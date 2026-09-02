<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Reading time at 250 words per minute (BUILD-PLAN §3, spec §46).
 * Authors never type this value; it is recomputed whenever content changes.
 */
class ReadingTime
{
    public const WORDS_PER_MINUTE = 250;

    /**
     * Minutes, minimum 1 for any non-empty content, null when there is nothing to read.
     */
    public function forHtml(?string $html): ?int
    {
        if ($html === null) {
            return null;
        }

        $text = trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags($html))) ?? '');

        if ($text === '') {
            return null;
        }

        $words = count(preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: []);

        if ($words === 0) {
            return null;
        }

        return max(1, (int) ceil($words / self::WORDS_PER_MINUTE));
    }
}
