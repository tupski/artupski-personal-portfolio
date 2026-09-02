<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;
use App\Models\Concerns\HasContentMedia;
use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\SanitizesHtml;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Project extends Model implements HasMedia
{
    /** @use HasFactory<ProjectFactory> */
    use HasContentMedia, HasFactory, HasPublishing, HasSeo, HasSlug, SanitizesHtml;

    /**
     * Eager-load set for project listings (BUILD-PLAN §4 §82). Nothing wider.
     *
     * @var array<int, string>
     */
    public const LISTING_RELATIONS = ['technologies', 'media'];

    /**
     * Eager-load set for the project detail page (BUILD-PLAN §4 §82).
     *
     * @var array<int, string>
     */
    public const DETAIL_RELATIONS = ['technologies', 'media', 'posts'];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'content',
        'content_blocks',
        'challenges',
        'solutions',
        'results',
        'project_type',
        'client',
        'role',
        'started_on',
        'ended_on',
        'project_status',
        'live_url',
        'repository_url',
        'is_featured',
        'sort_order',
        'seo_title',
        'seo_description',
        'canonical_url',
        'og_image_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content_blocks' => 'array',
            'project_type' => ProjectType::class,
            'project_status' => ProjectStatus::class,
            'status' => ContentStatus::class,
            'started_on' => 'date',
            'ended_on' => 'date',
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @return BelongsToMany<Technology, $this>
     */
    public function technologies(): BelongsToMany
    {
        return $this->belongsToMany(Technology::class)->orderBy('technologies.sort_order');
    }

    /**
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Adds the project-only `gallery` collection on top of the shared set.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('og')->singleFile();
        $this->addMediaCollection('gallery');
    }

    /**
     * Same five conversion names as the shared set; `og` is skipped for gallery
     * images since they are never used as social cards.
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Max, 400, 400)
            ->format('webp')
            ->queued();

        $this->addMediaConversion('medium')
            ->fit(Fit::Max, 800, 800)
            ->format('webp')
            ->queued();

        $this->addMediaConversion('large')
            ->fit(Fit::Max, 1600, 1600)
            ->format('webp')
            ->queued();

        $this->addMediaConversion('og')
            ->fit(Fit::Crop, 1200, 630)
            ->format('jpg')
            ->performOnCollections('featured', 'og')
            ->queued();
    }

    public function isOngoing(): bool
    {
        return $this->ended_on === null;
    }
}
