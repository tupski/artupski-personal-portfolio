<?php

namespace App\Filament\Pages;

use App\Enums\SettingType;
use App\Models\SiteSetting;
use App\Services\GitInfo;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;

class ManageSiteSettings extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string|\UnitEnum|null $navigationGroup = 'System';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.manage-site-settings';

    protected static ?string $title = 'Site Settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'settings' => $this->loadSettings(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\Tabs::make('settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Identity')
                            ->icon('heroicon-o-building-office')
                            ->schema([
                                $this->settingField('identity', 'site_name', 'String'),
                                $this->settingField('identity', 'site_title', 'String'),
                                $this->settingField('identity', 'site_description', 'Text'),
                                $this->mediaSettingField('identity', 'logo', 'Logo'),
                                $this->mediaSettingField('identity', 'favicon', 'Favicon'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Personal')
                            ->icon('heroicon-o-user')
                            ->schema([
                                $this->settingField('personal', 'name', 'String'),
                                $this->settingField('personal', 'headline', 'String'),
                                $this->settingField('personal', 'bio', 'Text'),
                                $this->mediaSettingField('personal', 'profile_photo', 'Profile Photo'),
                                $this->settingField('personal', 'location', 'String'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Contact')
                            ->icon('heroicon-o-envelope')
                            ->schema([
                                $this->settingField('contact', 'email', 'String'),
                                $this->settingField('contact', 'phone', 'String'),
                                $this->settingField('contact', 'contact_url', 'String'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Social')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                $this->settingField('social', 'github', 'String'),
                                $this->settingField('social', 'linkedin', 'String'),
                                $this->settingField('social', 'instagram', 'String'),
                                $this->settingField('social', 'x', 'String'),
                                $this->settingField('social', 'youtube', 'String'),
                                $this->settingField('social', 'threads', 'String'),
                            ]),

                        Forms\Components\Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                $this->settingField('seo', 'default_title', 'String'),
                                $this->settingField('seo', 'default_description', 'Text'),
                                $this->mediaSettingField('seo', 'default_og_image', 'Default OG Image'),
                                $this->settingField('seo', 'robots_enabled', 'Boolean'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Analytics')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                $this->settingField('analytics', 'provider', 'String'),
                                $this->settingField('analytics', 'google_analytics_id', 'String'),
                                $this->settingField('analytics', 'plausible_domain', 'String'),
                                $this->settingField('analytics', 'umami_website_id', 'String'),
                                $this->settingField('analytics', 'umami_script_url', 'String'),
                                $this->settingField('analytics', 'custom_head_snippet', 'Text'),
                            ]),

                        Forms\Components\Tabs\Tab::make('Git Info')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Forms\Components\Placeholder::make('git_commit_hash')
                                    ->label('Current Commit')
                                    ->content(self::getGitInfo('commit')),

                                Forms\Components\Placeholder::make('git_branch')
                                    ->label('Branch')
                                    ->content(self::getGitInfo('branch')),

                                Forms\Components\Placeholder::make('git_last_message')
                                    ->label('Last Commit Message')
                                    ->content(self::getGitInfo('message')),

                                Forms\Components\Placeholder::make('git_last_date')
                                    ->label('Last Commit Date')
                                    ->content(self::getGitInfo('date')),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    protected function settingField(string $group, string $key, string $type): Forms\Components\Field
    {
        $label = str_replace('_', ' ', ucfirst($key));

        return match ($type) {
            'String' => Forms\Components\TextInput::make("settings.{$group}.{$key}")
                ->label($label)
                ->maxLength(255),

            'Text' => Forms\Components\Textarea::make("settings.{$group}.{$key}")
                ->label($label)
                ->rows(4),

            'Boolean' => Forms\Components\Toggle::make("settings.{$group}.{$key}")
                ->label($label)
                ->default(false),

            default => Forms\Components\TextInput::make("settings.{$group}.{$key}")
                ->label($label),
        };
    }

    protected function mediaSettingField(string $group, string $key, string $label): Forms\Components\FileUpload
    {
        return Forms\Components\FileUpload::make("settings.{$group}.{$key}")
            ->label($label)
            ->disk('public')
            ->directory('uploads/settings')
            ->image()
            ->imageEditor()
            ->maxSize(512);
    }

    protected function loadSettings(): array
    {
        $settings = [];
        $allSettings = SiteSetting::all();

        foreach ($allSettings as $setting) {
            $value = $setting->typedValue();
            $settings[$setting->group][$setting->key] = match ($setting->type) {
                SettingType::Media => $setting->value,
                default => $value,
            };
        }

        return $settings;
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $settings = $data['settings'] ?? [];

        foreach ($settings as $group => $groupSettings) {
            if (! is_array($groupSettings)) {
                continue;
            }

            foreach ($groupSettings as $key => $value) {
                $setting = SiteSetting::where('group', $group)->where('key', $key)->first();

                if (! $setting) {
                    continue;
                }

                $setting->update(['value' => $value]);
            }
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected static function getGitInfo(string $type): string
    {
        return match ($type) {
            'commit' => trim(GitInfo::getCommitHash()),
            'branch' => trim(GitInfo::getBranch()),
            'message' => trim(GitInfo::getLastCommitMessage()),
            'date' => trim(GitInfo::getLastCommitDate()),
            default => '',
        };
    }
}
