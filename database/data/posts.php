<?php

use App\Enums\ContentStatus;

/**
 * Seed posts covering every publishing state (spec §106, §45). Category and tags are
 * referenced by name and resolved to seeded rows by PostSeeder.
 *
 * @return array<int, array<string, mixed>>
 */
return [
    [
        'title' => 'Why I still server-render everything',
        'slug' => 'why-i-still-server-render-everything',
        'excerpt' => 'An SPA was never the constraint. Latency, cache headers and HTML were.',
        'category' => 'Web Development',
        'tags' => ['Laravel', 'Turbo', 'Hotwire'],
        'project' => 'bali-malayali-tourism-portal',
        'status' => ContentStatus::Published->value,
        'is_featured' => true,
        'published_days_ago' => 42,
        'content' => <<<'HTML'
            <p>Every few years the default answer changes. The question does not: how quickly can a person see useful content, and how little can I ship to make that happen?</p>
            <h2>What the SPA actually bought</h2>
            <p>Client-side routing removed a round trip and added a build step, a hydration cost, and a second source of truth for URLs. On a content site, that trade never paid off.</p>
            <h2>What replaced it</h2>
            <p>Server-rendered HTML, cached at the edge where it is safe to cache, with Turbo intercepting navigation so the browser keeps the page while the next one arrives.</p>
            <h3>The part people miss</h3>
            <p>Turbo is not a framework you build against. It is a progressive enhancement: turn the JavaScript off and every link still works, because every link is still a link.</p>
            <ul>
                <li>One routing table, on the server.</li>
                <li>No hydration mismatch, because there is no hydration.</li>
                <li>Cache headers that a CDN actually understands.</li>
            </ul>
            <h2>When I would still reach for a SPA</h2>
            <p>Editors, dashboards with live collaborative state, anything where the document model genuinely lives in the client. A portfolio is none of those.</p>
            HTML,
    ],
    [
        'title' => 'Scheduled publishing without a cron surprise',
        'slug' => 'scheduled-publishing-without-a-cron-surprise',
        'excerpt' => 'How a one-minute command and a strict status enum keep drafts invisible.',
        'category' => 'Laravel',
        'tags' => ['Laravel', 'PHP'],
        'project' => null,
        'status' => ContentStatus::Published->value,
        'is_featured' => true,
        'published_days_ago' => 21,
        'content' => <<<'HTML'
            <p>Scheduled publishing looks like a one-line query and turns into a leak the first time someone reloads at the wrong moment.</p>
            <h2>The rule</h2>
            <p>Public visibility requires three things at once: the status is <code>published</code>, a publish timestamp exists, and that timestamp is in the past.</p>
            <h2>Why status alone is not enough</h2>
            <p>A row marked <code>scheduled</code> with a past timestamp is still not public. Only the scheduler may flip it, so an operator can always see what will go out and when.</p>
            <h3>Where the check lives</h3>
            <p>In a local scope, not a global one. A global scope would also hide drafts from the admin panel and from the signed preview URL, which is exactly where drafts need to be visible.</p>
            HTML,
    ],
    [
        'title' => 'Sanitising editor HTML on save, not on render',
        'slug' => 'sanitising-editor-html-on-save-not-on-render',
        'excerpt' => 'Trading a one-off backfill for a permanently cheaper render path.',
        'category' => 'Development',
        'tags' => ['PHP', 'Laravel'],
        'project' => null,
        'status' => ContentStatus::Published->value,
        'is_featured' => false,
        'published_days_ago' => 9,
        'content' => <<<'HTML'
            <p>Both options are defensible. One of them is cheaper on every request.</p>
            <h2>Sanitise on render</h2>
            <p>Flexible: change the allow-list and every page updates. Also means the database holds hostile markup, and the render cache can no longer be trusted blindly.</p>
            <h2>Sanitise on save</h2>
            <p>The stored value is already safe, so rendering is one escape-free interpolation and the cached HTML is safe to store. The cost is a backfill command when the allow-list changes.</p>
            <h3>The consequence to honour</h3>
            <p>Exactly one template is allowed to emit raw HTML. Anything else touching user-editable data is a review failure, not a style preference.</p>
            HTML,
    ],
    [
        'title' => 'Media conversions when imagick is missing',
        'slug' => 'media-conversions-when-imagick-is-missing',
        'excerpt' => 'What changes when gd is the only image driver on the box.',
        'category' => 'Tools',
        'tags' => ['PHP', 'Automation'],
        'project' => 'invoice-automation-suite',
        'status' => ContentStatus::Published->value,
        'is_featured' => false,
        'published_days_ago' => 3,
        'content' => <<<'HTML'
            <p>The library supports both drivers. Your server probably only has one.</p>
            <h2>What still works</h2>
            <p>Resizing, cropping, WebP and JPEG output. For a portfolio, that is the entire requirement.</p>
            <h2>What to drop</h2>
            <p>AVIF. Encoding quality and speed differ enough that shipping it on gd is a false economy, so the conversion set stops at WebP.</p>
            <h3>Keep the conversion list short</h3>
            <p>Four or five named sizes covers thumbnails, article images and social cards. Generating a dozen just fills the disk.</p>
            HTML,
    ],
    [
        'title' => 'Designing a settings table you will not regret',
        'slug' => 'designing-a-settings-table-you-will-not-regret',
        'excerpt' => 'Key/value rows, a type column, and one cached repository.',
        'category' => 'Development',
        'tags' => ['Laravel', 'MySQL'],
        'project' => null,
        'status' => ContentStatus::Draft->value,
        'is_featured' => false,
        'published_days_ago' => null,
        'content' => <<<'HTML'
            <p>Typed columns are better right up until the day you add a setting.</p>
            <h2>The trade</h2>
            <p>Key/value rows lose compile-time typing and gain the ability to add a setting without a migration. A type column plus a single cached accessor buys most of the typing back.</p>
            HTML,
    ],
    [
        'title' => 'Slug changes and the 301 you owe your readers',
        'slug' => 'slug-changes-and-the-301-you-owe-your-readers',
        'excerpt' => 'Permanent URLs are a promise, not a preference.',
        'category' => 'SEO',
        'tags' => ['SEO', 'Laravel'],
        'project' => null,
        'status' => ContentStatus::Draft->value,
        'is_featured' => false,
        'published_days_ago' => null,
        'content' => <<<'HTML'
            <p>Renaming a published slug without a redirect quietly deletes every inbound link.</p>
            <h2>The mechanism</h2>
            <p>On save, if the slug changed and the row was already public, write the old path into a redirects table and serve a 301 from the middleware that runs before the 404.</p>
            HTML,
    ],
    [
        'title' => 'Turbo Frames for blog filtering',
        'slug' => 'turbo-frames-for-blog-filtering',
        'excerpt' => 'Query params, one route, zero JSON endpoints.',
        'category' => 'Web Development',
        'tags' => ['Turbo', 'Hotwire', 'JavaScript'],
        'project' => null,
        'status' => ContentStatus::Scheduled->value,
        'is_featured' => false,
        'published_days_ago' => -5,
        'content' => <<<'HTML'
            <p>Filtering a list is a GET request with different parameters. It does not need an API.</p>
            <h2>The setup</h2>
            <p>The listing route reads category, tag, search and page from the query string. The results live inside a frame, so a filter link swaps only that fragment.</p>
            <h3>Why this survives JavaScript being off</h3>
            <p>Without Turbo the same link performs a full navigation to the same URL and renders the same list. Nothing is lost but the smoothness.</p>
            HTML,
    ],
    [
        'title' => 'Reading time is a derived value',
        'slug' => 'reading-time-is-a-derived-value',
        'excerpt' => 'Anything an author can mistype is a value you should compute.',
        'category' => 'Development',
        'tags' => ['PHP', 'Laravel'],
        'project' => null,
        'status' => ContentStatus::Scheduled->value,
        'is_featured' => false,
        'published_days_ago' => -12,
        'content' => <<<'HTML'
            <p>Two hundred and fifty words a minute, computed on save, stored next to the content it came from.</p>
            <h2>Why store it at all</h2>
            <p>Because the listing page shows it, and recomputing from full article bodies to render a card is work with no upside.</p>
            <h2>Why not let authors type it</h2>
            <p>Every manually entered derived value drifts. This one drifts silently.</p>
            HTML,
    ],
    [
        'title' => 'Indexing for the queries you actually run',
        'slug' => 'indexing-for-the-queries-you-actually-run',
        'excerpt' => 'Composite indexes earn their keep; speculative ones do not.',
        'category' => 'Development',
        'tags' => ['MySQL', 'Performance'],
        'project' => null,
        'status' => ContentStatus::Archived->value,
        'is_featured' => false,
        'published_days_ago' => 400,
        'content' => <<<'HTML'
            <p>An unused index is a write tax with no read benefit.</p>
            <h2>Start from the query</h2>
            <p>The public listing filters on status and orders by publish date, so those two belong in one composite index, in that order.</p>
            HTML,
    ],
];
