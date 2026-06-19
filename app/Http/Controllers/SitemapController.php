<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/**
 * Phase 7D — Dynamic XML sitemap.
 *
 * Includes ONLY public, indexable, GET-only pages:
 *   - Static: /, /urunler, /iletisim, /teklif-al
 *   - Active categories (/kategori/{slug})
 *   - Active products   (/urun/{slug})
 *
 * Excludes: /admin, /magaza-panel, POST routes, thank-you pages,
 * product-specific quote form (duplicate-content w/ /teklif-al).
 */
class SitemapController extends Controller
{
    public function index(): Response
    {
        $now = Carbon::now()->toAtomString();

        $urls = [
            ['loc' => route('home'),                'lastmod' => $now, 'changefreq' => 'weekly',  'priority' => '1.0'],
            ['loc' => route('public.products'),     'lastmod' => $now, 'changefreq' => 'weekly',  'priority' => '0.9'],
            ['loc' => route('public.contact'),      'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => route('public.quote.create'), 'lastmod' => $now, 'changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        ProductCategory::query()
            ->where('is_active', true)
            ->whereNotNull('slug')
            ->select('slug', 'updated_at')
            ->orderBy('id')
            ->chunk(500, function ($categories) use (&$urls) {
                foreach ($categories as $cat) {
                    $urls[] = [
                        'loc'        => route('public.category', ['slug' => $cat->slug]),
                        'lastmod'    => optional($cat->updated_at)->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.7',
                    ];
                }
            });

        Product::query()
            ->where('is_active', true)
            ->whereNotNull('slug')
            ->select('slug', 'updated_at')
            ->orderBy('id')
            ->chunk(500, function ($products) use (&$urls) {
                foreach ($products as $p) {
                    $urls[] = [
                        'loc'        => route('public.product', ['slug' => $p->slug]),
                        'lastmod'    => optional($p->updated_at)->toAtomString(),
                        'changefreq' => 'weekly',
                        'priority'   => '0.6',
                    ];
                }
            });

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
            if (! empty($u['lastmod'])) {
                $xml .= '    <lastmod>' . $u['lastmod'] . "</lastmod>\n";
            }
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= '</urlset>' . "\n";

        return response($xml, 200, [
            'Content-Type'  => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
