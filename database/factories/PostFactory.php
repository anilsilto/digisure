<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = rtrim(fake()->sentence(5), '.');

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'excerpt' => fake()->sentence(12),
            'body' => "## Giriş\n\n".fake()->paragraph(4)."\n\n## Detay\n\n".fake()->paragraph(4),
            'status' => 'taslak',
            'published_at' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => 'yayinda',
            'published_at' => now()->subDays(fake()->numberBetween(1, 60)),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'taslak', 'published_at' => null]);
    }
}
