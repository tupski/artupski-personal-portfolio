<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Models\Concerns\HasContentMedia;
use App\Models\Concerns\HasPublishing;
use App\Models\Concerns\HasSeo;
use App\Models\Concerns\HasSlug;
use App\Models\Concerns\SanitizesHtml;
use App\Services\ReadingTime;
use Database\Factories\PostFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;

class Post extends Model implements HasMedia
{
    /** @use HasFactory<PostFactory> */
    use HasContentMedia, HasFactory, HasPublishing, HasSeo, HasSlug, SanitizesHtml;

    /**
     * Eager-load set for post listings (BUILD-PLAN §4 §82). Nothing wider.
     *
     * @var array<int, string>
     */
    public const LISTING_RELATIONS = ['category', 'media'];

    /**
     * Eager-load set for the post detail page (BUILD-PLAN §4 §82).
     *
     * @var array<int, string>
     */
    public const DETAIL_RELATIONS = ['category', 'author', 'tags', 'project', 'media'];

    /**
     * `reading_time` is absent on purpose: it is computed, never author-supplied (§46).
     *
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'project_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'content_blocks',
        'is_featured',
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
            'status' => ContentStatus::class,
            'published_at' => 'datetime',
            'reading_time' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Recompute reading time whenever the content changes (§46).
     */
    protected static function booted(): void
    {
        static::saving(function (self $post): void {
            if ($post->isDirty('content') || $post->reading_time === null) {
                $post->reading_time = app(ReadingTime::class)->forHtml($post->content);
            }
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Optional "I built X" link back to a project (spec §93).
     *
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsToMany<Tag, $this>
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
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
}
