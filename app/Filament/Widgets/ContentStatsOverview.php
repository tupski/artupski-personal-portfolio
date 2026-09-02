<?php

namespace App\Filament\Widgets;

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Published Posts', Post::where('status', 'published')->count())
                ->description('Live articles')
                ->descriptionIcon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('Draft Posts', Post::where('status', 'draft')->count())
                ->description('Work in progress')
                ->descriptionIcon('heroicon-o-pencil')
                ->color('gray'),

            Stat::make('Scheduled Posts', Post::where('status', 'scheduled')->count())
                ->description('Waiting to publish')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Projects', Project::where('status', 'published')->count())
                ->description('Published projects')
                ->descriptionIcon('heroicon-o-briefcase')
                ->color('info'),

            Stat::make('Unread Messages', ContactMessage::where('status', ContactMessageStatus::Unread)->count())
                ->description('Need attention')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('danger'),

            Stat::make('Pages', Page::where('status', 'published')->count())
                ->description('Published pages')
                ->descriptionIcon('heroicon-o-document-duplicate')
                ->color('primary'),
        ];
    }
}
