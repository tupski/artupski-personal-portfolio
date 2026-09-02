<?php

namespace Database\Factories;

use App\Models\Technology;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Technology>
 */
class TechnologyFactory extends Factory
{
    /**
     * @var array<int, array{0: string, 1: string, 2: string}>
     */
    private const STACK = [
        ['Laravel', 'https://laravel.com', 'The framework behind most of what I ship.'],
        ['PHP', 'https://www.php.net', 'Primary language, 8.3+ with strict types.'],
        ['Tailwind CSS', 'https://tailwindcss.com', 'Utility-first styling with a small design system on top.'],
        ['Turbo', 'https://turbo.hotwired.dev', 'Server-rendered navigation without an SPA.'],
        ['Stimulus', 'https://stimulus.hotwired.dev', 'Small behaviour sprinkles where HTML is not enough.'],
        ['Filament', 'https://filamentphp.com', 'Admin panels that clients actually enjoy using.'],
        ['MySQL', 'https://www.mysql.com', 'Default relational store, MariaDB-compatible.'],
        ['Alpine.js', 'https://alpinejs.dev', 'Used only inside admin panels that bundle it.'],
        ['Vite', 'https://vite.dev', 'Asset pipeline for every frontend build.'],
        ['Supabase', 'https://supabase.com', 'Postgres plus auth when a project starts serverless.'],
        ['OpenAI API', 'https://platform.openai.com', 'LLM features behind server-side endpoints.'],
        ['Docker', 'https://www.docker.com', 'Reproducible local environments and deploys.'],
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        [$name, $url, $description] = fake()->unique()->randomElement(self::STACK);

        return [
            'name' => $name,
            'url' => $url,
            'description' => $description,
            'icon' => null,
            'sort_order' => 0,
        ];
    }
}
