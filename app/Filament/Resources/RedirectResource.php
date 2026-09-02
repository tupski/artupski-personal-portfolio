<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RedirectResource\Pages;
use App\Models\Redirect;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-right-end-on-rectangle';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Redirect Details')
                    ->schema([
                        Forms\Components\TextInput::make('from_path')
                            ->label('From Path')
                            ->required()
                            ->maxLength(2048),

                        Forms\Components\TextInput::make('to_path')
                            ->label('To Path')
                            ->required()
                            ->maxLength(2048),

                        Forms\Components\Select::make('status_code')
                            ->label('Status Code')
                            ->options([
                                301 => '301 - Permanent',
                                302 => '302 - Temporary',
                            ])
                            ->default(301)
                            ->required()
                            ->selectablePlaceholder(false),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('from_path')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('to_path')
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_code')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('hits')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListRedirects::route('/'),
            'create' => Pages\CreateRedirect::route('/create'),
            'edit' => Pages\EditRedirect::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create', Redirect::class);
    }
}
