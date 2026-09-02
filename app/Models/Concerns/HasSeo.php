<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * SEO fallback chain for models carrying seo_* columns (BUILD-PLAN §3, spec §67).
 * The Blade layer never re-implements these fallbacks.
 */
trait HasSeo
{
    public function seoTitle(): string
    {
        return (string) ($this->seo_title ?: $this->title);
    }

    public function seoDescription(): string
    {
        if (filled($this->seo_description)) {
            return (string) $this->seo_description;
        }

        if (filled($this->excerpt ?? null)) {
            return (string) $this->excerpt;
        }

        if (filled($this->short_description ?? null)) {
            return (string) $this->short_description;
        }

        return Str::limit(trim(strip_tags((string) $this->content)), 155);
    }

    /**
     * Explicit canonical only; the current URL default belongs to the SEO component.
     */
    public function canonicalUrl(): ?string
    {
        return filled($this->canonical_url) ? (string) $this->canonical_url : null;
    }
}
