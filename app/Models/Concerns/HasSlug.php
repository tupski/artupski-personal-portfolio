<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Support\Str;

/**
 * Slug generation from a source column with manual override and collision handling
 * (BUILD-PLAN §4, spec §27/§28). One implementation, shared by every slugged model.
 */
trait HasSlug
{
    public static function bootHasSlug(): void
    {
        static::saving(function (self $model): void {
            $model->applySlug();
        });
    }

    /**
     * Column the slug is generated from. Override in models keyed on `name`.
     */
    public function slugSourceColumn(): string
    {
        return 'title';
    }

    public function slugColumn(): string
    {
        return 'slug';
    }

    /**
     * Fill or normalise the slug. A slug typed by the author wins over the
     * generated one; it is still normalised and made unique.
     */
    public function applySlug(): void
    {
        $column = $this->slugColumn();
        $current = $this->{$column};

        // Existing, untouched slug: leave permanent URLs alone.
        if (filled($current) && ! $this->isDirty($column)) {
            return;
        }

        $base = Str::slug((string) (filled($current) ? $current : $this->{$this->slugSourceColumn()}));

        if ($base === '') {
            $base = Str::lower(class_basename($this));
        }

        // Leave room for a numeric suffix inside varchar(255).
        $base = Str::limit($base, 240, '');

        $this->{$column} = $this->uniqueSlug($base);
    }

    protected function uniqueSlug(string $base): string
    {
        $slug = $base;
        $suffix = 2;

        while ($this->slugExists($slug)) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    protected function slugExists(string $slug): bool
    {
        $query = static::query()->where($this->slugColumn(), $slug);

        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        return $query->exists();
    }
}
