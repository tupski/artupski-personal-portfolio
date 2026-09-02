<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Blog posts from database/data/posts.php, covering published, draft, scheduled and
 * archived states (spec §106, §45). Idempotent on slug; tags are synced by slug.
 */
class PostSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::query()->orderBy('id')->first();
        $categories = Category::query()->pluck('id', 'slug');
        $tags = Tag::query()->pluck('id', 'slug');
        $projects = Project::query()->pluck('id', 'slug');

        /** @var array<int, array<string, mixed>> $posts */
        $posts = require database_path('data/posts.php');

        foreach ($posts as $data) {
            $post = Post::firstOrNew(['slug' => $data['slug']]);

            if (! $post->exists) {
                $status = ContentStatus::from($data['status']);
                $offset = $data['published_days_ago'];

                $post->fill([
                    'title' => $data['title'],
                    'excerpt' => $data['excerpt'],
                    'content' => $data['content'],
                    'is_featured' => $data['is_featured'],
                    'category_id' => $categories[str($data['category'])->slug()->value()] ?? null,
                    'project_id' => $data['project'] !== null
                        ? ($projects[$data['project']] ?? null)
                        : null,
                ]);

                $post->slug = $data['slug'];
                $post->user_id = $author?->id;
                $post->created_by = $author?->id;
                $post->updated_by = $author?->id;
                $post->status = $status;

                // Negative offsets mean "in the future", used by the scheduled rows.
                $post->published_at = $offset === null ? null : now()->subDays($offset);

                $post->save();
            }

            /** @var array<int, string> $names */
            $names = $data['tags'];

            $ids = collect($names)
                ->map(fn (string $name): ?int => $tags[str($name)->slug()->value()] ?? null)
                ->filter()
                ->all();

            $post->tags()->sync($ids);
        }
    }
}
