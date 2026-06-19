<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $word = fake()->unique()->numerify('product-###');

        return [
            'name'          => ['tr' => 'Ürün ' . $word, 'en' => 'Product ' . $word],
            'product_type'  => 'buyable',
            'price'         => fake()->randomFloat(2, 50, 2000),
            'is_active'     => true,
            'is_featured'   => false,
            'track_stock'   => false,
            'stock_quantity' => 0,
        ];
    }

    public function buyable(): static
    {
        return $this->state(fn () => [
            'product_type' => 'buyable',
            'price'        => fake()->randomFloat(2, 50, 2000),
        ]);
    }

    public function quoteOnly(): static
    {
        return $this->state(fn () => [
            'product_type' => 'quote_only',
            'price'        => null,
        ]);
    }
}
