<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Services\HtmlSanitizerFactory;

/**
 * Sanitizes rich HTML columns on save, before the value is persisted (BUILD-PLAN §7).
 *
 * Consequence honoured elsewhere: because storage is already clean, the render path
 * is a single `{!! !!}` inside resources/views/components/content/rich-content.blade.php.
 */
trait SanitizesHtml
{
    public static function bootSanitizesHtml(): void
    {
        static::saving(function (self $model): void {
            $sanitizer = app(HtmlSanitizerFactory::class);

            foreach ($model->sanitizedHtmlColumns() as $column) {
                if (! $model->isDirty($column)) {
                    continue;
                }

                $model->{$column} = $sanitizer->sanitize($model->{$column});
            }
        });
    }

    /**
     * Columns holding editor HTML. Plain-text columns must never be listed here.
     *
     * @return array<int, string>
     */
    public function sanitizedHtmlColumns(): array
    {
        return ['content'];
    }
}
