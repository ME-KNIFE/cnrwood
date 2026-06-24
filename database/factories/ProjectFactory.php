<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        return [
            'slug'         => Str::slug($title) . '-' . $this->faker->unique()->numberBetween(1, 9999),
            'status'       => 'draft',
            'is_published' => false,
            'is_featured'  => false,
            'sort_order'   => 0,
            'title'        => ['tr' => $title, 'en' => $title],
            'excerpt_tr'   => $this->faker->sentence(10),
            'content_tr'   => $this->faker->paragraph(3),
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => true,
            'status'       => 'published',
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    public function withCoverImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'cover_image_path' => 'project-images/placeholder.jpg',
            'image_alt_tr'     => 'Test gorsel',
        ]);
    }
}
