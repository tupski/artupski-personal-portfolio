<?php

use App\Enums\ContentStatus;
use App\Enums\ProjectStatus;
use App\Enums\ProjectType;

/**
 * Portfolio seed projects (spec §106). Two are featured with distinct sort_order so
 * the homepage curation in §13 can be evaluated with real rows.
 *
 * @return array<int, array<string, mixed>>
 */
return [
    [
        'title' => 'Bali Malayali Tourism Portal',
        'slug' => 'bali-malayali-tourism-portal',
        'short_description' => 'A multi-language destination portal with an editorial workflow, structured place data and printable itineraries.',
        'content' => <<<'HTML'
            <h2>Overview</h2>
            <p>The client had strong content trapped in a theme that could not express relationships between places, stays and activities. Everything was a page, so nothing could be reused.</p>
            <h2>Approach</h2>
            <p>Modelled places, properties and activities as first-class entities with a shared taxonomy, then rebuilt the public site as server-rendered pages with cached read models.</p>
            <h2>Result</h2>
            <p>Editors publish an itinerary in minutes instead of copy-pasting between pages, and the same data feeds the sitemap, feeds and internal links.</p>
            HTML,
        'challenges' => 'Years of content stored as unstructured page HTML, with no consistent identifiers between related entries.',
        'solutions' => 'Wrote an import command that extracted entities from the legacy markup, then reviewed the results in the new admin before switching the public site over.',
        'results' => 'Publishing time down from hours to minutes; organic entry pages up because related content finally interlinked.',
        'project_type' => ProjectType::Website->value,
        'client' => 'Bali Malayali',
        'role' => 'Lead developer',
        'started_on' => '2024-02-01',
        'ended_on' => '2024-08-15',
        'project_status' => ProjectStatus::Maintained->value,
        'status' => ContentStatus::Published->value,
        'is_featured' => true,
        'sort_order' => 10,
        'live_url' => 'https://balimalayali.com',
        'repository_url' => null,
        'technologies' => ['Laravel', 'PHP', 'Filament', 'Tailwind CSS', 'MySQL'],
    ],
    [
        'title' => 'Serviced Apartment CMS',
        'slug' => 'serviced-apartment-cms',
        'short_description' => 'A booking-aware content system with rate calendars, promo rules and channel exports.',
        'content' => <<<'HTML'
            <h2>Overview</h2>
            <p>Rates, promos and availability lived in three spreadsheets and one person's memory. The website was always slightly wrong.</p>
            <h2>Approach</h2>
            <p>One source of truth for rates with explicit date ranges, plus a small export layer so the booking channels read the same numbers as the website.</p>
            <h2>Result</h2>
            <p>Pricing mismatches stopped being a weekly conversation.</p>
            HTML,
        'challenges' => 'Overlapping promo periods that had to resolve deterministically, and a currency conversion that changed daily.',
        'solutions' => 'Made promo resolution a single ordered query with explicit precedence, and cached converted rates with a daily refresh.',
        'results' => 'Zero pricing mismatches since launch; the operations team now edits rates directly.',
        'project_type' => ProjectType::Cms->value,
        'client' => 'Private client',
        'role' => 'Full-stack developer',
        'started_on' => '2023-05-10',
        'ended_on' => '2023-11-30',
        'project_status' => ProjectStatus::Completed->value,
        'status' => ContentStatus::Published->value,
        'is_featured' => true,
        'sort_order' => 20,
        'live_url' => null,
        'repository_url' => null,
        'technologies' => ['Laravel', 'PHP', 'Filament', 'MySQL', 'Docker'],
    ],
    [
        'title' => 'Invoice Automation Suite',
        'slug' => 'invoice-automation-suite',
        'short_description' => 'Reads supplier email, extracts line items and posts reviewed drafts to the ledger.',
        'content' => <<<'HTML'
            <h2>Overview</h2>
            <p>Two people spent a day a week retyping supplier invoices. The data was already in the inbox.</p>
            <h2>Approach</h2>
            <p>A queue worker parses attachments, an LLM call normalises the line items, and every draft waits for a human approval before it reaches the ledger.</p>
            <h2>Result</h2>
            <p>Most invoices now need one click. The rest fail loudly instead of quietly.</p>
            HTML,
        'challenges' => 'Supplier layouts changed without warning, and a wrong number is worse than no number.',
        'solutions' => 'Never auto-post. Confidence below the threshold routes to manual review with the original attachment side by side.',
        'results' => 'Roughly a day of manual entry per week removed, with an audit trail on every posted draft.',
        'project_type' => ProjectType::Automation->value,
        'client' => null,
        'role' => 'Solo developer',
        'started_on' => '2025-01-15',
        'ended_on' => null,
        'project_status' => ProjectStatus::InProgress->value,
        'status' => ContentStatus::Published->value,
        'is_featured' => false,
        'sort_order' => 30,
        'live_url' => null,
        'repository_url' => 'https://github.com/tupski/invoice-automation',
        'technologies' => ['Laravel', 'PHP', 'OpenAI API', 'Redis'],
    ],
    [
        'title' => 'Village Data Registry',
        'slug' => 'village-data-registry',
        'short_description' => 'A government reporting tool with strict audit trails and printable statutory forms.',
        'content' => <<<'HTML'
            <h2>Overview</h2>
            <p>Reporting was done in a word processor and mailed as attachments. Nobody could tell which version was current.</p>
            <h2>Approach</h2>
            <p>Structured forms with validation at entry, an append-only change log, and print stylesheets that match the official layout exactly.</p>
            <h2>Result</h2>
            <p>Submissions are now traceable to a person and a timestamp.</p>
            HTML,
        'challenges' => 'The printed output was legally prescribed, down to column widths.',
        'solutions' => 'Built the print layout first and derived the screen form from it, rather than the other way round.',
        'results' => 'Rejected submissions dropped sharply because validation happens before printing, not after.',
        'project_type' => ProjectType::Government->value,
        'client' => 'Regional government office',
        'role' => 'Technical lead',
        'started_on' => '2022-07-01',
        'ended_on' => '2023-02-28',
        'project_status' => ProjectStatus::Archived->value,
        'status' => ContentStatus::Published->value,
        'is_featured' => false,
        'sort_order' => 40,
        'live_url' => null,
        'repository_url' => null,
        'technologies' => ['Laravel', 'PHP', 'MySQL'],
    ],
    [
        'title' => 'Support Triage Assistant',
        'slug' => 'support-triage-assistant',
        'short_description' => 'Classifies inbound tickets and drafts a first reply for a human to approve.',
        'content' => <<<'HTML'
            <h2>Overview</h2>
            <p>An in-progress internal tool: the classification is reliable, the drafting still needs supervision.</p>
            <h2>Approach</h2>
            <p>Category prediction runs on every inbound ticket; draft replies are generated only for the categories where a template already existed.</p>
            HTML,
        'challenges' => 'Confidently wrong answers are worse than no answer in a support context.',
        'solutions' => 'Drafts are never sent automatically, and every suggestion shows the source ticket it was modelled on.',
        'results' => 'Not yet measured — this one is still a draft case study.',
        'project_type' => ProjectType::AiTool->value,
        'client' => null,
        'role' => 'Solo developer',
        'started_on' => '2026-03-01',
        'ended_on' => null,
        'project_status' => ProjectStatus::InProgress->value,
        'status' => ContentStatus::Draft->value,
        'is_featured' => false,
        'sort_order' => 50,
        'live_url' => null,
        'repository_url' => null,
        'technologies' => ['Laravel', 'PHP', 'OpenAI API'],
    ],
];
