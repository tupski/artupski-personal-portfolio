<?php

declare(strict_types=1);

/**
 * Allow-list for editor HTML, applied on save by App\Models\Concerns\SanitizesHtml
 * (BUILD-PLAN §7). `iframe` is deliberately absent — embeds go through a content block.
 *
 * This is a security allow-list, not an editorial policy: the editor's own toolbar
 * decides which of these tags an author can actually produce.
 */
return [

    /*
     * Elements kept, mapped to the attributes kept on them.
     * Anything not listed here is dropped (text content is preserved).
     */
    'elements' => [
        'h1' => [],
        'h2' => [],
        'h3' => [],
        'h4' => [],
        'h5' => [],
        'h6' => [],
        'p' => [],
        'strong' => [],
        'em' => [],
        'u' => [],
        's' => [],
        'a' => ['href', 'title', 'rel', 'target'],
        'blockquote' => ['cite'],
        'ul' => [],
        'ol' => ['start'],
        'li' => [],
        'code' => [],
        'pre' => [],
        'table' => [],
        'thead' => [],
        'tbody' => [],
        'tr' => [],
        'th' => ['colspan', 'rowspan', 'scope'],
        'td' => ['colspan', 'rowspan'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'],
        'figure' => [],
        'figcaption' => [],
        'hr' => [],
        'br' => [],
    ],

    /*
     * Elements dropped together with their content, regardless of the list above.
     */
    'dropped_elements' => ['script', 'style', 'iframe', 'object', 'embed', 'form', 'svg'],

    'allowed_link_schemes' => ['http', 'https', 'mailto'],

    'allowed_media_schemes' => ['http', 'https'],

    /*
     * Internal links and media are stored as root-relative paths, so relative
     * URLs must survive sanitization.
     */
    'allow_relative_links' => true,

    'allow_relative_media' => true,

    /*
     * -1 disables Symfony's input truncation; long-form articles exceed the
     * component's 20k default and must not be silently cut.
     */
    'max_input_length' => -1,

    /*
     * rel value forced on links pointing at another host (BUILD-PLAN §7).
     */
    'external_link_rel' => 'nofollow noopener',

];
