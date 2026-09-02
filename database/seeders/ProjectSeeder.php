<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Models\Project;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Portfolio projects from database/data/projects.php (spec §106, §13). Idempotent on slug.
 * Technologies are attached by slug so the pivot survives a re-seed without duplicating.
 */
class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->orderBy('id')->first();
        $technologies = Technology::query()->pluck('id', 'slug');

        /** @var array<int, array<string, mixed>> $projects */
        $projects = require database_path('data/projects.php');

        foreach ($projects as $data) {
            $project = Project::firstOrNew(['slug' => $data['slug']]);

            if (! $project->exists) {
                $status = ContentStatus::from($data['status']);

                $project->fill([
                    'title' => $data['title'],
                    'short_description' => $data['short_description'],
                    'content' => $data['content'],
                    'challenges' => $data['challenges'],
                    'solutions' => $data['solutions'],
                    'results' => $data['results'],
                    'project_type' => $data['project_type'],
                    'client' => $data['client'],
                    'role' => $data['role'],
                    'started_on' => $data['started_on'],
                    'ended_on' => $data['ended_on'],
                    'project_status' => $data['project_status'],
                    'live_url' => $data['live_url'],
                    'repository_url' => $data['repository_url'],
                    'is_featured' => $data['is_featured'],
                    'sort_order' => $data['sort_order'],
                ]);

                $project->slug = $data['slug'];
                $project->user_id = $owner?->id;
                $project->created_by = $owner?->id;
                $project->updated_by = $owner?->id;
                $project->status = $status;
                $project->published_at = $status === ContentStatus::Published
                    ? ($data['ended_on'] ?? now()->subMonths(2))
                    : null;

                $project->save();
            }

            /** @var array<int, string> $names */
            $names = $data['technologies'];

            $ids = collect($names)
                ->map(fn (string $name): ?int => $technologies[str($name)->slug()->value()] ?? null)
                ->filter()
                ->all();

            $project->technologies()->sync($ids);
        }
    }
}
