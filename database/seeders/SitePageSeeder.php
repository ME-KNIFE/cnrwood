<?php

namespace Database\Seeders;

use App\Models\SitePage;
use Illuminate\Database\Seeder;

class SitePageSeeder extends Seeder
{
    /**
     * Seed the core site pages.
     * Uses firstOrCreate so existing admin edits are never overwritten.
     * Content fields are intentionally left empty so views fall back to
     * their hardcoded defaults until an admin actively edits them.
     */
    public function run(): void
    {
        $pages = [
            [
                'slug'       => 'hakkimizda',
                'sort_order' => 1,
            ],
            [
                'slug'       => 'hizmetler',
                'sort_order' => 2,
            ],
            [
                'slug'       => 'kurumsal',
                'sort_order' => 3,
            ],
        ];

        foreach ($pages as $attrs) {
            SitePage::firstOrCreate(
                ['slug' => $attrs['slug']],
                array_merge($attrs, ['is_published' => true])
            );
        }
    }
}
