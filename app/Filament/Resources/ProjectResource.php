<?php

namespace App\Filament\Resources;

use App\Enums\ContentStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Filament\Resources\ProjectResource\Pages;
use App\Filament\RichContent\Blocks\CalloutBlock;
use App\Filament\RichContent\Blocks\CodeBlock;
use App\Filament\RichContent\Blocks\CtaBlock;
use App\Filament\RichContent\Blocks\ImageBlock;
use App\Filament\RichContent\Blocks\ProjectHighlightBlock;
use App\Models\Project;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-briefcase';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

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
                                ->unique(Project::class, 'slug', ignoreRecord: true),

                            Forms\Components\Textarea::make('short_description')
                                ->label('Short Description')
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

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->default(false),

                                Forms\Components\TextInput::make('sort_order')
                                    ->label('Sort Order')
                                    ->numeric()
                                    ->default(0),
                            ]),

                        Forms\Components\Section::make('Project Details')
                            ->icon('heroicon-o-cog')
                            ->schema([
                                Forms\Components\Select::make('project_type')
                                    ->label('Type')
                                    ->options(fn () => collect(ProjectType::cases())
                                        ->mapWithKeys(fn ($t) => [$t->value => $t->label()])
                                        ->toArray())
                                    ->default(ProjectType::Website)
                                    ->selectablePlaceholder(false),

                                Forms\Components\Select::make('project_status')
                                    ->label('Project Status')
                                    ->options(fn () => collect(ProjectStatus::cases())
                                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                                        ->toArray())
                                    ->default(ProjectStatus::Completed)
                                    ->selectablePlaceholder(false),

                                Forms\Components\TextInput::make('client')
                                    ->label('Client')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('role')
                                    ->label('Your Role')
                                    ->maxLength(255),

                                Forms\Components\DatePicker::make('started_on')
                                    ->label('Started On'),

                                Forms\Components\DatePicker::make('ended_on')
                                    ->label('Ended On'),

                                Forms\Components\TextInput::make('live_url')
                                    ->label('Live URL')
                                    ->maxLength(255)
                                    ->url(),

                                Forms\Components\TextInput::make('repository_url')
                                    ->label('Repository URL')
                                    ->maxLength(255)
                                    ->url(),
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
                                    ->viewData(['type' => 'project']),
                            ]),

                        Forms\Components\Section::make('Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->label('Featured Image')
                                    ->disk('public')
                                    ->directory('uploads/projects/featured')
                                    ->image()
                                    ->imageEditor()
                                    ->maxSize(4096)
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Technologies')
                            ->icon('heroicon-o-wrench')
                            ->schema([
                                Forms\Components\Select::make('technologies')
                                    ->label('Technologies')
                                    ->relationship('technologies', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload(),
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

                Tables\Columns\TextColumn::make('project_type')
                    ->label('Type')
                    ->badge(),

                Tables\Columns\TextColumn::make('project_status')
                    ->label('Status')
                    ->badge(),

                Tables\Columns\IconColumn::make('status')
                    ->label('Published')
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

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M j, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(fn () => collect(ContentStatus::cases())
                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('project_type')
                    ->options(fn () => collect(ProjectType::cases())
                        ->mapWithKeys(fn ($t) => [$t->value => $t->label()])
                        ->toArray()),

                Tables\Filters\SelectFilter::make('project_status')
                    ->options(fn () => collect(ProjectStatus::cases())
                        ->mapWithKeys(fn ($s) => [$s->value => $s->label()])
                        ->toArray()),

                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured'),
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
            ->defaultSort('sort_order', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'view' => Pages\ViewProject::route('/{record}'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create', Project::class);
    }
}
