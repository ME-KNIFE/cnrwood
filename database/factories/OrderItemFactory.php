<?php

namespace Database\Factories;

use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $unitPrice = fake()->randomFloat(2, 50, 2000);
        $qty       = fake()->numberBetween(1, 5);

        return [
            'order_id'     => null, // set via relationship: $order->items()->create(...)
            'product_id'   => null,
            'product_name' => fake()->words(3, true),
            'product_sku'  => 'SKU-' . fake()->unique()->numerify('###'),
            'variant_name' => null,
            'quantity'     => $qty,
            'unit_price'   => $unitPrice,
            'total_price'  => round($unitPrice * $qty, 2),
        ];
    }
}
