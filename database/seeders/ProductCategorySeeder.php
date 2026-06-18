<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ── Root categories (from PROJECT_MAP §3) ─────────────────────────────
        $roots = [
            [
                'slug' => 'sandik-ve-ambalaj',
                'name' => ['tr' => 'Sandık ve Ambalaj', 'en' => 'Crates & Packaging'],
                'sort_order' => 1,
                'children' => [
                    ['slug' => 'ahsap-sandik',       'name' => ['tr' => 'Ahşap Sandık',       'en' => 'Wooden Crates'],       'sort_order' => 1],
                    ['slug' => 'osb-sandik',          'name' => ['tr' => 'OSB Sandık',          'en' => 'OSB Crates'],          'sort_order' => 2],
                    ['slug' => 'ihracat-ambalaj',     'name' => ['tr' => 'İhracat Ambalajı',    'en' => 'Export Packaging'],    'sort_order' => 3],
                    ['slug' => 'ozel-ambalaj',        'name' => ['tr' => 'Özel Ambalaj',        'en' => 'Custom Packaging'],    'sort_order' => 4],
                ],
            ],
            [
                'slug' => 'palet',
                'name' => ['tr' => 'Palet', 'en' => 'Pallets'],
                'sort_order' => 2,
                'children' => [
                    ['slug' => 'euro-palet',          'name' => ['tr' => 'Euro Palet',          'en' => 'Euro Pallet'],         'sort_order' => 1],
                    ['slug' => 'ispm15-palet',        'name' => ['tr' => 'ISPM15 Palet',        'en' => 'ISPM15 Pallet'],       'sort_order' => 2],
                    ['slug' => 'ozel-palet',          'name' => ['tr' => 'Özel Palet',          'en' => 'Custom Pallet'],       'sort_order' => 3],
                ],
            ],
            [
                'slug' => 'kagit-ve-karton',
                'name' => ['tr' => 'Kağıt ve Karton Ambalaj', 'en' => 'Paper & Cardboard Packaging'],
                'sort_order' => 3,
                'children' => [
                    ['slug' => 'oluklu-mukavva',      'name' => ['tr' => 'Oluklu Mukavva',      'en' => 'Corrugated Cardboard'], 'sort_order' => 1],
                    ['slug' => 'masif-karton',        'name' => ['tr' => 'Masif Karton',        'en' => 'Solid Cardboard'],      'sort_order' => 2],
                ],
            ],
            [
                'slug' => 'diger-ambalaj',
                'name' => ['tr' => 'Diğer Ambalaj Ürünleri', 'en' => 'Other Packaging Products'],
                'sort_order' => 4,
                'children' => [],
            ],
        ];

        foreach ($roots as $rootData) {
            $children = $rootData['children'];
            unset($rootData['children']);

            $root = ProductCategory::updateOrCreate(
                ['slug' => $rootData['slug']],
                array_merge($rootData, ['is_active' => true, 'parent_id' => null])
            );

            foreach ($children as $childData) {
                ProductCategory::updateOrCreate(
                    ['slug' => $childData['slug']],
                    array_merge($childData, ['is_active' => true, 'parent_id' => $root->id])
                );
            }
        }

        $this->command->info('ProductCategory seeder: ' . ProductCategory::count() . ' categories created.');
    }
}
