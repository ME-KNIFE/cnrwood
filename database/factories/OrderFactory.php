<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 100, 5000);

        return [
            'order_number'     => 'SIP-' . date('Y') . '-' . str_pad(fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'user_id'          => null,
            'customer_name'    => fake()->name(),
            'customer_email'   => fake()->safeEmail(),
            'customer_phone'   => '05' . fake()->numerify('#########'),
            'status'           => 'beklemede',
            'payment_method'   => 'havale_eft',
            'payment_status'   => 'beklemede',
            'subtotal'         => $subtotal,
            'discount_amount'  => 0,
            'shipping_cost'    => 0,
            'total'            => $subtotal,
            'shipping_address' => [
                'full_name'     => fake()->name(),
                'phone'         => '05' . fake()->numerify('#########'),
                'address_line1' => fake()->streetAddress(),
                'city'          => 'İstanbul',
                'country'       => 'Türkiye',
            ],
        ];
    }

    public function processing(): static
    {
        return $this->state(['status' => 'islemde', 'payment_status' => 'odendi']);
    }

    public function shipped(): static
    {
        return $this->state(['status' => 'kargoya_verildi', 'payment_status' => 'odendi']);
    }

    public function delivered(): static
    {
        return $this->state(['status' => 'teslim_edildi', 'payment_status' => 'odendi']);
    }

    public function cancelled(): static
    {
        return $this->state(['status' => 'iptal_edildi', 'cancelled_at' => now()]);
    }
}
