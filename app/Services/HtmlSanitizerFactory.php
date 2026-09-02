<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use DOMElement;
use Illuminate\Support\Facades\Config;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;

/**
 * The single sanitizer, built from `config/sanitizer.php` (BUILD-PLAN §7).
 *
 * Sanitization runs on SAVE via App\Models\Concerns\SanitizesHtml, so the database
 * never holds hostile markup and the render path stays a cheap `{!! !!}`.
 */
class HtmlSanitizerFactory
{
    private ?HtmlSanitizer $sanitizer = null;

    public function make(): HtmlSanitizer
    {
        return $this->sanitizer ??= new HtmlSanitizer($this->config());
    }

    public function sanitize(?string $html): ?string
    {
        if ($html === null || trim($html) === '') {
            return $html;
        }

        return $this->forceExternalLinkRel($this->make()->sanitize($html));
    }

    private function config(): HtmlSanitizerConfig
    {
        $options = Config::array('sanitizer');

        $config = (new HtmlSanitizerConfig)
            ->allowLinkSchemes(Config::array('sanitizer.allowed_link_schemes'))
            ->allowMediaSchemes(Config::array('sanitizer.allowed_media_schemes'))
            ->allowRelativeLinks(Config::boolean('sanitizer.allow_relative_links'))
            ->allowRelativeMedias(Config::boolean('sanitizer.allow_relative_media'))
            ->withMaxInputLength(Config::integer('sanitizer.max_input_length'));

        /** @var array<string, array<int, string>> $elements */
        $elements = $options['elements'];

        foreach ($elements as $element => $attributes) {
            $config = $config->allowElement($element, $attributes);
        }

        /** @var array<int, string> $dropped */
        $dropped = $options['dropped_elements'];

        foreach ($dropped as $element) {
            $config = $config->dropElement($element);
        }

        return $config;
    }

    /**
     * Add `rel="nofollow noopener"` to links pointing at another host (BUILD-PLAN §7).
     * Internal and relative links keep their rel so internal linking is not devalued.
     */
    private function forceExternalLinkRel(string $html): string
    {
        if (! str_contains($html, '<a')) {
            return $html;
        }

        $rel = Config::string('sanitizer.external_link_rel');
        $appHost = parse_url((string) Config::get('app.url'), PHP_URL_HOST);

        $document = new DOMDocument;
        $restore = libxml_use_internal_errors(true);

        // The meta tag pins UTF-8; the wrapper div is the fragment boundary. `div` is not
        // in the allow-list, so sanitized content can never contain a competing one.
        $loaded = $document->loadHTML(
            '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">'
            .'<div id="rich-content-fragment">'.$html.'</div>',
            LIBXML_HTML_NODEFDTD | LIBXML_NOERROR | LIBXML_NOWARNING,
        );

        libxml_clear_errors();
        libxml_use_internal_errors($restore);

        if ($loaded === false) {
            return $html;
        }

        $changed = false;

        /** @var DOMElement $anchor */
        foreach ($document->getElementsByTagName('a') as $anchor) {
            $host = parse_url($anchor->getAttribute('href'), PHP_URL_HOST);

            if ($host === null || $host === false || $host === $appHost) {
                continue;
            }

            if ($anchor->getAttribute('rel') === $rel) {
                continue;
            }

            $anchor->setAttribute('rel', $rel);
            $changed = true;
        }

        if (! $changed) {
            return $html;
        }

        $wrapper = $document->getElementById('rich-content-fragment')
            ?? $document->getElementsByTagName('div')->item(0);

        if (! $wrapper instanceof DOMElement) {
            return $html;
        }

        $output = '';

        foreach ($wrapper->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return $output;
    }
}
