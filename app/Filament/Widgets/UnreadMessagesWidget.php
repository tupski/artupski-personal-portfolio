<?php

namespace App\Filament\Widgets;

use App\Enums\ContactMessageStatus;
use App\Models\ContactMessage;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UnreadMessagesWidget extends TableWidget
{
    protected static ?string $heading = 'Unread Messages';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                ContactMessage::where('status', ContactMessageStatus::Unread)
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->limit(50),

                Tables\Columns\TextColumn::make('message')
                    ->limit(80),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, g:i A')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
