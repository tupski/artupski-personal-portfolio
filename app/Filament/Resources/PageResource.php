<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Resources\PageResource\Pages;
use App\Filament\RichContent\Blocks\CalloutBlock;
use App\Filament\RichContent\Blocks\CodeBlock;
use App\Filament\RichContent\Blocks\CtaBlock;
use App\Filament\RichContent\Blocks\ImageBlock;
use App\Filament\RichContent\Blocks\ProjectHighlightBlock;
use App\Models\Page;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)->schema([
                    Forms\Components\Section::make('Content')
                        ->icon('heroicon-o-document-text')
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),

                            Forms\Components\TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(Page::class, 'slug', ignoreRecord: true),

                            Forms\Components\Textarea::make('excerpt')
                                ->label('Excerpt')
                                ->maxLength(500)
                                ->rows(3),

                            Forms\Components\RichEditor::make('content')
                                ->label('Content')
                                ->customBlocks([
                                    ImageBlock::class,
                                    CalloutBlock::class,
                                    CtaBlock::class,
                                    CodeBlock::class,
                                    ProjectHighlightBlock::class,
                                ])
                                ->toolbarButtons([
                                    'bold', 'italic', 'underline', 'strike', 'link',
                                    'h2', 'h3',
                                    'alignStart', 'alignCenter', 'alignEnd',
                                    'blockquote', 'codeBlock', 'bulletList', 'orderedList',
                                    'table', 'attachFiles', 'customBlocks',
                                    'undo', 'redo',
                                ])
                                ->fileAttachmentsDisk('public')
                                ->columnSpanFull(),
                        ])->columnSpan(1),

                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\Section::make('Publishing')
                            ->icon('heroicon-o-arrow-up-tray')
                            ->schema([
                                Forms\Components\Select::make('status')
                                    ->label('Status')
                                    ->options(fn () => collect(ContentStatus::cases())
                                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                                        ->toArray())
                                    ->default(ContentStatus::Draft)
                                    ->selectablePlaceholder(false),

                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->visible(fn ($get) => in_array($get('status'), [
                                        ContentStatus::Scheduled->value,
                                        ContentStatus::Published->value,
                                    ])),

                                Forms\Components\Toggle::make('is_navigable')
                                    ->label('Visible in Navigation')
                                    ->default(true),
                            ]),

                        Forms\Components\Section::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\TextInput::make('seo_title')
                                    ->label('SEO Title')
                                    ->maxLength(255),

                                Forms\Components\Textarea::make('seo_description')
                                    ->label('SEO Description')
                                    ->maxLength(500)
                                    ->rows(2),

                                Forms\Components\TextInput::make('canonical_url')
                                    ->label('Canonical URL')
                                    ->maxLength(255),

                                Forms\Components\View::make('filament.forms.seo-preview')
                                    ->label('Preview')
                                    ->viewData(['type' => 'page']),
                            ]),

                        Forms\Components\Section::make('Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Featured Image')
                                    ->disk('public')
                                    ->directory('uploads/pages/featured')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(4096)
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => match ($record->status) {
                        ContentStatus::Draft => 'heroicon-o-pencil',
                        ContentStatus::Scheduled => 'heroicon-o-clock',
                        ContentStatus::Published => 'heroicon-o-check-circle',
                        ContentStatus::Archived => 'heroicon-o-archive-box',
                    })
                    ->color(fn ($record) => match ($record->status) {
                        ContentStatus::Draft => 'gray',
                        ContentStatus::Scheduled => 'warning',
                        ContentStatus::Published => 'success',
                        ContentStatus::Archived => 'danger',
                    }),

                Tables\Columns\IconColumn::make('is_navigable')
                    ->label('Nav')
                    ->boolean(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M j, Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(ContentStatus::cases())
                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'view' => Pages\ViewPage::route('/{record}'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create', Page::class);
    }
}
