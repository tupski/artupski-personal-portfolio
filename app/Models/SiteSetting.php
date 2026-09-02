<?php

namespace App\Models;

use App\Enums\SettingType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * A single key/value setting row (BUILD-PLAN §4, spec §21).
 *
 * Key/value rather than typed columns by decision: the six setting groups keep
 * growing, and typed columns would mean a migration per new setting. The `type`
 * column carries the cast; SettingsRepository (Task 5+) does the caching.
 *
 * Media-backed settings (logo, favicon, profile_photo, default_og) attach media to
 * the row itself and store the medialibrary UUID in `value` with `type=media`.
 */
class SiteSetting extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => SettingType::class,
        ];
    }

    /**
     * The stored string decoded according to `type`.
     */
    public function typedValue(): string|bool|array|null
    {
        return match ($this->type) {
            SettingType::Boolean => filter_var($this->value, FILTER_VALIDATE_BOOLEAN),
            SettingType::Json => json_decode((string) $this->value, true) ?: [],
            default => $this->value,
        };
    }

    public function scopeGroup(Builder $query, string $group): Builder
    {
        return $query->where('group', $group);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')->singleFile();
        $this->addMediaCollection('favicon')->singleFile();
        $this->addMediaCollection('profile_photo')->singleFile();
        $this->addMediaCollection('default_og')->singleFile();
    }

    /**
     * Favicons get no conversion (BUILD-PLAN §4).
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Max, 400, 400)
            ->format('webp')
            ->performOnCollections('logo', 'profile_photo', 'default_og')
            ->queued();

        $this->addMediaConversion('medium')
            ->fit(Fit::Max, 800, 800)
            ->format('webp')
            ->performOnCollections('logo', 'profile_photo', 'default_og')
            ->queued();
    }
}
