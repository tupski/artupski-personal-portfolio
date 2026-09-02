<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Broad topics only — categories are not tags (spec §17).
     *
     * @var array<int, array{0: string, 1: string}>
     */
    private const TOPICS = [
        ['Development', 'Hands-on engineering notes from shipping real products.'],
        ['AI', 'Practical uses of language models in production software.'],
        ['Laravel', 'Framework patterns, upgrades and things learned the hard way.'],
        ['Web Development', 'Frontend, performance and the modern server-rendered stack.'],
        ['Automation', 'Removing repetitive work with small, reliable scripts.'],
        ['SEO', 'Technical SEO for developers who own their own markup.'],
        ['Tools', 'The editors, CLIs and services that make the work faster.'],
        ['Projects', 'Build logs and post-mortems from client and personal work.'],
        ['Personal', 'Career, focus and how the work actually gets done.'],
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        [$name, $description] = fake()->randomElement(self::TOPICS);

        return [
            'name' => $name,
            'description' => $description,
            'is_indexable' => true,
            'sort_order' => 0,
        ];
    }

    public function notIndexable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_indexable' => false,
        ]);
    }
}
