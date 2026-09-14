<?php

namespace App\Filament\Widgets;

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Dashboard numbers.
 *
 * Reordered by what the operator can act on, not by content type. Previously the six
 * tiles were laid out as post-status, post-status, post-status, projects, unread
 * messages, pages — which pushed the one metric that requires a human response
 * (unread messages) into position five and gave four tiles the same red/orange/grey
 * "attention" reading. Now:
 *
 *   1. Unread messages  — the only tile with an inbox behind it
 *   2. Drafts           — work the operator left unfinished
 *   3. Scheduled        — publishing that will happen without them
 *   4. Published posts / Projects / Pages — inventory, last, as a single reference row
 *
 * Icons and colour are kept only where they carry meaning (R-04): the accent tint marks
 * the actionable tile, everything else is neutral so the one exception is legible.
 * Counts are real queries, so no figure here is decorative (R-17).
 */
class ContentStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $unread = ContactMessage::where('status', ContactMessageStatus::Unread)->count();
        $drafts = Post::where('status', 'draft')->count();
        $scheduled = Post::where('status', 'scheduled')->count();

        return [
            Stat::make('Unread messages', $unread)
                ->description($unread > 0 ? 'Waiting for a reply' : 'Inbox is clear')
                ->descriptionIcon($unread > 0 ? 'heroicon-o-envelope' : 'heroicon-o-check-circle')
                ->color($unread > 0 ? 'danger' : 'gray'),

            Stat::make('Draft posts', $drafts)
                ->description($drafts > 0 ? 'Unfinished' : 'Nothing pending')
                ->descriptionIcon('heroicon-o-pencil-square')
                ->color('gray'),

            Stat::make('Scheduled posts', $scheduled)
                ->description($scheduled > 0 ? 'Publish automatically' : 'Queue empty')
                ->descriptionIcon('heroicon-o-clock')
                ->color('gray'),

            Stat::make('Published', Post::where('status', 'published')->count())
                ->description('Articles')
                ->color('gray'),

            Stat::make('Projects', Project::where('status', 'published')->count())
                ->description('Published')
                ->color('gray'),

            Stat::make('Pages', Page::where('status', 'published')->count())
                ->description('Published')
                ->color('gray'),
        ];
    }
}
