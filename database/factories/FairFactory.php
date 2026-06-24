<?php

namespace Database\Factories;

use App\Models\Fair;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class FairFactory extends Factory
{
    protected $model = Fair::class;

    public function definition(): array
    {
        $name  = $this->faker->company() . ' ' . $this->faker->randomElement(['Fuari', 'Zirvesi', 'Sergisi', 'Kongresi']);
        $start = $this->faker->dateTimeBetween('-2 years', '+1 year');

        return [
            'slug'         => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'name'         => ['tr' => $name, 'en' => $name],
            'description'  => ['tr' => $this->faker->sentence(10), 'en' => null],
            'city'         => $this->faker->randomElement(['Istanbul', 'Ankara', 'Izmir', 'Frankfurt', 'Dubai']),
            'venue'        => null,
            'start_date'   => $start->format('Y-m-d'),
            'end_date'     => null,
            'sort_order'   => 0,
            'is_published' => false,
            'is_featured'  => false,
            'cover_image_path' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->addDays(rand(5, 90))->format('Y-m-d'),
        ]);
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => now()->subDays(rand(10, 365))->format('Y-m-d'),
        ]);
    }

    public function withCoverImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'cover_image_path' => 'fair-images/placeholder.jpg',
            'image_alt_tr'     => 'Test fuar gorseli',
        ]);
    }
}
