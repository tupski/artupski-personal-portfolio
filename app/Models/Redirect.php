<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * A stored redirect, consumed by the HandleRedirects middleware (BUILD-PLAN §4, spec §29).
 * `to_path` must be validated as an internal path or an explicit absolute URL by the
 * request layer — no open redirects (BUILD-PLAN §7).
 */
class Redirect extends Model
{
    /**
     * `hits` is incremented server-side, not filled.
     *
     * @var list<string>
     */
    protected $fillable = [
        'from_path',
        'to_path',
        'status_code',
        'is_active',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status_code' => 'integer',
            'is_active' => 'boolean',
            'hits' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
