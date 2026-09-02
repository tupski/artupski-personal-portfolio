<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * The shared media collections and conversion set for Page, Project and Post
 * (BUILD-PLAN §4). Five conversion names max, gd-compatible only.
 *
 * `InteractsWithMedia` is composed here rather than in each model: nesting makes this
 * trait's `registerMedia*` methods win over the package defaults instead of colliding
 * with them. A model that needs different collections overrides the methods directly.
 *
 * DEVIATION: no AVIF conversion — Task 1 confirmed `imagick` is absent and conversions
 * run on `gd`, so BUILD-PLAN §1's "drop AVIF when only gd exists" applies.
 */
trait HasContentMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')->singleFile();
        $this->addMediaCollection('og')->singleFile();
    }

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
            ->queued();
    }
}
