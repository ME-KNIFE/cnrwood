<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'user_id'             => null, // set via for() or state
            'title'               => fake()->randomElement(['Ev', 'İş', 'Depo', 'Fabrika']),
            'full_name'           => fake()->name(),
            'phone'               => '05' . fake()->numerify('#########'),
            'address_line1'       => fake()->streetAddress(),
            'address_line2'       => null,
            'city'                => fake()->randomElement(['İstanbul', 'Ankara', 'İzmir', 'Kocaeli', 'Bursa']),
            'district'            => fake()->city(),
            'postal_code'         => fake()->numerify('#####'),
            'country'             => 'Türkiye',
            'is_default_shipping' => false,
            'is_default_billing'  => false,
        ];
    }

    public function defaultShipping(): static
    {
        return $this->state(['is_default_shipping' => true]);
    }

    public function defaultBilling(): static
    {
        return $this->state(['is_default_billing' => true]);
    }
}
