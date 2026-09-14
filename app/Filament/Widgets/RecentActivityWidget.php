<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Support\Collection;

/**
 * Cross-content activity feed.
 *
 * Bug fixed here: the table queried an intentionally empty model
 * (Post::whereRaw('1 = 0')) while the real rows sat unused in `$activities`,
 * so the widget rendered a permanently empty table. Filament supports passing a
 * prepared collection straight to the table, which is what the widget always meant
 * to do — no backend contract was touched to achieve it.
 *
 * Ordering follows §9 of the brief: the actionable columns (type, status) come
 * first, timestamps last, and the heading states the window so the reader knows
 * what "recent" means.
 */
class RecentActivityWidget extends TableWidget
{
    protected static ?string $heading = 'Recent Activity';

    protected static ?string $description = 'Latest 5 changes across posts, projects and messages.';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->records(fn (): Collection => $this->activities())
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (array $record) => match ($record['type']) {
                        'Post' => 'info',
                        'Project' => 'success',
                        'Message' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->wrap()
                    ->searchable(false),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (array $record) => match ($record['status']) {
                        'published' => 'success',
                        'scheduled' => 'warning',
                        'unread' => 'danger',
                        'draft', 'read' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('date')
                    ->label('Changed')
                    ->dateTime('M j, g:i A')
                    ->sortable(false),
            ])
            ->paginated(false)
            ->emptyStateHeading('Nothing has changed yet')
            ->emptyStateDescription('Create a post or project, or wait for a message, and it will appear here.')
            ->emptyStateIcon('heroicon-o-clock');
    }

    /**
     * @return Collection<int, array{type: string, title: string, status: string, date: mixed, url: string}>
     */
    protected function activities(): Collection
    {
        $activities = collect();

        Post::latest('updated_at')->limit(3)->get()->each(function (Post $post) use ($activities) {
            $activities->push([
                'type' => 'Post',
                'title' => $post->title,
                'status' => $post->status->value,
                'date' => $post->updated_at,
                'url' => route('filament.tupasadmin.resources.posts.edit', $post),
            ]);
        });

        Project::latest('updated_at')->limit(3)->get()->each(function (Project $project) use ($activities) {
            $activities->push([
                'type' => 'Project',
                'title' => $project->title,
                'status' => $project->status->value,
                'date' => $project->updated_at,
                'url' => route('filament.tupasadmin.resources.projects.edit', $project),
            ]);
        });

        ContactMessage::latest('created_at')->limit(3)->get()->each(function (ContactMessage $message) use ($activities) {
            $activities->push([
                'type' => 'Message',
                'title' => $message->name.': '.($message->subject ?? 'No subject'),
                'status' => $message->status->value,
                'date' => $message->created_at,
                'url' => route('filament.tupasadmin.resources.contact-messages.view', $message),
            ]);
        });

        return $activities->sortByDesc('date')->take(5)->values();
    }
}
