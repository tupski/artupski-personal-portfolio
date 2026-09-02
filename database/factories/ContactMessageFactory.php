<?php

namespace Database\Factories;

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Config;

/**
 * @extends Factory<ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'subject' => fake()->randomElement([
                'Laravel build for a booking platform',
                'Rescuing a stalled WordPress migration',
                'Automation for invoice processing',
                'Available for a two-month engagement?',
            ]),
            'message' => 'We are a small team looking for help shipping a booking flow on Laravel. The admin side exists but the public site needs building, and we would like it server-rendered. Timeline is flexible; quality is not.',
            'company' => fake()->boolean(60) ? fake()->company() : null,
            'budget' => fake()->boolean(50)
                ? fake()->randomElement(['< 5k USD', '5k - 10k USD', '10k - 25k USD', 'Not sure yet'])
                : null,
            'project_type' => fake()->boolean(50)
                ? fake()->randomElement(['Website', 'Web app', 'Automation', 'Consulting'])
                : null,
            'status' => ContactMessageStatus::Unread,
            'ip_hash' => hash('sha256', fake()->ipv4().Config::string('app.key')),
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0 Safari/537.36',
            'read_at' => null,
            'replied_at' => null,
        ];
    }

    public function read(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContactMessageStatus::Read,
            'read_at' => now()->subHours(fake()->numberBetween(1, 72)),
        ]);
    }

    public function replied(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContactMessageStatus::Replied,
            'read_at' => now()->subDays(3),
            'replied_at' => now()->subDays(2),
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ContactMessageStatus::Archived,
            'read_at' => now()->subMonth(),
        ]);
    }
}
