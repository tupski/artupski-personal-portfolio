<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Page>
 */
class PageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->randomElement([
            'About', 'Now', 'Uses', 'Resume', 'Services', 'Speaking', 'Colophon',
        ]);

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'excerpt' => 'A short, human summary of what this page covers and who it is for.',
            'content' => '<h2>What this page is</h2><p>Placeholder body copy written like real prose so spacing, measure and heading rhythm can be judged before the final content lands.</p><p>It links to <a href="/projects">projects</a> and ends with a clear next step.</p>',
            'content_blocks' => null,
            'status' => ContentStatus::Draft,
            'published_at' => null,
            'is_navigable' => true,
            'seo_title' => null,
            'seo_description' => null,
            'canonical_url' => null,
            'og_image_path' => null,
            'created_by' => null,
            'updated_by' => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Published,
            'published_at' => now()->subDays(fake()->numberBetween(1, 120)),
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Scheduled,
            'published_at' => now()->addDays(fake()->numberBetween(1, 14)),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Archived,
            'published_at' => now()->subMonths(8),
        ]);
    }

    public function hiddenFromNavigation(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_navigable' => false,
        ]);
    }
}
