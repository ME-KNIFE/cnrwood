<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

/**
 * Public website controller — read-only.
 *
 * Phase 7A scope: home, product listing, category, product detail.
 * Strict rules:
 *   - Only active records.
 *   - Quote-only products NEVER show price (Product::getDisplayPrice() returns null).
 *   - No checkout, no real submissions, no DB writes from these actions.
 */
class PublicController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::active()
            ->featured()
            ->with(['category', 'images'])
            ->limit(8)
            ->get();

        // Fallback: if no featured products yet, show the latest active ones
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::active()
                ->with(['category', 'images'])
                ->latest()
                ->limit(8)
                ->get();
        }

        $rootCategories = ProductCategory::active()
            ->root()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        return view('public.home', [
            'featuredProducts' => $featuredProducts,
            'rootCategories'   => $rootCategories,
        ]);
    }

    public function products(Request $request)
    {
        // Whitelisted, validated GET params only.
        $type = $request->query('tip');
        if (! in_array($type, ['buyable', 'quote_only'], true)) {
            $type = null;
        }

        $categorySlug = (string) $request->query('kategori', '');
        $search       = trim((string) $request->query('q', ''));

        $query = Product::active()->with(['category', 'images']);

        if ($type !== null) {
            $query->where('product_type', $type);
        }

        if ($categorySlug !== '') {
            $cat = ProductCategory::active()->where('slug', $categorySlug)->first();
            if ($cat !== null) {
                $query->where('product_category_id', $cat->id);
            }
        }

        if ($search !== '' && mb_strlen($search) <= 100) {
            // Safe LIKE — JSON name column is text in storage; cast handled by DB.
            $like = '%' . $search . '%';
            $query->where(function ($q) use ($like) {
                $q->where('name', 'like', $like)
                  ->orWhere('sku', 'like', $like)
                  ->orWhere('slug', 'like', $like);
            });
        }

        $products = $query
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        $categories = ProductCategory::active()
            ->orderBy('sort_order')
            ->get();

        return view('public.products', [
            'products'      => $products,
            'categories'    => $categories,
            'selectedType'  => $type,
            'selectedSlug'  => $categorySlug,
            'searchTerm'    => $search,
        ]);
    }

    public function category(string $slug)
    {
        $category = ProductCategory::active()
            ->where('slug', $slug)
            ->firstOrFail();

        $products = Product::active()
            ->where('product_category_id', $category->id)
            ->with(['category', 'images'])
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(12);

        $siblings = ProductCategory::active()
            ->where('parent_id', $category->parent_id)
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->limit(8)
            ->get();

        return view('public.category', [
            'category' => $category,
            'products' => $products,
            'siblings' => $siblings,
        ]);
    }

    public function product(string $slug)
    {
        $product = Product::active()
            ->where('slug', $slug)
            ->with(['category', 'images', 'variants'])
            ->firstOrFail();

        $related = Product::active()
            ->where('product_category_id', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->with(['images'])
            ->limit(4)
            ->get();

        return view('public.product', [
            'product' => $product,
            'related' => $related,
        ]);
    }

    // ── Phase 7E — Static content pages (read-only) ─────────────────────────
    public function corporate()
    {
        return view('public.corporate');
    }

    public function about()
    {
        return view('public.about');
    }

    public function services()
    {
        return view('public.services');
    }

    public function sandik()
    {
        return view('public.sandik');
    }
}
