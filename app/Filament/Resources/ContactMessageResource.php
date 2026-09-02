<?php

namespace App\Filament\Resources;

use App\Enums\ContactMessageStatus;
use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|\UnitEnum|null $navigationGroup = 'Communication';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Message';

    protected static ?string $pluralModelLabel = 'Messages';

    public static function form(Schema $schema): Schema
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Message Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Name')
                            ->disabled(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->disabled(),

                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->disabled(),

                        Forms\Components\Textarea::make('message')
                            ->label('Message')
                            ->disabled()
                            ->rows(5),

                        Forms\Components\TextInput::make('company')
                            ->label('Company')
                            ->disabled(),

                        Forms\Components\TextInput::make('budget')
                            ->label('Budget')
                            ->disabled(),

                        Forms\Components\TextInput::make('project_type')
                            ->label('Project Type')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options(fn () => collect(ContactMessageStatus::cases())
                                ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                                ->toArray())
                            ->selectablePlaceholder(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => match ($record->status) {
                        ContactMessageStatus::Unread => 'heroicon-o-envelope',
                        ContactMessageStatus::Read => 'heroicon-o-document-check',
                        ContactMessageStatus::Replied => 'heroicon-o-check-circle',
                        ContactMessageStatus::Archived => 'heroicon-o-archive-box',
                    })
                    ->color(fn ($record) => match ($record->status) {
                        ContactMessageStatus::Unread => 'info',
                        ContactMessageStatus::Read => 'gray',
                        ContactMessageStatus::Replied => 'success',
                        ContactMessageStatus::Archived => 'danger',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(ContactMessageStatus::cases())
                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('markRead')
                    ->label('Mark Read')
                    ->icon('heroicon-o-document-check')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => ContactMessageStatus::Read,
                        'read_at' => now(),
                    ]))
                    ->visible(fn ($record) => $record->status === ContactMessageStatus::Unread),

                Tables\Actions\Action::make('markReplied')
                    ->label('Mark Replied')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => ContactMessageStatus::Replied,
                        'replied_at' => now(),
                    ]))
                    ->visible(fn ($record) => in_array($record->status, [
                        ContactMessageStatus::Unread,
                        ContactMessageStatus::Read,
                    ])),

                Tables\Actions\Action::make('archive')
                    ->label('Archive')
                    ->icon('heroicon-o-archive-box')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => ContactMessageStatus::Archived,
                    ]))
                    ->visible(fn ($record) => $record->status !== ContactMessageStatus::Archived),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Contact Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')->label('Name'),
                        Infolists\Components\TextEntry::make('email')->label('Email'),
                        Infolists\Components\TextEntry::make('subject')->label('Subject'),
                        Infolists\Components\TextEntry::make('message')->label('Message'),
                        Infolists\Components\TextEntry::make('company')->label('Company'),
                        Infolists\Components\TextEntry::make('budget')->label('Budget'),
                        Infolists\Components\TextEntry::make('project_type')->label('Project Type'),
                        Infolists\Components\TextEntry::make('status')->label('Status'),
                        Infolists\Components\TextEntry::make('created_at')->label('Received At')->dateTime(),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'view' => Pages\ViewContactMessage::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
