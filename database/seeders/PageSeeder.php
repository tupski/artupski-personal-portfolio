<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * The CMS pages behind the named routes in BUILD-PLAN §5. Idempotent on slug.
 *
 * `status`, `published_at` and `user_id` are assigned here rather than mass-assigned,
 * matching the rule that publishing fields are set server-side (BUILD-PLAN §7).
 */
class PageSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->orderBy('id')->first();

        /** @var array<int, array<string, mixed>> $pages */
        $pages = require database_path('data/pages.php');

        foreach ($pages as $data) {
            $page = Page::firstOrNew(['slug' => $data['slug']]);

            if ($page->exists) {
                continue;
            }

            $status = ContentStatus::from($data['status']);

            $page->fill([
                'title' => $data['title'],
                'excerpt' => $data['excerpt'],
                'content' => $data['content'],
                'is_navigable' => $data['is_navigable'],
            ]);

            $page->slug = $data['slug'];
            $page->user_id = $owner?->id;
            $page->created_by = $owner?->id;
            $page->updated_by = $owner?->id;
            $page->status = $status;
            $page->published_at = $status === ContentStatus::Published ? now()->subMonth() : null;

            $page->save();
        }
    }
}
