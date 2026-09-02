<?php

/**
 * The core CMS pages backing the named routes in BUILD-PLAN §5 (/about, /now, /uses,
 * /resume) plus the homepage row. Seed data, not code — editable in Filament.
 *
 * @return array<int, array{title: string, slug: string, excerpt: string, content: string, status: string, is_navigable: bool}>
 */

use App\Enums\ContentStatus;

return [
    [
        'title' => 'Home',
        'slug' => 'home',
        'excerpt' => 'Web developer building fast, maintainable web applications.',
        'content' => '<p>I build web applications that stay fast as they grow: Laravel on the server, HTML over the wire, and just enough JavaScript to make the interface feel immediate.</p>',
        'status' => ContentStatus::Published->value,
        'is_navigable' => false,
    ],
    [
        'title' => 'About',
        'slug' => 'about',
        'excerpt' => 'Who I am, what I build, and how I work with small teams.',
        'content' => <<<'HTML'
            <p>I am Angga Tupski, a web developer working mostly with Laravel. I build content systems, internal tools and automation for teams who need software that keeps working after launch.</p>
            <h2>How I work</h2>
            <p>Server-rendered first. A schema shaped by how the data is actually read. Tests on the paths that would embarrass me if they broke.</p>
            <h2>What I am good at</h2>
            <ul>
                <li>Turning a messy manual process into a small, reliable system.</li>
                <li>Making an existing application measurably faster without a rewrite.</li>
                <li>Building admin panels the client does not need a manual for.</li>
            </ul>
            <p>If that sounds useful, the <a href="/contact">contact page</a> is the fastest way to reach me.</p>
            HTML,
        'status' => ContentStatus::Published->value,
        'is_navigable' => true,
    ],
    [
        'title' => 'Now',
        'slug' => 'now',
        'excerpt' => 'What I am focused on at the moment.',
        'content' => <<<'HTML'
            <p>A snapshot of current focus, updated when it stops being true.</p>
            <ul>
                <li>Rebuilding this site on Laravel 13 with Turbo instead of a SPA.</li>
                <li>Writing up the parts of past projects that were actually hard.</li>
                <li>Taking on one client engagement at a time.</li>
            </ul>
            HTML,
        'status' => ContentStatus::Published->value,
        'is_navigable' => true,
    ],
    [
        'title' => 'Uses',
        'slug' => 'uses',
        'excerpt' => 'The hardware, editors and services I actually use daily.',
        'content' => <<<'HTML'
            <h2>Editor and terminal</h2>
            <p>VS Code with a deliberately small extension list, and a Git Bash terminal that has outlived several machines.</p>
            <h2>Stack</h2>
            <p>Laravel, Filament, Tailwind CSS, Turbo and Stimulus. MariaDB locally, MySQL in production.</p>
            <h2>Services</h2>
            <p>GitHub for code, a plain VPS for hosting, and object storage only when a project genuinely needs it.</p>
            HTML,
        'status' => ContentStatus::Published->value,
        'is_navigable' => true,
    ],
    [
        'title' => 'Resume',
        'slug' => 'resume',
        'excerpt' => 'Experience, focus areas and how to get in touch.',
        'content' => <<<'HTML'
            <h2>Focus</h2>
            <p>Full-stack web development with an emphasis on Laravel, content systems and performance.</p>
            <h2>Selected experience</h2>
            <p>Government reporting tools, tourism and hospitality platforms, internal operations dashboards, and automation that removed recurring manual work.</p>
            <h2>Availability</h2>
            <p>Open to project work. Details on the <a href="/contact">contact page</a>.</p>
            HTML,
        'status' => ContentStatus::Draft->value,
        'is_navigable' => true,
    ],
];
