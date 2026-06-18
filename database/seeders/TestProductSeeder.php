<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class TestProductSeeder extends Seeder
{
    public function run(): void
    {
        $buyableCategory   = ProductCategory::where('slug', 'euro-palet')->first();
        $quoteOnlyCategory = ProductCategory::where('slug', 'ahsap-sandik')->first();

        if (! $buyableCategory || ! $quoteOnlyCategory) {
            $this->command->error('Run ProductCategorySeeder first!');
            return;
        }

        // ── 3 BUYABLE products ────────────────────────────────────────────────
        $buyable = [
            [
                'sku'          => 'TEST-BUYABLE-001',
                'slug'         => 'test-euro-palet-80x120',
                'product_type' => 'buyable',
                'name'         => ['tr' => 'Euro Palet 80x120 cm', 'en' => 'Euro Pallet 80x120 cm'],
                'description'  => ['tr' => 'Standart Euro palet, ISPM15 sertifikalı.', 'en' => 'Standard Euro pallet, ISPM15 certified.'],
                'price'        => 85.00,
                'stock_quantity' => 100,
                'track_stock'  => true,
                'is_active'    => true,
                'product_category_id' => $buyableCategory->id,
            ],
            [
                'sku'          => 'TEST-BUYABLE-002',
                'slug'         => 'test-euro-palet-100x120',
                'product_type' => 'buyable',
                'name'         => ['tr' => 'Euro Palet 100x120 cm', 'en' => 'Euro Pallet 100x120 cm'],
                'description'  => ['tr' => 'Büyük Euro palet, ihracat için uygundur.', 'en' => 'Large Euro pallet, suitable for export.'],
                'price'        => 110.00,
                'stock_quantity' => 50,
                'track_stock'  => true,
                'is_active'    => true,
                'product_category_id' => $buyableCategory->id,
            ],
            [
                'sku'          => 'TEST-BUYABLE-003',
                'slug'         => 'test-ispm15-palet',
                'product_type' => 'buyable',
                'name'         => ['tr' => 'ISPM15 Sertifikalı Palet', 'en' => 'ISPM15 Certified Pallet'],
                'description'  => ['tr' => 'Uluslararası nakliye için ISPM15 sertifikalı ahşap palet.', 'en' => 'ISPM15 certified wooden pallet for international shipping.'],
                'price'        => 135.00,
                'stock_quantity' => 200,
                'track_stock'  => true,
                'is_active'    => true,
                'product_category_id' => $buyableCategory->id,
            ],
        ];

        // ── 3 QUOTE_ONLY products — NO price, NO stock ────────────────────────
        $quoteOnly = [
            [
                'sku'          => 'TEST-QUOTE-001',
                'slug'         => 'test-ozel-ahsap-sandik',
                'product_type' => 'quote_only',
                'name'         => ['tr' => 'Özel Ölçü Ahşap Sandık', 'en' => 'Custom Size Wooden Crate'],
                'description'  => ['tr' => 'Müşteri ölçülerine göre üretilen özel ahşap sandık. Fiyat için teklif alın.', 'en' => 'Custom wooden crate built to customer specs. Request a quote for pricing.'],
                'price'        => null,   // ← NEVER set price for quote_only
                'stock_quantity' => null, // ← NEVER track stock for quote_only
                'track_stock'  => false,
                'is_active'    => true,
                'product_category_id' => $quoteOnlyCategory->id,
            ],
            [
                'sku'          => 'TEST-QUOTE-002',
                'slug'         => 'test-ihracat-sandik-sistemi',
                'product_type' => 'quote_only',
                'name'         => ['tr' => 'İhracat Sandık Sistemi', 'en' => 'Export Crate System'],
                'description'  => ['tr' => 'Büyük ekipmanlar için özel tasarım ihracat sandık sistemi.', 'en' => 'Custom-designed export crate system for large equipment.'],
                'price'        => null,
                'stock_quantity' => null,
                'track_stock'  => false,
                'is_active'    => true,
                'product_category_id' => $quoteOnlyCategory->id,
            ],
            [
                'sku'          => 'TEST-QUOTE-003',
                'slug'         => 'test-vinc-aparatli-sandik',
                'product_type' => 'quote_only',
                'name'         => ['tr' => 'Vinç Aparatlı Sandık', 'en' => 'Crane-Ready Crate'],
                'description'  => ['tr' => 'Vinç ile taşıma için özel aparat kaynaklı sandık. Proje bazlı üretim.', 'en' => 'Crate with welded crane lift points. Project-based production.'],
                'price'        => null,
                'stock_quantity' => null,
                'track_stock'  => false,
                'is_active'    => true,
                'product_category_id' => $quoteOnlyCategory->id,
            ],
        ];

        foreach (array_merge($buyable, $quoteOnly) as $productData) {
            Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
        }

        $this->command->info('TestProduct seeder: 3 buyable + 3 quote_only products created.');
        $this->command->info('Verify: Product::buyable()->count() should return 3');
        $this->command->info('Verify: Product::quoteOnly()->count() should return 3');
        $this->command->info('Verify: Product::quoteOnly()->whereNotNull("price")->count() should return 0');
    }
}
