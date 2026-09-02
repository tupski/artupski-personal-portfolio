<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use App\Models\Post;
use App\Models\Project;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class RecentActivityWidget extends TableWidget
{
    protected static ?string $heading = 'Recent Activity';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $activities = collect();

        // Recent posts
        Post::latest()->limit(3)->get()->each(function ($post) use ($activities) {
            $activities->push([
                'type' => 'Post',
                'title' => $post->title,
                'status' => $post->status->value,
                'date' => $post->updated_at,
                'url' => route('filament.tupasadmin.resources.post.edit', $post),
            ]);
        });

        // Recent projects
        Project::latest()->limit(3)->get()->each(function ($project) use ($activities) {
            $activities->push([
                'type' => 'Project',
                'title' => $project->title,
                'status' => $project->status->value,
                'date' => $project->updated_at,
                'url' => route('filament.tupasadmin.resources.project.edit', $project),
            ]);
        });

        // Recent messages
        ContactMessage::latest()->limit(3)->get()->each(function ($message) use ($activities) {
            $activities->push([
                'type' => 'Message',
                'title' => $message->name.': '.($message->subject ?? 'No subject'),
                'status' => $message->status->value,
                'date' => $message->created_at,
                'url' => route('filament.tupasadmin.resources.contact-message.view', $message),
            ]);
        });

        // Sort by date, take top 5
        $activities = $activities->sortByDesc('date')->take(5)->values();

        return $table
            ->query(
                Post::whereRaw('1 = 0') // Empty query, we'll use custom data
            )
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn ($record) => match ($record['type']) {
                        'Post' => 'info',
                        'Project' => 'success',
                        'Message' => 'warning',
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($record) => match ($record['status']) {
                        'published' => 'success',
                        'draft' => 'gray',
                        'scheduled' => 'warning',
                        'unread' => 'danger',
                        'read' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('date')
                    ->label('Updated')
                    ->dateTime('M j, g:i A'),
            ])
            ->paginated(false);
    }
}
