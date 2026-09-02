<?php

namespace Database\Factories;

use App\Enums\ContentStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @var array<int, array{0: string, 1: string}>
     */
    private const IDEAS = [
        ['Regional Tourism Portal', 'A multi-language destination portal with editorial workflow and offline-friendly maps.'],
        ['Serviced Apartment CMS', 'A booking-aware content system with rate calendars and channel exports.'],
        ['Invoice Automation Suite', 'Reads supplier emails, extracts line items and posts drafts to the ledger.'],
        ['Support Triage Assistant', 'Classifies inbound tickets and drafts first replies for a human to approve.'],
        ['Internal Ops Dashboard', 'One screen replacing four spreadsheets for a distributed operations team.'],
        ['Village Data Registry', 'A government reporting tool with strict audit trails and printable forms.'],
        ['Headless Storefront', 'Product catalogue and checkout split across a CMS and a payments provider.'],
    ];

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        [$title, $shortDescription] = fake()->unique()->randomElement(self::IDEAS);

        $startedOn = fake()->dateTimeBetween('-4 years', '-8 months');

        return [
            'user_id' => User::factory(),
            'title' => $title,
            'short_description' => $shortDescription,
            'content' => '<h2>Overview</h2><p>What the client needed, the constraints that shaped the build, and how the finished system behaves in production.</p><h2>Implementation</h2><p>Server-rendered pages, a small amount of progressive enhancement, and a schema designed around how the data is actually read.</p>',
            'content_blocks' => null,
            'challenges' => 'Legacy data with inconsistent identifiers, and a hard deadline tied to an external launch.',
            'solutions' => 'Normalised the data behind an import command, then shipped the read paths first so stakeholders could review real content early.',
            'results' => 'Publishing time dropped from hours to minutes and the team stopped maintaining the old spreadsheets.',
            'project_type' => fake()->randomElement(ProjectType::cases()),
            'client' => fake()->boolean(70) ? fake()->company() : null,
            'role' => fake()->randomElement([
                'Lead developer', 'Full-stack developer', 'Technical lead', 'Solo developer',
            ]),
            'started_on' => $startedOn,
            'ended_on' => fake()->boolean(80)
                ? fake()->dateTimeBetween($startedOn, '-1 month')
                : null,
            'project_status' => fake()->randomElement(ProjectStatus::cases()),
            'status' => ContentStatus::Draft,
            'published_at' => null,
            'live_url' => fake()->boolean(70) ? 'https://'.fake()->domainName() : null,
            'repository_url' => fake()->boolean(40) ? 'https://github.com/tupski/'.fake()->slug(2) : null,
            'is_featured' => false,
            'sort_order' => 0,
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
            'published_at' => now()->subDays(fake()->numberBetween(1, 200)),
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Scheduled,
            'published_at' => now()->addDays(fake()->numberBetween(1, 21)),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContentStatus::Archived,
            'published_at' => now()->subYear(),
        ]);
    }

    public function featured(int $sortOrder = 0): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
            'sort_order' => $sortOrder,
        ]);
    }

    public function ongoing(): static
    {
        return $this->state(fn (array $attributes) => [
            'ended_on' => null,
            'project_status' => ProjectStatus::InProgress,
        ]);
    }
}
