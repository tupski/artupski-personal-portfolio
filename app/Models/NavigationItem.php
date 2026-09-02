<?php

namespace App\Models;

use App\Enums\NavigationLocation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Header/footer navigation item (BUILD-PLAN §4, spec §22).
 */
class NavigationItem extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'url',
        'location',
        'target_blank',
        'is_visible',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'location' => NavigationLocation::class,
            'target_blank' => 'boolean',
            'is_visible' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_visible', true);
    }

    public function scopeLocation(Builder $query, NavigationLocation $location): Builder
    {
        return $query->where('location', $location->value);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
