<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Filament\Resources\PostResource\Pages;
use App\Filament\RichContent\Blocks\CalloutBlock;
use App\Filament\RichContent\Blocks\CodeBlock;
use App\Filament\RichContent\Blocks\CtaBlock;
use App\Filament\RichContent\Blocks\ImageBlock;
use App\Filament\RichContent\Blocks\ProjectHighlightBlock;
use App\Models\Post;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Grid::make()->schema([
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
                                    ->unique(Post::class, 'slug', ignoreRecord: true),

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
                            ]),

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

                                    Forms\Components\Toggle::make('is_featured')
                                        ->label('Featured')
                                        ->default(false),
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
                                        ->viewData(['type' => 'post']),
                                ]),

                            Forms\Components\Section::make('Media')
                                ->icon('heroicon-o-photo')
                                ->schema([
                                    Forms\Components\FileUpload::make('featured_image')
                                        ->label('Featured Image')
                                        ->disk('public')
                                        ->directory('uploads/posts/featured')
                                        ->image()
                                        ->imageEditor()
                                        ->maxSize(4096)
                                        ->columnSpanFull(),
                                ]),

                            Forms\Components\Section::make('Organization')
                                ->icon('heroicon-o-folder')
                                ->schema([
                                    Forms\Components\Select::make('category_id')
                                        ->label('Category')
                                        ->relationship('category', 'name')
                                        ->searchable()
                                        ->preload(),

                                    Forms\Components\Select::make('tags')
                                        ->label('Tags')
                                        ->relationship('tags', 'name')
                                        ->multiple()
                                        ->searchable()
                                        ->preload(),

                                    Forms\Components\Select::make('project_id')
                                        ->label('Related Project')
                                        ->relationship('project', 'title')
                                        ->searchable()
                                        ->preload()
                                        ->nullable(),
                                ]),
                        ])->columnSpan(1),
                    ])->columnSpan(2),
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

                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),

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

                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),

                Tables\Columns\TextColumn::make('reading_time')
                    ->label('Read')
                    ->suffix(' min')
                    ->sortable(),

                Tables\Columns\TextColumn::make('author.name')
                    ->sortable(),

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

                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category'),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),

                Tables\Filters\Filter::make('scheduled')
                    ->query(fn (Builder $query) => $query
                        ->where('status', ContentStatus::Scheduled)
                        ->where('published_at', '>', now()))
                    ->label('Scheduled'),
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

    public static function infolist(Schema $schema): Schema
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Content')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')->label('Title'),
                        Infolists\Components\TextEntry::make('slug')->label('Slug'),
                        Infolists\Components\TextEntry::make('excerpt')->label('Excerpt'),
                        Infolists\Components\TextEntry::make('content')->label('Content')->html(),
                    ]),

                Infolists\Components\Section::make('Publishing')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')->label('Status'),
                        Infolists\Components\TextEntry::make('published_at')->label('Published At')->dateTime(),
                        Infolists\Components\TextEntry::make('is_featured')->label('Featured')->boolean(),
                        Infolists\Components\TextEntry::make('reading_time')->label('Reading Time')->suffix(' min'),
                    ]),

                Infolists\Components\Section::make('SEO')
                    ->schema([
                        Infolists\Components\TextEntry::make('seo_title')->label('SEO Title'),
                        Infolists\Components\TextEntry::make('seo_description')->label('SEO Description'),
                        Infolists\Components\TextEntry::make('canonical_url')->label('Canonical URL'),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create', Post::class);
    }
}
