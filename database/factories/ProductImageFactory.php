<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id'  => Product::factory(),
            'url'         => 'product-images/placeholder.jpg',
            'alt_text'    => ['tr' => 'Test görsel', 'en' => 'Test image'],
            'is_primary'  => false,
            'is_active'   => true,
            'sort_order'  => 0,
        ];
    }

    public function primary(): static
    {
        return $this->state(fn () => ['is_primary' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
