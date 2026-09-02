<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tag>
 */
class TagFactory extends Factory
{
    /**
     * @var array<int, string>
     */
    private const NAMES = [
        'Laravel', 'PHP', 'JavaScript', 'Turbo', 'Hotwire', 'Filament', 'AI',
        'OpenAI', 'Supabase', 'Vercel', 'SEO', 'Automation', 'WordPress',
        'Tailwind CSS', 'MySQL', 'Redis', 'Docker', 'Testing', 'Performance',
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(self::NAMES),
            'is_indexable' => false,
        ];
    }

    public function indexable(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_indexable' => true,
        ]);
    }
}
