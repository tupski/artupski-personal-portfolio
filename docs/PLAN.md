# Personal Portfolio — PLAN.md

## 1. Project Overview

Build a fast, modern, SEO-friendly personal portfolio website for **Artupski / Angga Artupas**.

The website is primarily a personal portfolio and professional profile, but it should also function as:

* Personal portfolio
* Developer profile
* Project showcase
* Case study platform
* Technical blog
* Personal knowledge/content hub
* Contact / lead-generation website
* Long-term personal brand website

The website must prioritize:

1. **Performance**
2. **SEO**
3. **Clean architecture**
4. **Excellent writing/content experience**
5. **Easy content management**
6. **Minimal JavaScript on the public frontend**
7. **Fast navigation**
8. **Mobile-first responsive design**
9. **Maintainability**
10. **Future extensibility**

The frontend experience should feel similar to a modern server-rendered application enhanced by **Hotwired Turbo** rather than a traditional SPA.

The website should feel extremely fast when navigating between pages.

---

# 2. Core Technology Decisions

## Backend

* Laravel 13
* PHP 8.3+
* Eloquent ORM
* Laravel Blade
* Laravel Routing
* Laravel Validation
* Laravel Policies
* Laravel Cache
* Laravel Queues where necessary
* Laravel Scheduler where necessary

Laravel 13 is mandatory.

Do not downgrade Laravel to Laravel 12 merely because a third-party package has not yet been updated.

Before installing third-party packages, verify Laravel 13 compatibility.

---

# 3. Frontend Architecture

Before start the frontend, find skills named anti-ui-slop.

Use:

* Blade
* Hotwired Turbo
* Stimulus only when interaction genuinely requires JavaScript
* Alpine.js only when it provides a clear advantage for small local interactions
* Tailwind CSS
* Vite

Avoid building the public website as:

* React SPA
* Vue SPA
* Inertia application
* Heavy client-side application

The public website should remain primarily server-rendered.

## Principle

Prefer:

```text
Laravel Route
    ↓
Controller
    ↓
Query / Service
    ↓
Blade View
    ↓
Turbo-enhanced navigation
```

over:

```text
Browser
    ↓
JavaScript application
    ↓
API
    ↓
Laravel
```

The objective is to keep the initial page load fast, HTML meaningful, crawlable, and JavaScript-light.

---

# 4. Admin Panel

Use **Filament 5** as the admin panel.

Admin URL:

```text
/tupasadmin
```

The admin panel is completely separate from the public frontend. Create custom login page for admin.

Filament should be responsible for:

* Authentication
* Dashboard
* Content management
* Portfolio management
* Blog management
* Categories
* Tags
* Media management
* SEO fields
* Site settings (include git version control to update the website.)
* Navigation management
* Contact/message management
* User/account management if required

Do not build a custom admin panel unless Filament cannot reasonably support a requirement.

---

# 5. Rich Text / WYSIWYG Editor

Use Filament's RichEditor.

The editor must support:

* Headings
* Bold
* Italic
* Underline
* Strikethrough
* Links
* Blockquotes
* Ordered lists
* Unordered lists
* Code blocks
* Inline code
* Tables where useful
* Images
* Image uploads
* Image resizing
* Undo / redo
* Text alignment
* Custom content blocks where necessary

The editor should use TipTap through Filament's RichEditor.

Prefer storing article content as HTML unless there is a strong architectural reason to use structured JSON.

For content that requires custom rendering or reusable blocks, evaluate structured content where appropriate.

---

# 6. Custom Rich Content Blocks

The blog and page editor should eventually support reusable content blocks.

Examples:

```text
Hero
Callout
Quote
Image
Image + Text
Two Columns
CTA
Code Block
Video Embed
Gallery
Project Highlight
Timeline
Stats
FAQ
```

Do not build every block immediately.

Start with:

```text
Image
Callout
CTA
Code
Project Highlight
```

The architecture must allow additional blocks to be added later without changing the database structure.

---

# 7. Public Website

The public website should contain the following core pages.

```text
/
 /about
 /projects
 /projects/{project:slug}
 /blog
 /blog/{post:slug}
 /contact
 /now
 /uses
 /resume
```

Some pages can be disabled initially through configuration/settings.

---

# 8. Homepage

The homepage should communicate who Angga is within a few seconds.

Suggested sections:

```text
Hero
↓
Short introduction
↓
Selected projects
↓
What I build
↓
Experience / background
↓
Latest articles
↓
Technology / expertise
↓
CTA
↓
Footer
```

Do not make the homepage excessively long.

The homepage should prioritize:

* Identity
* Credibility
* Selected work
* Expertise
* Personality
* Contact opportunity

---

# 9. Hero Section

Hero should include:

* Name
* Professional title
* Short personal positioning statement
* Primary CTA
* Secondary CTA

Example direction:

```text
Angga Artupas

Web Developer & Builder

I build fast, useful web applications, internal tools,
automation systems, and digital products.

[View Projects]
[Read Blog]
```

Do not hard-code final copy during implementation.

Make important hero content editable from the admin panel.

---

# 10. About Page

The About page should contain:

* Introduction
* Personal story
* Professional background
* Experience
* Skills
* Working philosophy
* Current interests
* Selected technologies
* CTA

Avoid turning this into a conventional CV dump.

It should feel like a personal website rather than a recruitment portal.

---

# 11. Projects / Portfolio

Portfolio is one of the most important sections.

Create a dedicated `projects` entity.

Each project should support:

```text
Title
Slug
Short description
Long description
Featured image
Gallery
Project type
Client
Role
Start date
End date
Status
URL
Repository URL
Technologies
Challenges
Solutions
Results
Content
SEO metadata
Featured flag
Published flag
Sort order
```

Potential project types:

```text
Website
Web Application
CMS
Internal Tool
Automation
AI Tool
SaaS
Government Project
Personal Project
Open Source
Other
```

---

# 12. Project Detail Page

Project pages should feel like case studies rather than simple portfolio cards.

Suggested structure:

```text
Project Hero
↓
Project overview
↓
Role
↓
Timeline
↓
Technology stack
↓
Problem
↓
Approach
↓
Implementation
↓
Result
↓
Screenshots
↓
Technical details
↓
Related projects
↓
CTA
```

The page should support rich content.

Project content must be editable through Filament.

---

# 13. Featured Projects

Allow projects to be marked as:

```text
featured = true
```

Homepage should display only a curated subset.

Do not automatically display every project.

Admin should control ordering.

Use:

```text
sort_order
```

to control display priority.

---

# 14. Blog

Create a full blog/content system.

Core entities:

```text
posts
categories
tags
post_tag
```

Each post should support:

```text
title
slug
excerpt
content
featured_image
author
category
tags
status
published_at
reading_time
is_featured
seo_title
seo_description
canonical_url
og_image
```

Post statuses:

```text
draft
scheduled
published
archived
```

---

# 15. Blog Editor

The blog editor is a high-priority admin feature.

The editor should provide a comfortable writing experience.

Layout should ideally separate:

```text
Main content
    ↓
Title
    ↓
Excerpt
    ↓
Rich editor
```

and:

```text
Publishing
SEO
Featured image
Categories
Tags
```

Use collapsible/side panels where appropriate.

The writing experience should feel closer to a modern CMS than a generic CRUD form.

---

# 16. Blog Features

Implement:

* Drafts
* Scheduled publishing
* Publishing date
* Categories
* Tags
* Featured posts
* Featured image
* Reading time
* Related posts
* Previous/next navigation
* RSS feed
* Sitemap inclusion
* Canonical URLs
* Open Graph metadata
* Twitter/X card metadata
* Article structured data

---

# 17. Blog Categories

Categories should represent broad topics.

Possible initial categories:

```text
Development
AI
Laravel
Web Development
Automation
SEO
Tools
Projects
Personal
```

Do not hard-code these categories.

They must be manageable from Filament.

---

# 18. Tags

Tags should represent specific technologies/topics.

Examples:

```text
Laravel
PHP
JavaScript
Turbo
Hotwire
Filament
AI
OpenAI
Supabase
Vercel
SEO
Automation
WordPress
```

Tags should be reusable across posts.

---

# 19. Pages CMS

Create a generic `pages` entity.

Pages should support:

```text
title
slug
excerpt
content
featured_image
status
published_at
seo_title
seo_description
canonical_url
og_image
```

This allows pages such as:

```text
/about
/uses
/now
```

to be managed without changing code.

---

# 20. Page Builder

Pages should support rich content.

Do not build a complex Elementor-style page builder.

Use:

```text
RichEditor
+
Custom Rich Content Blocks
```

This provides enough flexibility while keeping the architecture simple.

---

# 21. Site Settings

Create a centralized settings system.

Settings should include:

## Identity

```text
site_name
site_title
site_description
logo
favicon
```

## Personal

```text
name
headline
bio
profile_photo
location
```

## Contact

```text
email
phone
contact_url
```

## Social

```text
github
linkedin
instagram
x
youtube
threads
```

## SEO

```text
default_title
default_description
default_og_image
robots_enabled
```

## Analytics

Allow optional:

```text
Google Analytics
Plausible
Umami
Other analytics
```

Do not force an analytics provider.

---

# 22. Navigation Management

The header navigation should be manageable from admin.

Possible initial navigation:

```text
Home
About
Projects
Blog
Contact
```

The admin should eventually be able to:

* Add menu items
* Remove menu items
* Reorder items
* Set external URLs
* Toggle visibility

Do not over-engineer this initially.

---

# 23. Media Management

Use a proper media architecture.

Recommended:

```text
spatie/laravel-medialibrary
```

Media should support:

* Images
* Project screenshots
* Blog images
* Profile image
* OG images
* Favicon where appropriate

Generate responsive image variants where beneficial.

Possible conversions:

```text
thumbnail
small
medium
large
og
```

Do not blindly generate dozens of image sizes.

---

# 24. Image Performance

Images must be optimized.

Requirements:

* Lazy loading where appropriate
* Explicit width/height
* Responsive images
* Modern image formats where possible
* Avoid layout shifts
* Avoid huge original images being served to mobile users
* Use appropriate image conversions

Hero/LCP images must not be lazily loaded if they are required for the first viewport.

---

# 25. SEO Architecture

SEO must be treated as a first-class feature.

Every indexable page should have:

```text
<title>
<meta name="description">
canonical
Open Graph
Twitter/X metadata
```

Implement:

```text
sitemap.xml
robots.txt
RSS feed
```

Structured data should be implemented where relevant.

---

# 26. Structured Data

Implement JSON-LD for:

## Website

```text
WebSite
```

## Person

```text
Person
```

## Blog

```text
Blog
```

## Article

```text
BlogPosting
```

## Breadcrumbs

```text
BreadcrumbList
```

## Projects

Use appropriate schema only when semantically justified.

Do not add fake schema just for SEO.

---

# 27. URL Strategy

URLs must be clean and permanent.

Use:

```text
/projects/{slug}
 /blog/{slug}
 /{page-slug}
```

Avoid:

```text
?id=123
/post.php?id=123
/project/123
```

Use route model binding with slugs.

Slugs must be unique.

---

# 28. Slug Management

When creating content:

```text
title
↓
slug generation
```

Allow manual override.

Once published, slug changes should be treated carefully.

If a published slug changes:

```text
old URL
    ↓
301 redirect
    ↓
new URL
```

Do not silently break old URLs.

---

# 29. Redirect Management

Create a simple redirects system if necessary.

Fields:

```text
from
to
status_code
active
```

Supported:

```text
301
302
```

Use 301 for permanent slug migrations.

---

# 30. Search

The initial release does not need an advanced search engine.

Implement a lightweight blog/project search.

Search should cover:

```text
title
excerpt
content
tags
categories
```

Use Laravel Scout only if the dataset or search requirements justify it.

Do not add Elasticsearch/Meilisearch unnecessarily.

Laravel 13 has expanded search capabilities, so the architecture should remain compatible with future Scout-based search if the content grows.

---

# 31. Contact

Create a contact form.

Fields:

```text
name
email
subject
message
```

Optional:

```text
company
budget
project_type
```

Contact submissions should be stored.

Admin should be able to:

* View messages
* Mark as read
* Mark as replied
* Archive

---

# 32. Contact Security

Implement:

* CSRF protection
* Rate limiting
* Validation
* Spam protection
* Honeypot
* Optional Turnstile
* Email validation

Do not expose the email address unnecessarily if not required.

---

# 33. Authentication

Admin authentication must be protected.

Use Filament authentication.

At minimum:

```text
email
password
```

Prefer:

```text
2FA
```

if supported and appropriate.

The public website must never expose admin functionality.

---

# 34. Authorization

Even if initially there is only one admin user, use authorization correctly.

Structure should support:

```text
Super Admin
Editor
Author
```

if multi-user functionality is needed later.

Do not rely only on hiding navigation items.

Use actual policies/authorization checks.

---

# 35. Turbo Architecture

Hotwired Turbo is a core requirement.

Use Turbo Drive for normal navigation.

The experience should feel like:

```text
Click link
↓
Turbo intercepts
↓
Server renders page
↓
Only relevant page content changes
↓
Browser remains fast
```

Do not convert every interaction into an AJAX endpoint.

---

# 36. Turbo Frames

Use Turbo Frames where they provide meaningful UX improvements.

Good use cases:

```text
Blog filtering
Search
Pagination
Contact form
Newsletter form
Related content
Comments if added later
```

Avoid unnecessary Turbo Frames.

The default page navigation should remain simple Turbo Drive navigation.

---

# 37. Turbo Streams

Use Turbo Streams for server-driven UI updates where beneficial.

Examples:

```text
Contact form submission
Live filtering
Admin-independent public interactions
Notifications
```

Do not force Turbo Streams into every feature.

---

# 38. Stimulus

Stimulus should be the default JavaScript framework for interactions that cannot be handled by HTML/CSS/Turbo.

Examples:

```text
Mobile menu
Copy button
Reading progress
Theme toggle
Search shortcut
Image lightbox
Code block interaction
```

Controllers should remain small.

Avoid creating a giant JavaScript application.

---

# 39. Alpine.js

Alpine.js is optional.

Only introduce Alpine when:

* The interaction is very local
* Stimulus would add unnecessary complexity
* The component does not need server communication

Do not use Alpine and Stimulus for the same component.

Prefer consistency.

---

# 40. Performance Goals

Performance is a primary requirement.

Target:

```text
Fast initial HTML response
Fast LCP
Minimal JS
Minimal CSS
Minimal third-party scripts
No unnecessary hydration
```

Target Lighthouse:

```text
Performance: 90+
Accessibility: 95+
Best Practices: 95+
SEO: 95+
```

These are targets, not excuses to compromise usability.

---

# 41. Caching

Use Laravel caching strategically.

Potential cache targets:

```text
Homepage
Navigation
Site settings
Categories
Tags
Popular/featured projects
Featured posts
```

Do not cache everything.

Cache invalidation must happen automatically when relevant content changes.

Example:

```text
Post updated
↓
Invalidate post-related cache
↓
Invalidate blog listing cache
↓
Invalidate homepage cache if necessary
```

---

# 42. Database Design

Initial database entities:

```text
users

pages

projects
project_technologies

posts
categories
tags
post_tag

media

contact_messages

redirects

site_settings
navigation_items
```

Additional tables should only be introduced when required.

---

# 43. Project Technologies

Create a reusable technologies entity if useful.

Fields:

```text
name
slug
icon
url
description
```

Projects can have many technologies.

Example:

```text
Laravel
PHP
Tailwind CSS
Turbo
Filament
MySQL
Supabase
```

Do not store technology lists as comma-separated strings.

Use relationships.

---

# 44. Database Relationships

Expected relationships:

```text
User
 ├── hasMany Posts
 └── hasMany Projects if ownership is required

Project
 └── belongsToMany Technologies

Post
 ├── belongsTo Category
 ├── belongsTo User
 └── belongsToMany Tags

Page
 └── belongsTo User if authorship is required
```

Keep relationships explicit.

---

# 45. Publishing Architecture

Use a consistent publishing model.

Content should support:

```text
draft
scheduled
published
archived
```

A post with:

```text
published_at <= now()
```

and published status should be publicly visible.

Scheduled posts must not appear publicly before their publish time.

---

# 46. Reading Time

Automatically calculate article reading time.

Example:

```text
250 words / minute
```

Do not make authors manually enter reading time.

Store the calculated value if useful for performance.

Recalculate when content changes.

---

# 47. Related Content

Implement simple related content.

For posts:

```text
Same category
+
Shared tags
```

Do not build an AI recommendation engine initially.

A deterministic query is sufficient.

---

# 48. Public Design System

Create reusable Blade components.

Suggested structure:

```text
resources/views/
    components/
        ui/
        layout/
        navigation/
        cards/
        content/
        media/
        blog/
        projects/
```

Examples:

```text
<x-button>
<x-card>
<x-badge>
<x-container>
<x-heading>
<x-prose>
<x-project-card>
<x-post-card>
<x-breadcrumbs>
```

Do not duplicate markup unnecessarily.

---

# 49. Layouts

Create:

```text
layouts/app.blade.php
layouts/guest.blade.php
layouts/blog.blade.php
```

or equivalent clean structure.

The exact structure can be adjusted during implementation.

The important principle is to separate:

```text
Global shell
Content layout
Specialized content layout
```

---

# 50. Typography

Use a clean typography system.

The design should feel:

```text
Technical
Minimal
Personal
Premium
Readable
Modern
```

Avoid:

* Excessive gradients
* Excessive animations
* Huge decorative backgrounds
* Overly rounded everything
* Generic AI-generated landing page aesthetics

The website should feel like a developer's personal site.

---

# 51. Animation

Animations must be subtle.

Use animation for:

* Page transitions where Turbo permits
* Hover states
* Mobile menu
* Image interactions
* Small content reveals

Do not use:

```text
heavy parallax
continuous animations
large JS animation libraries
```

unless there is a strong reason.

Respect:

```css
prefers-reduced-motion
```

---

# 52. Dark Mode

Support:

```text
light
dark
system
```

The preference should persist.

Avoid flash of incorrect theme on initial page load.

---

# 53. Accessibility

Requirements:

* Semantic HTML
* Keyboard navigation
* Visible focus states
* Proper labels
* Accessible forms
* Alt text
* Heading hierarchy
* Sufficient contrast
* Reduced motion support
* Accessible mobile navigation

Do not sacrifice accessibility for visual effects.

---

# 54. Mobile

Mobile-first.

The website must work well at:

```text
320px
375px
390px
430px
768px
1024px
1440px+
```

Pay special attention to:

* Typography
* Navigation
* Project cards
* Blog reading experience
* Rich content
* Images
* Code blocks
* Tables

Wide code blocks must scroll horizontally rather than breaking the page.

---

# 55. Blog Reading Experience

Blog pages should prioritize reading.

Use:

```text
Readable content width
Comfortable line-height
Good heading spacing
Code block styling
Image captions
Table overflow
Blockquotes
Related articles
```

Avoid excessive sidebar content.

On mobile, the article should be the primary focus.

---

# 56. RSS

Implement:

```text
/feed
```

or:

```text
/rss.xml
```

RSS should contain published posts only.

Include:

```text
title
description
link
published date
author
```

---

# 57. Sitemap

Generate dynamic sitemap.

Include:

```text
Homepage
Pages
Published projects
Published posts
Categories where indexable
Tags only if indexable
```

Do not include:

```text
drafts
admin URLs
contact submissions
private resources
```

---

# 58. Robots

Admin routes must not be indexed.

Example conceptual rules:

```text
Disallow: /admin
```

Do not accidentally block public content.

---

# 59. Security

Follow Laravel security best practices.

Requirements:

* CSRF
* XSS protection
* Mass assignment protection
* Authorization
* Validation
* Rate limiting
* Secure file uploads
* MIME validation
* File size validation
* Sanitized rich content
* Secure admin authentication
* Secure headers where appropriate

Rich HTML content must never be rendered blindly without appropriate sanitization.

---

# 60. File Upload Security

For all uploads:

```text
Validate MIME type
Validate file size
Generate safe filenames
Do not trust original filename
Store outside executable locations where appropriate
```

Images should be processed before public delivery when necessary.

---

# 61. Error Pages

Create custom:

```text
404
403
419
429
500
503
```

The design should match the main website.

404 should provide:

```text
Search
Back home
Popular projects
Latest posts
```

where appropriate.

---

# 62. Admin Dashboard

The Filament dashboard should be useful, not decorative.

Show:

```text
Published posts
Draft posts
Scheduled posts
Projects
Unread messages
Recent activity
```

Optional:

```text
Views
Traffic
Top posts
```

Only implement analytics widgets if a reliable data source exists.

Do not invent analytics data.

---

# 63. Content Workflow

Preferred workflow:

```text
Create draft
↓
Write/edit
↓
Preview
↓
SEO review
↓
Publish now / schedule
↓
Public
```

Admin should make this workflow obvious.

---

# 64. Preview

Editors should be able to preview content before publishing.

Possible approach:

```text
/admin/...
      ↓
Preview action
      ↓
Signed preview URL
      ↓
Public template
```

Draft content must not become publicly indexable.

Use temporary/signed URLs where appropriate.

---

# 65. SEO Preview

In admin, provide a compact SEO preview:

```text
Google-style preview
Open Graph preview
```

At minimum show:

```text
SEO title
SEO description
URL
```

Do not attempt to fully reproduce Google's UI.

---

# 66. Content Validation

Posts should require:

```text
title
slug
content
```

Optional but recommended:

```text
excerpt
featured image
category
SEO title
SEO description
```

Validate SEO metadata length sensibly.

Do not reject content merely because an SEO field is not filled if a sensible fallback exists.

---

# 67. Automatic SEO Fallbacks

If:

```text
seo_title
```

is empty:

```text
use post title
```

If:

```text
seo_description
```

is empty:

```text
use excerpt
```

If excerpt is empty:

```text
generate a safe truncated excerpt from content
```

Do not duplicate SEO logic throughout Blade templates.

Create a centralized SEO layer/component.

---

# 68. Canonical URL

Every indexable page should have a canonical URL.

Default:

```text
current public URL
```

Allow manual override only when necessary.

---

# 69. Open Graph

Support:

```text
og:title
og:description
og:image
og:url
og:type
```

For posts:

```text
og:type = article
```

For normal pages:

```text
og:type = website
```

---

# 70. Metadata Architecture

Create a reusable SEO component.

Example:

```blade
<x-seo
    :title="$seoTitle"
    :description="$seoDescription"
    :image="$ogImage"
    :canonical="$canonical"
/>
```

Do not duplicate metadata markup in every page.

---

# 71. Content Rendering

Rich content should be rendered through a controlled rendering layer.

Do not scatter:

```blade
{!! $post->content !!}
```

throughout the application.

Create a reusable content renderer/component.

This makes it easier to:

* Sanitize
* Process embeds
* Apply classes
* Process images
* Add custom blocks
* Change rendering behavior later

---

# 72. Code Blocks

Blog posts may contain code.

Code blocks must:

* Be readable
* Scroll horizontally
* Preserve whitespace
* Not break mobile layout
* Have accessible contrast

Optional:

```text
Copy button
Language label
```

Use Stimulus for copy interaction if required.

Do not add a heavy syntax highlighting library unless needed.

---

# 73. External Embeds

If supporting:

```text
YouTube
Vimeo
CodePen
GitHub
```

do not blindly render arbitrary iframe URLs.

Whitelist supported providers.

Validate URLs before rendering.

---

# 74. Performance Budget

Keep the public frontend lightweight.

Avoid adding packages unless they solve a real problem.

Before adding any frontend dependency, ask:

```text
Can this be done with HTML?
Can this be done with CSS?
Can this be done with Turbo?
Can this be done with Stimulus?
```

Only then add a dependency.

---

# 75. JavaScript Budget

Public frontend should ideally ship only the JavaScript actually required.

Do not include:

```text
Admin JavaScript
Rich editor dependencies
Filament assets
```

on public pages.

Admin assets must remain isolated.

---

# 76. CSS Architecture

Use Tailwind CSS.

Create a consistent design system.

Avoid giant custom CSS files.

Custom CSS is allowed when:

* Rich text rendering needs it
* Browser-specific behavior needs it
* Tailwind cannot express something cleanly
* Third-party editor output needs styling

---

# 77. Environment Configuration

Use `.env` for:

```text
APP_NAME
APP_URL
APP_ENV
APP_KEY
DB_*
MAIL_*
FILESYSTEM_DISK
CACHE_*
QUEUE_*
```

Never commit:

```text
API keys
passwords
tokens
private credentials
```

---

# 78. Deployment

The application should be deployable to a normal Laravel-compatible server.

Preferred deployment architecture:

```text
Nginx
PHP-FPM
MySQL/PostgreSQL
Redis optional
Queue worker optional
Cron
```

The public website must not depend on Node.js running in production.

Node/Vite is only required during build/deployment.

---

# 79. Queue Usage

Use queues only for tasks that benefit from asynchronous processing.

Examples:

```text
Image processing
Email sending
Sitemap generation if expensive
Heavy media operations
```

Do not put normal page rendering into queues.

---

# 80. Scheduler

Use Laravel Scheduler for:

```text
Scheduled post publishing if necessary
Cleanup
Maintenance
Sitemap refresh if necessary
```

Avoid unnecessary scheduled tasks.

---

# 81. Database Indexing

Index:

```text
slug
status
published_at
is_featured
sort_order
category_id
```

and relationship foreign keys.

Composite indexes should be added based on actual query patterns.

---

# 82. N+1 Prevention

Public pages must avoid N+1 queries.

Use eager loading where appropriate.

Example:

```text
posts
    with category
    with tags
    with author
```

Do not blindly eager load every relationship.

---

# 83. Pagination

Blog listing must use pagination.

Do not load the entire blog table.

Preferred:

```text
paginate()
```

or:

```text
simplePaginate()
```

depending on requirements.

---

# 84. Infinite Scroll

Do not implement infinite scroll initially.

Normal pagination is:

* Better for SEO
* Easier to maintain
* Accessible
* Faster to implement

Turbo can later enhance pagination without changing the underlying architecture.

---

# 85. Testing

Use Laravel's testing stack.

Tests should cover:

## Feature

```text
Homepage
Project listing
Project detail
Blog listing
Blog detail
Pages
Contact
RSS
Sitemap
SEO metadata
```

## Security

```text
Admin authentication
Authorization
Draft visibility
Scheduled post visibility
Upload validation
Contact rate limiting
```

## Database

```text
Relationships
Slug uniqueness
Publishing logic
```

---

# 86. Critical Tests

At minimum:

```text
Draft posts are not public
Scheduled posts are not public before published_at
Archived posts are not public
Published posts are public
Changed slugs redirect correctly
Admin cannot be accessed without authentication
Contact form validates input
Unauthorized users cannot modify content
```

---

# 87. Turbo Tests

Verify that Turbo navigation does not break:

```text
Navigation
Forms
Modals
Mobile menu
Code copy
Image interactions
Back/forward browser navigation
```

Turbo-specific lifecycle behavior must be considered when initializing Stimulus or DOM-dependent behavior.

---

# 88. Browser Compatibility

Target modern:

```text
Chrome
Edge
Firefox
Safari
iOS Safari
Android Chrome
```

Do not optimize for obsolete browsers unless specifically required.

---

# 89. Logging

Use Laravel logging.

Do not expose sensitive information in logs.

Especially never log:

```text
passwords
session tokens
API keys
authentication secrets
```

---

# 90. Admin Activity

If practical, maintain basic audit information:

```text
created_by
updated_by
published_by
published_at
```

Full audit history is optional for the first version.

---

# 91. SEO-Friendly Content Architecture

Content should be authored with SEO in mind but never become an SEO spam system.

Each article should encourage:

```text
Clear title
Useful introduction
Good heading hierarchy
Original information
Relevant internal links
Relevant external links
Useful images
Readable formatting
```

Do not automatically generate meaningless SEO content.

---

# 92. Internal Linking

The architecture should make internal linking easy.

Potential related links:

```text
Post → Project
Post → Post
Project → Post
Page → Project
```

Consider manually selected related content in admin.

Do not rely exclusively on automatic similarity.

---

# 93. Project ↔ Blog Relationships

Allow posts to optionally reference projects.

Example:

```text
Blog post:
"How I built X"

Related project:
"X Project"
```

Project pages can display:

```text
Related articles
```

This creates a useful content graph.

---

# 94. Personal Brand

The website should not look like a generic portfolio template.

Design should communicate:

```text
Developer
Builder
Independent
Technical
Practical
Modern
Personal
```

Avoid stock-template visual language.

---

# 95. Content Tone

The content system must support a personal writing style.

The blog should allow:

* Technical tutorials
* Project write-ups
* Lessons learned
* Tool reviews
* AI experiments
* Automation experiments
* Personal notes
* Engineering decisions

The CMS should not constrain the writing style.

---

# 96. Future AI Integration

Do not implement AI features in V1.

However, keep the architecture extensible for future features such as:

```text
AI writing assistant
SEO suggestion
Title generation
Excerpt generation
Content summarization
Related content suggestion
Semantic search
AI chatbot
```

Laravel 13 already includes first-party AI capabilities, so the application can adopt them later if there is a real use case.

Do not add AI merely because the framework supports it.

---

# 97. Analytics

Analytics should be optional.

Do not build a custom analytics system initially.

Support external providers through configuration.

Potential options:

```text
Plausible
Umami
Google Analytics
```

Analytics scripts should only load when configured.

---

# 98. Privacy

Do not collect unnecessary visitor data.

If analytics/cookies are introduced, implement the required privacy UX for the target audience and jurisdiction.

---

# 99. Admin UX Principles

The admin should optimize for the owner.

Prioritize:

```text
Fast content creation
Fast editing
Clear publishing workflow
Good search
Good filters
Useful bulk actions
Minimal clicks
```

The admin does not need to mirror the public website visually.

---

# 100. Filament Resources

Expected initial resources:

```text
PageResource
ProjectResource
PostResource
CategoryResource
TagResource
TechnologyResource
ContactMessageResource
SiteSettingResource
NavigationResource
RedirectResource
```

Additional resources only when necessary.

---

# 101. Filament Post Form

Suggested structure:

```text
Post Information
    Title
    Slug
    Excerpt
    Content

Publishing
    Status
    Published At
    Featured

Taxonomy
    Category
    Tags

Media
    Featured Image

SEO
    SEO Title
    SEO Description
    Canonical URL
    OG Image
```

The form should be organized into logical sections.

Do not create one extremely long unstructured form.

---

# 102. Filament Project Form

Suggested structure:

```text
Project Information
    Title
    Slug
    Short Description
    Content

Project Details
    Client
    Role
    Project Type
    Status
    Start Date
    End Date

Technology
    Technologies

Media
    Featured Image
    Gallery

Links
    Live URL
    Repository URL

SEO
    SEO Title
    SEO Description
```

---

# 103. Filament Page Form

Suggested structure:

```text
Page Information
    Title
    Slug
    Excerpt
    Content

Publishing
    Status
    Published At

Media
    Featured Image

SEO
    SEO Title
    SEO Description
    Canonical
    OG Image
```

---

# 104. Admin Media Workflow

Media uploaded through the admin should be reusable where appropriate.

Avoid creating duplicate physical files every time an image is referenced.

Use media IDs/relationships rather than raw file paths where practical.

---

# 105. Database Content vs Code

Content should live in the database.

Code should define:

```text
Structure
Behavior
Presentation
Business rules
```

Database should define:

```text
Text
Projects
Posts
Pages
Categories
Tags
Settings
```

Do not hard-code portfolio content in Blade templates.

---

# 106. Seed Data

Create development seeders with realistic placeholder data.

Seed:

```text
Admin user
Sample pages
Sample projects
Sample posts
Categories
Tags
Technologies
Site settings
```

Do not use fake lorem ipsum everywhere.

Use realistic developer-oriented sample content so UI can be evaluated properly.

---

# 107. Factories

Create factories for:

```text
Post
Project
Category
Tag
Technology
Page
ContactMessage
```

Factories should support testing and development.

---

# 108. Installation

Start from an empty directory.

The implementation agent should:

1. Verify PHP version.
2. Verify Composer.
3. Create Laravel 13 application.
4. Configure environment.
5. Install frontend dependencies.
6. Install Filament 5.
7. Install Turbo/Hotwire integration.
8. Install required supporting packages.
9. Configure database.
10. Run migrations.
11. Create admin user.
12. Run test suite.
13. Build frontend assets.

Do not assume packages are already installed.

---

# 109. Dependency Discipline

Before installing any package:

1. Confirm it supports Laravel 13.
2. Confirm it is actively maintained.
3. Check whether Laravel already provides the functionality.
4. Check whether Filament already provides the functionality.
5. Avoid duplicate libraries.

Do not install packages simply because they are popular.

---

# 110. Recommended Dependency Direction

Core:

```text
Laravel 13
Filament 5
Hotwired Turbo
Tailwind CSS
Vite
```

Potential:

```text
Spatie Laravel Media Library
```

Only add additional packages when justified.

---

# 111. Architecture

Prefer a conventional Laravel architecture.

Suggested:

```text
app/
├── Actions/
├── Console/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Policies/
├── Services/
├── Support/
└── View/
```

Do not create an unnecessarily complicated DDD architecture for a personal portfolio.

---

# 112. Actions / Services

Use Actions or Services for logic that is:

* Reused
* Complex
* Important enough to test independently

Examples:

```text
PublishPost
GenerateSlug
CalculateReadingTime
GenerateSeoMetadata
ProcessUploadedImage
```

Do not create a service class for every trivial Eloquent query.

---

# 113. Controllers

Controllers should remain thin.

Preferred:

```text
Request
↓
Controller
↓
Action/Query/Model
↓
View
```

Avoid putting large business logic directly in controllers.

---

# 114. Blade Components

Use components for repeated UI.

Avoid copy/paste.

However, do not turn every `<div>` into a component.

Components should represent meaningful reusable concepts.

---

# 115. Query Optimization

For every public page:

* Inspect SQL queries.
* Check N+1.
* Check unnecessary columns.
* Check pagination.
* Check caching opportunities.
* Check image loading.

Do not optimize prematurely, but do not ship obvious N+1 problems.

---

# 116. HTTP Caching

Where appropriate, consider:

```text
ETag
Last-Modified
Cache-Control
```

especially for static-ish public pages.

Do not introduce complicated caching infrastructure before measuring the need.

---

# 117. Turbo Cache

Understand Turbo Drive caching behavior.

Pages that should not be cached by Turbo must explicitly opt out.

Authentication/admin areas must remain isolated from public Turbo behavior.

---

# 118. Turbo + Forms

Forms should work normally without JavaScript.

Turbo may progressively enhance them.

If JavaScript fails:

```text
form should still function
```

where practical.

This is an important principle of the application.

---

# 119. Progressive Enhancement

Public website functionality should degrade gracefully.

Core requirements:

```text
Navigation works without JS
Content is server-rendered
Forms have normal HTTP fallbacks
Links have real URLs
Images have normal src
```

JavaScript enhances the experience rather than being the foundation.

---

# 120. Accessibility + Turbo

When Turbo changes page content:

* Ensure focus behavior is sensible.
* Ensure page titles update.
* Ensure screen readers receive meaningful navigation changes.
* Do not rely solely on animation to communicate state.

---

# 121. Development Phases

## Phase 1 — Foundation

Implement:

```text
Laravel 13
Tailwind
Turbo
Filament
Database
Authentication
Basic layout
Design system
```

Deliverable:

```text
Application boots
Admin works
Public homepage works
```

---

## Phase 2 — CMS

Implement:

```text
Pages
Projects
Posts
Categories
Tags
Technologies
Media
Settings
```

Deliverable:

```text
All major content can be managed from admin.
```

---

## Phase 3 — Public Website

Implement:

```text
Homepage
About
Projects
Project detail
Blog
Blog detail
Contact
```

Deliverable:

```text
Complete public website.
```

---

## Phase 4 — SEO

Implement:

```text
SEO component
Sitemap
Robots
RSS
Canonical
Open Graph
JSON-LD
Redirects
```

---

## Phase 5 — Performance

Audit:

```text
Database
Queries
Images
CSS
JavaScript
Turbo
Caching
HTTP headers
```

---

## Phase 6 — Testing

Implement:

```text
Feature tests
Security tests
Publishing tests
SEO tests
Critical browser tests
```

---

## Phase 7 — Polish

Improve:

```text
Typography
Spacing
Animation
Mobile
Dark mode
Accessibility
Admin UX
Content UX
```

---

# 122. MVP Definition

The MVP is complete when:

### Public

```text
Homepage
About
Projects
Project detail
Blog
Blog detail
Contact
404
```

### Admin

```text
Authentication
Pages
Projects
Posts
Categories
Tags
Technologies
Media
Settings
```

### Technical

```text
Laravel 13
Turbo
Filament 5
Responsive
SEO-ready
Sitemap
RSS
Secure
Tested
```

---

# 123. V1 Non-Goals

Do NOT implement initially:

```text
Newsletter platform
Comments
User registration
Social login
Complex analytics
AI writing assistant
AI chatbot
Advanced search engine
Multi-language
Multi-tenant architecture
Headless CMS API
Mobile app
Complex page builder
Real-time notifications
```

These can be added later if justified.

---

# 124. Future Roadmap

Potential V2 features:

```text
Newsletter
AI-assisted writing
Semantic blog search
Project changelog
Now page automation
GitHub integration
GitHub activity
Open source showcase
YouTube integration
Social content integration
Analytics dashboard
Content scheduling
Newsletter automation
API
MCP integration
```

These should not influence V1 architecture excessively.

---

# 125. Git Workflow

Use small, logical commits.

Example:

```text
feat: initialize Laravel 13 application
feat: install Filament admin panel
feat: add public design system
feat: add page CMS
feat: add project CMS
feat: add blog CMS
feat: add Turbo navigation
feat: add SEO infrastructure
feat: add sitemap and RSS
test: add publishing workflow tests
perf: optimize homepage queries
```

Avoid giant commits containing unrelated changes.

---

# 126. Documentation

Create:

```text
README.md
PLAN.md
```

Optional:

```text
CHANGELOG.md
```

README should explain:

```text
Project
Requirements
Installation
Environment
Database
Admin access
Development
Testing
Deployment
```

---

# 127. Environment Example

Create:

```text
.env.example
```

Never include real credentials.

Document required environment variables.

---

# 128. Code Quality

Follow Laravel conventions.

Prioritize:

```text
Readable
Simple
Explicit
Testable
Maintainable
```

Avoid:

```text
Overengineering
Premature abstraction
Magic
Huge classes
Huge controllers
Huge Blade templates
```

---

# 129. Definition of Done

A feature is considered complete only when:

```text
Database
    ↓
Model
    ↓
Validation
    ↓
Authorization
    ↓
Admin
    ↓
Public UI
    ↓
SEO
    ↓
Responsive UI
    ↓
Tests
```

have been considered.

Not every feature needs every layer, but the implementation agent must consciously verify each relevant layer.

---

# 130. Final Architecture

The intended architecture is:

```text
                    ┌──────────────────────┐
                    │      Visitor         │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │   Laravel 13         │
                    │   Blade              │
                    │   Turbo              │
                    └──────────┬───────────┘
                               │
                    ┌──────────▼───────────┐
                    │    Public Website     │
                    │                       │
                    │ Home                  │
                    │ About                 │
                    │ Projects              │
                    │ Blog                  │
                    │ Contact               │
                    └──────────┬───────────┘
                               │
                               ▼
                    ┌──────────────────────┐
                    │      Eloquent         │
                    │      Database         │
                    └──────────┬───────────┘
                               ▲
                               │
                    ┌──────────┴───────────┐
                    │      Filament 5       │
                    │       /admin          │
                    │                       │
                    │ Pages                 │
                    │ Projects              │
                    │ Posts                 │
                    │ Categories            │
                    │ Tags                  │
                    │ Media                 │
                    │ Settings              │
                    └──────────────────────┘
```

---

# 131. Important Implementation Rules

The coding agent MUST follow these rules.

### Rule 1

Do not replace Laravel Blade + Turbo with React/Vue/Inertia.

### Rule 2

Do not build a custom admin panel when Filament can handle the requirement.

### Rule 3

Do not hard-code editable content into Blade.

### Rule 4

Do not add unnecessary JavaScript.

### Rule 5

Do not add unnecessary dependencies.

### Rule 6

Do not expose draft/scheduled content publicly.

### Rule 7

Do not render untrusted rich HTML without sanitization.

### Rule 8

Do not sacrifice SEO for client-side interactions.

### Rule 9

Do not sacrifice accessibility for animations.

### Rule 10

Do not optimize blindly. Measure first.

### Rule 11

Do not introduce a complex architecture merely because the application could theoretically grow.

### Rule 12

The application must remain easy for one developer to maintain.

---

# 132. First Implementation Priority

After the project is initialized, the implementation should proceed in this order:

```text
1. Laravel 13
2. Tailwind + Vite
3. Turbo
4. Filament 5
5. Authentication
6. Database foundation
7. Site settings
8. Global Blade layout
9. Design system
10. Pages CMS
11. Projects CMS
12. Blog CMS
13. Public homepage
14. Public projects
15. Public blog
16. Contact
17. SEO
18. Sitemap + RSS
19. Media optimization
20. Performance audit
21. Tests
22. Final UI polish
```

Do not start by building every visual detail.

First establish:

```text
Foundation
↓
CMS
↓
One complete content type
↓
Public rendering
↓
Reusable components
↓
Expand
```

---

# 133. Success Criteria

The finished portfolio should feel like:

> A real personal website built by a developer who cares about performance, content, and engineering quality.

It should **not** feel like:

> A Laravel CRUD application with a portfolio theme attached.

The public experience should be:

```text
Fast
Clean
Personal
Technical
Readable
SEO-friendly
Mobile-friendly
```

The admin experience should be:

```text
Powerful
Comfortable
Fast
Structured
Easy to use
```

And the overall application should remain:

```text
Laravel-native
Server-rendered
Turbo-enhanced
JavaScript-light
Filament-powered
Easy to maintain
```

---

# 134. Final Technical Principle

The most important architectural principle for this project is:

```text
SERVER RENDER FIRST.
ENHANCE WITH TURBO.
ENHANCE FURTHER WITH STIMULUS ONLY WHEN NEEDED.
```

The browser should receive useful HTML immediately.

Turbo should make navigation feel instant.

Filament should make content management pleasant.

Laravel should remain the center of the application.

The result should be a portfolio that is not only visually good, but also technically representative of how the developer actually builds software.
