<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Realistic developer-oriented titles — no lorem ipsum (spec §106).
     *
     * @var array<int, array{0: string, 1: string}>
     */
    private const ARTICLES = [
        ['Why I still server-render everything', 'An SPA was never the constraint. Latency, cache headers and HTML were.'],
        ['Scheduled publishing without a cron surprise', 'How a one-minute command and a strict status enum keep drafts invisible.'],
        ['Sanitising editor HTML on save, not on render', 'Trading a one-off backfill for a permanently cheaper render path.'],
        ['Turbo Frames for blog filtering', 'Query params, one route, zero JSON endpoints.'],
        ['Designing a settings table you will not regret', 'Key/value rows, a type column, and one cached repository.'],
        ['Reading time is a derived value', 'Anything an author can mistype is a value you should compute.'],
        ['Media conversions when imagick is missing', 'What changes when gd is the only image driver on the box.'],
        ['Slug changes and the 301 you owe your readers', 'Permanent URLs are a promise, not a preference.'],
        ['Indexing for the queries you actually run', 'Composite indexes earn their keep; speculative ones do not.'],
        ['Eager loading without loading everything', 'Two fixed relation sets per model beat guessing per controller.'],
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        [$title, $excerpt] = fake()->unique()->randomElement(self::ARTICLES);

        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'project_id' => null,
            'title' => $title,
            'excerpt' => $excerpt,
            'content' => $this->body(),
            'content_blocks' => null,
            'status' => ContentStatus::Draft,
            'published_at' => null,
            'is_featured' => false,
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
            'published_at' => now()->subDays(fake()->numberBetween(1, 240)),
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
            'published_at' => now()->subYear(),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    private function body(): string
    {
        return <<<'HTML'
            <p>The short version: the boring option was the right one, and the interesting option would have cost a week of debugging for no user-visible gain.</p>
            <h2>The problem</h2>
            <p>Two requirements pulled in opposite directions. The first wanted the data denormalised for fast reads. The second wanted a single source of truth so the admin could not produce contradictory rows.</p>
            <h2>What I tried first</h2>
            <p>The first attempt pushed the derived value into the request path. It worked until the cache was cold, at which point every visitor paid for it.</p>
            <h3>The fix</h3>
            <p>Move the work to the write path. Writes are rare, reads are not, and the derived value only changes when the source column does.</p>
            <ul>
                <li>Compute on save, never on render.</li>
                <li>Store the result next to the data it came from.</li>
                <li>Recompute when the source column is dirty.</li>
            </ul>
            <h2>What I would do differently</h2>
            <p>Write the test for the invisible case first. The visible cases were obvious; the one that shipped a bug was the empty-content edge.</p>
            HTML;
    }
}
