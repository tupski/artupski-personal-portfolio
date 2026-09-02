<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Builder;

/**
 * Publishing rules shared by pages, projects and posts (BUILD-PLAN §4, spec §45/§86).
 *
 * `published()` is a LOCAL scope on purpose: a global scope would also hide drafts
 * from Filament and from the signed preview route.
 */
trait HasPublishing
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function isPublished(): bool
    {
        return $this->status === ContentStatus::Published
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }

    /**
     * Rows the scheduler is due to flip to `published` (spec §80).
     */
    public function scopeDueForPublishing(Builder $query): Builder
    {
        return $query->where('status', 'scheduled')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }
}
