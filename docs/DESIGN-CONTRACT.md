# DESIGN CONTRACT — artupski.com public frontend

Binding UI contract for the public site. Every visual/interaction decision an implementer needs is
here or is marked `DECISION:`. If this document and [`docs/PLAN.md`](docs/PLAN.md) disagree on
*visual* detail, this document wins; if they disagree on *scope or behaviour*, `PLAN.md` wins.

- Scope: public frontend only (`/`, `/about`, `/projects`, `/projects/{slug}`, `/blog`, `/blog/{slug}`, `/contact`, `/now`, `/uses`, `/resume`, error pages). Filament admin is out of scope and inherits nothing from here.
- Stack assumed: Laravel 13 + Blade + Turbo + Stimulus + Tailwind + Vite. Server-rendered, JS-light.
- Companion docs: [`docs/PLAN.md`](docs/PLAN.md) (owner spec), [`docs/BUILD-PLAN.md`](docs/BUILD-PLAN.md) (schema, routes, build order).
- Ladder for any new behaviour: HTML → CSS → Turbo → Stimulus → dependency. Stop at the first rung that works.

---

## 1. Design intent

This is the personal site of **Angga Artupas ("Artupski")**, a working web developer. It exists to
prove competence to three readers, in priority order: a hiring manager or client scanning for
credibility in under 30 seconds; a peer developer who arrived from search on one specific article;
Angga himself, publishing without friction.

Visual position: **typographic, near-monochrome, border-defined.** Content is the interface —
headlines, prose, code, screenshots. Structure is expressed with type scale, whitespace and 1px
borders, not with cards floating on gradients. One warm accent (`ember`) marks links, focus and the
current state; it is never decoration. Mono type carries metadata (dates, tags, labels, kickers) and
is the only "developer" signal we spend bytes on. Density is calm on the homepage, tighter on
listings, generous in article bodies. Premium here means restraint plus precision: consistent
optical rhythm, real contrast, nothing that moves without reason.

**We are not building (reject on sight):**

| Anti-pattern | Rule |
| --- | --- |
| Purple/indigo gradient hero | No gradient fills anywhere except an optional flat-to-transparent image scrim. Hero background = page background. |
| Glassmorphic cards, frosted headers | No `backdrop-filter` anywhere. Surfaces are opaque. |
| Three floating feature cards under a big headline | Homepage sections are content, not decorative card triptychs. |
| Blob / mesh / grid / noise background art | No large decorative backgrounds. Max background art: nothing. |
| Over-rounded everything | Container radius ceiling 10px; pills only for badges and tag pills. |
| Glow, colored shadows, inner highlights, neon borders | Two shadow levels exist, both neutral, both for overlays only. |
| Scroll-reveal on every section, parallax, animated counters, cursor followers, marquees | See §7. |
| Emoji as UI iconography, hand-drawn underlines, sticker AI illustrations | Not used. |
| "Available for work ✨" pill with pulsing dot | Availability may be stated as plain text or a static badge. No pulse. |
| Hero orbiting tech-logo cloud | Tech expertise is a labelled list, not floating logos. |

---

## 2. Tokens

**Source of truth:** CSS custom properties declared once on `:root` (light) and overridden on `.dark`.
Tailwind consumes them via `@theme inline` so utilities resolve to the live variable —
`bg-bg`, `text-fg-muted`, `border-line` swap automatically with the theme.
**Rule: no raw hex in Blade or in utility classes. No `dark:` variant for anything token-backed.**
`dark:` is permitted only for the three exceptions listed in §2.7.

### 2.1 Typefaces

Two families, self-hosted, no third-party font CDN.

| Role | Face | Files | Usage |
| --- | --- | --- | --- |
| `--font-sans` | **Inter** (variable) | `InterVariable.woff2` — latin + latin-ext, wght 100–900 | Everything: UI, headings, prose |
| `--font-mono` | **JetBrains Mono** | `JetBrainsMono-Regular.woff2`, `JetBrainsMono-Bold.woff2` — latin | Code, metadata, kickers, labels, tags |

```css
--font-sans: "Inter var", "Inter Fallback", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
--font-mono: "JetBrains Mono", ui-monospace, "SF Mono", "Cascadia Mono", Menlo, monospace;
```

**Loading strategy — LCP and CLS protection:**

1. Files live in `resources/fonts/`, emitted by Vite with a content hash, served same-origin from `/build/assets/`.
2. `font-display: swap` on every `@font-face`. Never `block`, never `optional`.
3. `<link rel="preload" as="font" type="font/woff2" crossorigin>` for **`InterVariable.woff2` only**. Mono is not preloaded — it never renders an LCP element.
4. Subset to latin + latin-ext. Reject any font file over 120 KB.
5. Metric-matched fallback so the swap does not shift layout:

```css
@font-face {
  font-family: "Inter Fallback";
  src: local("Arial");
  ascent-override: 90%; descent-override: 22%; line-gap-override: 0%; size-adjust: 107%;
}
```

6. No icon font. Icons are inline SVG via `<x-icon>`, sized 16 or 20px, stroked in `currentColor`, `aria-hidden="true"` unless the icon is the only label.

`DECISION:` two families, no display serif — a third face buys nothing and costs a request plus CLS risk.
`DECISION:` static mono, not variable — only 400 and 700 are used; two subsets are smaller than one variable file.

### 2.2 Type scale

Base 16px = `1rem`. `rem` only, never `px`, for type. Fluid `clamp()` on display sizes only.

| Token | Size | Line-height | Tracking | Used for |
| --- | --- | --- | --- | --- |
| `--text-2xs` | `0.6875rem` / 11px | `1.4` | `0.06em` uppercase | mono eyebrow/kicker labels only |
| `--text-xs` | `0.75rem` / 12px | `1.5` | `0.01em` | metadata, tag pills, captions, footer |
| `--text-sm` | `0.875rem` / 14px | `1.55` | `0` | UI text, nav, buttons, card meta, code |
| `--text-base` | `1rem` / 16px | `1.6` | `0` | default UI body; **form inputs never below this** |
| `--text-lg` | `1.125rem` / 18px | `1.6` | `0` | card titles, lead-in text |
| `--text-xl` | `1.25rem` / 20px | `1.45` | `-0.01em` | `h3`, section sub-headings |
| `--text-2xl` | `1.5rem` / 24px | `1.3` | `-0.015em` | `h2` |
| `--text-3xl` | `clamp(1.75rem, 1.5rem + 1.2vw, 2.25rem)` | `1.2` | `-0.02em` | page `h1` on listings and static pages |
| `--text-4xl` | `clamp(2.125rem, 1.7rem + 2.2vw, 3rem)` | `1.1` | `-0.025em` | article and project `h1` |
| `--text-5xl` | `clamp(2.5rem, 1.9rem + 3vw, 3.75rem)` | `1.05` | `-0.03em` | homepage hero `h1` only |

- Prose body overrides to `1.0625rem` / 17px at line-height `1.75` — the only permitted non-scale size.
- Weights in use: `400`, `500` (UI emphasis, current nav item), `600` (`h2`–`h4`, buttons), `700` (`h1`, mono bold). No `800`, no `900`.
- `text-wrap: balance` on `h1`/`h2`; `text-wrap: pretty` on prose paragraphs. Both are progressive enhancements.

### 2.3 Spacing scale

4px base. Only these steps exist.

```
--space-0:0  --space-1:4  --space-2:8  --space-3:12  --space-4:16  --space-5:20
--space-6:24 --space-8:32 --space-10:40 --space-12:48 --space-14:56
--space-16:64 --space-20:80 --space-24:96 --space-32:128     /* px */
```

- Section vertical rhythm: `56px` below 768, `80px` at ≥768, `96px` at ≥1024.
- Page gutter: `16px` below 768, `24px` at ≥768, `32px` at ≥1024.
- Gaps inside a component: `8 / 12 / 16 / 24` only.

### 2.4 Radius scale

| Token | Value | Applies to |
| --- | --- | --- |
| `--radius-none` | `0` | table cells, dividers, prose `hr` |
| `--radius-sm` | `4px` | checkbox, swatches, inline `code` |
| `--radius-md` | `6px` | button, input, select, textarea, tag link, alert |
| `--radius-lg` | `10px` | card, code block, figure/image, mobile nav panel |
| `--radius-full` | `9999px` | badge, tag pill, avatar |

Hard ceiling `10px` on any block container. No `2xl`/`3xl` radii — that is the over-rounded failure mode from §50.

### 2.5 Borders and elevation

Separation is a 1px border plus a background shift. **Cards carry no shadow, at rest or on hover.**

```css
--line:        #E7E5E4;  /* light hairline  */   --line-strong:  #D6D3D1;
--line-control: #78716C; /* one value, both themes: 4.8:1 on light bg, 4.1:1 on dark bg */
--ring-width: 2px;  --ring-offset: 2px;
--shadow-overlay:      0 8px 24px -8px rgb(0 0 0 / .18), 0 2px 6px -2px rgb(0 0 0 / .10);
--shadow-overlay-dark: 0 8px 24px -8px rgb(0 0 0 / .60), 0 2px 6px -2px rgb(0 0 0 / .40);
```

- `--shadow-overlay` is permitted on exactly three things: mobile nav panel, lightbox chrome, sticky header once scrolled. Nothing else casts a shadow.
- No colored shadows, no glow, no inset highlight, no `backdrop-filter`.
- `--line-control` is deliberately one shared value so form controls never fall below the 3:1 non-text contrast floor in either theme.

### 2.6 Color ramp — full, both themes

Warm neutral (stone) base, one ember accent, three semantics. No blue-grey. No second accent.

| Token | Light | Dark | Role |
| --- | --- | --- | --- |
| `--bg` | `#FFFFFF` | `#0C0A09` | page background |
| `--bg-subtle` | `#FAFAF9` | `#131110` | alternating section band, code block, footer |
| `--bg-muted` | `#F5F5F4` | `#1C1917` | inline code, table header, card hover fill, disabled fill |
| `--fg` | `#1C1917` | `#FAFAF9` | headings, body, primary button label |
| `--fg-muted` | `#57534E` | `#A8A29E` | secondary text, excerpts, card descriptions |
| `--fg-subtle` | `#6E6762` | `#8B837D` | metadata, captions, mono kickers, placeholder |
| `--line` | `#E7E5E4` | `#292524` | hairline dividers, card border at rest |
| `--line-strong` | `#D6D3D1` | `#3B3735` | card border on hover, blockquote rule, table rules |
| `--line-control` | `#78716C` | `#78716C` | input/checkbox/select border (non-text, ≥3:1 both themes) |
| `--accent` | `#B4400F` | `#E8703A` | links, focus ring, current nav marker, active tab |
| `--accent-strong` | `#8F320B` | `#F08A57` | link hover, accent text on `--accent-soft` |
| `--accent-soft` | `#FBEFE8` | `#2A1710` | tag hover fill, active filter fill, `::selection` |
| `--danger` | `#B42318` | `#F98F85` | form error text, destructive label |
| `--danger-soft` | `#FEF3F2` | `#2A1412` | error alert / invalid field fill |
| `--success` | `#15803D` | `#5BD68F` | flash success text |
| `--success-soft` | `#F0FDF4` | `#0E1F15` | success alert fill |
| `--warn` | `#92400E` | `#EABF6B` | warning text, draft/scheduled badge |
| `--warn-soft` | `#FEF7EC` | `#241A08` | warning alert fill |

Fixed, theme-independent: `--overlay: rgb(12 10 9 / .72)` for the lightbox backdrop.

`DECISION:` primary button is **inverted neutral** (`--fg` fill, `--bg` label), not accent-filled. Accent stays reserved for links, focus, and current-state so it never becomes decoration, and CTA contrast is maximal in both themes.
`DECISION:` code blocks follow the active theme (`--bg-subtle` fill) rather than being permanently dark. One fewer palette to maintain and no contrast surprise in light mode.
`DECISION:` no syntax-highlighting library in v1 (§72). Code blocks are mono + a language label. If highlighting is added later it must be build-time/server-side and must introduce its own token block here.

### 2.7 Verified contrast — key pairs

Floor: **4.5:1 body text, 3:1 large text (≥24px or ≥19px bold) and non-text UI boundaries.**

| Pair | Light | Dark | Status |
| --- | --- | --- | --- |
| `--fg` on `--bg` | 17.5:1 | 18.9:1 | AAA |
| `--fg` on `--bg-muted` | 16.4:1 | 16.7:1 | AAA |
| `--fg-muted` on `--bg` | 7.6:1 | 7.8:1 | AAA |
| `--fg-muted` on `--bg-muted` | 7.1:1 | 6.9:1 | AAA |
| `--fg-subtle` on `--bg` | 5.6:1 | 5.3:1 | AA |
| `--fg-subtle` on `--bg-muted` | 5.1:1 | 4.7:1 | AA (floor case — do not lighten further) |
| `--accent` on `--bg` | 5.7:1 | 6.4:1 | AA |
| `--accent` on `--bg-subtle` | 5.5:1 | 5.9:1 | AA |
| `--accent-strong` on `--accent-soft` | 5.1:1 | 5.5:1 | AA |
| `--bg` label on `--fg` fill (primary button) | 17.5:1 | 18.9:1 | AAA |
| `--danger` on `--bg` | 6.6:1 | 8.7:1 | AA |
| `--success` on `--bg` | 5.0:1 | 10.8:1 | AA |
| `--line-control` vs `--bg` | 4.8:1 | 4.1:1 | passes 3:1 non-text |
| `--accent` focus ring vs `--bg` / `--bg-muted` | 5.7 / 5.2:1 | 6.4 / 5.7:1 | passes 3:1 non-text |
| `--line` vs `--bg` | 1.2:1 | 1.3:1 | decorative only — never the sole state carrier |

Any new color pair added later must be recorded in this table with a measured ratio before it ships.

### 2.8 Tailwind mapping

```css
/* app.css */
@import "tailwindcss";
@theme inline {
  --color-bg: var(--bg);              --color-bg-subtle: var(--bg-subtle);
  --color-bg-muted: var(--bg-muted);
  --color-fg: var(--fg);              --color-fg-muted: var(--fg-muted);
  --color-fg-subtle: var(--fg-subtle);
  --color-line: var(--line);          --color-line-strong: var(--line-strong);
  --color-line-control: var(--line-control);
  --color-accent: var(--accent);      --color-accent-strong: var(--accent-strong);
  --color-accent-soft: var(--accent-soft);
  --color-danger: var(--danger);      --color-success: var(--success);
  --color-warn: var(--warn);
  --font-sans: var(--font-sans);      --font-mono: var(--font-mono);
  --radius-md: 6px;                   --radius-lg: 10px;
}
@custom-variant dark (&:where(.dark, .dark *));
```

Utilities therefore read `bg-bg`, `bg-bg-subtle`, `text-fg-muted`, `border-line`, `text-accent`, `ring-accent`.

**The only three places `dark:` is allowed:**

1. Swapping a raster asset that cannot be recolored — the two logo/portrait variants (`dark:hidden` / `hidden dark:block`).
2. `--shadow-overlay-dark` application, if the shadow cannot be expressed as a single token.
3. Media treatment on third-party embed posters (e.g. a small `brightness`/`opacity` trim on a YouTube thumbnail).

Also set `<meta name="theme-color">` per theme and `color-scheme: light dark` on `:root` so native form controls, scrollbars and the URL bar follow the theme.

---

## 3. Layout system

### 3.1 Breakpoints

Mobile-first. Min-width queries only. Test set is fixed by §54.

| Name | Min-width | Device intent | Must-verify |
| --- | --- | --- | --- |
| *(base)* | 0 | 320 small Android — the hard floor | no horizontal overflow at 320 |
| `sm` | 480px | 375 / 390 / 430 phones | 2-col tag/meta rows allowed |
| `md` | 768px | tablet portrait | desktop nav appears; 2-col listings |
| `lg` | 1024px | tablet landscape / small laptop | 3-col project grid; article gains right rail |
| `xl` | 1280px | laptop | container caps |
| `2xl` | 1536px | 1440+ desktop | no further layout change; whitespace only grows |

`DECISION:` `sm` is 480px, not Tailwind's default 640px. 375–430 is the dominant real traffic band and it needs its own step between 320 and tablet.
`DECISION:` desktop nav switches on at `md` (768). Below that, the hamburger. No intermediate condensed nav.

### 3.2 Containers

Single `<x-container>` with a `size` prop. Each is `mx-auto w-full` plus the §2.3 gutter.

| `size` | Max-width | Used by |
| --- | --- | --- |
| `prose` | `72ch` (≈43rem) | article and case-study body column |
| `narrow` | `40rem` / 640px | contact, now, uses, 404, single-column static pages |
| `default` | `72rem` / 1152px | homepage sections, listings, about, resume, header, footer |
| `wide` | `84rem` / 1344px | full-bleed project hero media, gallery grids |

- Nesting a container inside a container is forbidden. Layouts provide the container; components never do.
- Full-bleed elements inside prose (wide code block, gallery, table) break out with a `.prose-bleed` utility, capped at `wide`, and re-enter prose measure after.

### 3.3 Article measure

- Body copy measure: **`72ch`**, which at 17px Inter lands at 68–75 characters per line. Hard ceiling `75ch`.
- Line-height `1.75`; paragraph spacing `1em`; `h2` gets `2em` top / `0.75em` bottom; `h3` `1.5em` / `0.5em`.
- Mobile measure is gutter-bound, never `width: 100vw` — `100vw` on iOS includes the scrollbar and causes 1–2px overflow.
- Article right rail (table of contents, meta) appears at `lg` only, `14rem` wide, `position: sticky; top: 5rem`, `max-height: calc(100dvh - 7rem); overflow-y: auto`. Below `lg` a collapsed `<details>` ToC sits above the body. Per §55 the rail carries ToC and meta only — no ads, no widget stack, no newsletter box mid-article.

### 3.4 Grids

| Listing | base (320) | `sm` 480 | `md` 768 | `lg` 1024+ |
| --- | --- | --- | --- | --- |
| Projects index | 1 col | 1 col | 2 col | 3 col |
| Featured project (index row 1) | 1 col | 1 col | spans 2 | spans 2 (taller media) |
| Homepage selected projects (max 3) | 1 col | 1 col | 2 col | 3 col |
| Blog index | 1 col list | 1 col list | 1 col list | 1 col list + `18rem` filter rail |
| Homepage latest articles (max 3) | 1 col | 1 col | 1 col | 1 col |
| Project gallery | 1 col | 2 col | 2 col | 3 col |
| Uses / tech list | 1 col | 1 col | 2 col | 2 col |

- Gaps: `16px` base, `24px` from `md`, `32px` from `lg`.
- `DECISION:` blog index is a **single-column bordered list** (title, dek, mono meta, optional 96×96 thumb), not a card grid. Titles and deks are the scanning surface for a reading-first site; a 3-up grid truncates both.
- Use `grid-template-columns: repeat(auto-fill, minmax(...))` only where the count is unbounded; use explicit column counts where the count is fixed (homepage rows) so an orphan card cannot stretch full width.
- Cards in a row are equal height via grid stretch, with the CTA/meta row pushed down by `margin-top: auto`.

### 3.5 Shell

- Header: `sticky top-0 z-30`, `h-14` base / `h-16` from `md`, `bg-bg`, bottom `1px --line`, opaque. Optional: gain `--shadow-overlay` once `scrollY > 8`. No blur.
- Skip link is the first focusable element in `<body>`; visually hidden until focused, then pinned top-left above the header.
- Footer: `bg-bg-subtle`, top `1px --line`, 1 col base → 3 col at `md` (identity / nav / social+RSS), plus a bottom mono line with copyright and last-build year.
- Vertical scrollbar is always reserved (`scrollbar-gutter: stable`) so Turbo navigation between short and long pages does not shift layout horizontally.
- `min-height: 100dvh` on the shell wrapper (not `100vh`) so mobile browser chrome does not clip the footer.

---

## 4. Component inventory

18 components. `focus-visible` means the §9.3 ring — no component may opt out. `hover` effects are
suppressed under `@media (hover: none)` so touch devices do not latch a hover state.
Every clickable target is ≥44×44px effective (padding counts; a 20px icon in a 12px pad qualifies).

| Component | Purpose | Required states | Overflow / long-content behavior |
| --- | --- | --- | --- |
| `x-button` | one action. Variants: `primary` (`--fg` fill / `--bg` label), `secondary` (transparent + `--line-strong` border), `ghost` (text only), `danger`. Sizes `sm` 32px, `md` 40px, `lg` 48px | default, hover (primary → `--fg-muted` fill; secondary → `--bg-muted` fill + `--line-strong` border), focus-visible, active (`translateY(1px)`), disabled (`--bg-muted` fill, `--fg-subtle` label, `cursor: not-allowed`, `aria-disabled`), loading (inline spinner replaces icon slot, label persists, `aria-busy="true"`, pointer-events off) | label never wraps at ≤3 words; longer labels wrap to 2 lines and the button grows — never `overflow: hidden`. Full-width variant below `sm` for form submits |
| `x-badge` | static status: Draft, Scheduled, Archived, Featured, Case Study | default only (never interactive) | `--radius-full`, `--text-xs`, mono, uppercase; text + a shape/glyph difference so status is not color-only; truncates never — text is a fixed vocabulary |
| `x-card` | generic bordered surface | default, hover (`--bg-muted` fill + `--line-strong` border), focus-within (ring on the card when its link is focused) | `min-width: 0` on every flex/grid child; `overflow-wrap: anywhere` on titles. No shadow in any state |
| `x-container` | width + gutter only | n/a | never nested; see §3.2 |
| `x-heading` | `h1`–`h4` with scale + optional mono eyebrow and anchor link | default, hover (anchor `#` appears at `--fg-subtle`), focus-visible on anchor | `text-wrap: balance`; `overflow-wrap: anywhere` guards unbroken slugs/URLs in titles |
| `x-prose` | rich-content wrapper — the only place bare HTML is styled | default; nested states for links, code, tables, figures, lists, blockquote, `hr` | tables → `overflow-x: auto` wrapper with `tabindex="0"` + accessible name; `pre` → §code-block; images → `max-width:100%; height:auto`; long URLs → `overflow-wrap: anywhere` |
| `x-project-card` | one project in a grid | default, hover (border + fill shift, media `scale(1.02)`), focus-visible (ring on card), empty-media (token initials block on `--bg-muted`, never a broken img) | title clamps to 2 lines, description to 3 (`line-clamp`); max 3 tech tags then `+N`; media `aspect-ratio: 16/9`, `object-fit: cover` |
| `x-post-card` | one post in the blog list | default, hover (title → `--accent`, row fill `--bg-subtle`), focus-visible, no-thumb (meta+text reflow, no placeholder box) | title clamps to 2 lines, dek to 2; mono meta row wraps rather than truncating; thumb fixed 96×96 (`sm`+), hidden below `sm` |
| `x-breadcrumbs` | position on detail pages | default, hover (underline), focus-visible, current (`aria-current="page"`, not a link) | single row, `overflow-x: auto`, no scrollbar, last crumb truncates with ellipsis; `<nav aria-label="Breadcrumb"> <ol>` |
| `x-nav-desktop` | primary nav ≥`md` | default, hover (`--fg`), focus-visible, current (`aria-current="page"` + `--accent` 2px bottom rule + weight 500), section-active (child route highlights its parent) | fixed 8-item vocabulary; if items exceed the row, drop `/uses` and `/now` into the mobile pattern rather than wrapping |
| `x-nav-mobile` | hamburger + panel <`md` | closed, open, hover, focus-visible, current | Stimulus. `<button aria-expanded aria-controls>`; panel is a full-height right sheet, `--shadow-overlay`, `bg-bg`, own scroll container, `overscroll-behavior: contain`; body scroll locked while open; `Escape` closes and returns focus to the toggle; focus trapped inside; closes on Turbo `turbo:before-visit`; long lists scroll inside the panel |
| `x-theme-toggle` | light / dark / system | current-value shown, hover, focus-visible, all three values reachable | Stimulus. 3-state cycling `<button>` with `aria-label` naming the *next* state and a `<span class="sr-only" aria-live="polite">` announcing the applied one. Icon-only, 40×40. Never hidden inside the mobile panel only — present in both navs |
| `x-tag-pill` | tag/category link | default, hover (`--accent-soft` fill, `--accent-strong` text), focus-visible, active/selected (`--accent-soft` fill + `--accent` 1px border + `aria-current="page"`), disabled/zero-count (rendered as plain text, not a link) | `--radius-full`, `--text-xs`, mono; filter rows wrap to multiple lines — never a horizontal scroll strip on mobile; long tag names `overflow-wrap: anywhere` |
| `x-code-block` | fenced code from rich content | default, hover (copy button fades in), focus-visible on copy button, copied (label swaps to "Copied", `aria-live="polite"`, reverts after 2s), copy-failed (label "Copy failed", falls back to selecting the text) | `overflow-x: auto`, `white-space: pre`, `tab-size: 2`, `-webkit-overflow-scrolling: touch`; wrapper `tabindex="0"` + `role="region"` + `aria-label` so keyboard users can scroll it; **never** `word-wrap` code; optional language label top-right in mono `--text-2xs`; copy button is Stimulus, hidden from print, and absent when JS has not booted (progressive enhancement) |
| `x-figure` | image + optional caption | default, hover (`cursor: zoom-in` only when a lightbox exists), focus-visible, loading (`--bg-muted` block at the declared aspect ratio), error/missing (caption-only fallback, no broken-icon box) | always explicit `width`/`height` or `aspect-ratio` to reserve space; `loading="lazy"` + `decoding="async"` **except** the LCP image; `sizes` set to the real rendered width; caption `--text-xs`, `--fg-subtle`, centered under the media; lightbox = `<dialog>` + tiny Stimulus controller, `Escape` closes, focus returns to the trigger |
| `x-pagination` | paged listings | default, hover, focus-visible, current page (`aria-current="page"`, `--bg-muted` fill, not a link), disabled prev/next (`<span>`, not a disabled link) | `<nav aria-label="Pagination">`; below `sm` render only Prev / "Page 2 of 9" / Next; from `sm` render windowed numbers with `…`; never wraps to two rows |
| `x-form-field` | label + control + hint + error | default, hover, focus-visible, filled, disabled, readonly, invalid (`aria-invalid="true"`, `--danger` 1px border, `--danger-soft` fill, message wired via `aria-describedby`), required (`required` attr + visible "required" text — never a bare asterisk) | label always visible above the control, never a placeholder-as-label; hint and error stack below and push layout (no absolute positioning that overlaps the next field); `textarea` `min-height: 8rem`, vertical resize only; input `font-size: 1rem` minimum to stop iOS zoom |
| `x-alert` | flash / inline form summary | success, error, warning, info; dismissible variant adds hover + focus-visible on the close button | `role="status"` for success/info, `role="alert"` for error; icon + colored text + colored left rule — never color alone; long text wraps, container grows; error summary lists field links that jump focus to the offending control |

**Not built in v1:** modal (other than the lightbox `<dialog>`), tooltip, dropdown menu, accordion (use native `<details>`), tabs, carousel, toast stack, avatar group, skeleton shimmer.
`DECISION:` native `<details>`/`<summary>` covers the mobile ToC and any FAQ. No accordion component, no JS.

---

## 5. Per-page layout intent

"First viewport" = 320×568 with the header visible. If a page's job is not readable there, the page fails.

### `/` Home — container `default`
Section order is fixed by §8: hero → short intro → selected projects → what I build → experience → latest articles → tech/expertise → CTA → footer.
- **First viewport must deliver:** name, role, one-line positioning, primary CTA (View Projects), secondary CTA (Read Blog). Left-aligned, no portrait above the fold, no image required — text is the LCP element and paints instantly.
- Hero is type only on `--bg`. No panel, no gradient, no border, no illustration.
- Selected projects: **max 3**, from `is_featured`, ordered by `sort_order`. Anything more belongs on `/projects`.
- "What I build" and "Tech/expertise": labelled text lists, 2 columns from `md`. Not icon cards, not logo walls, not progress bars or percentage skill meters.
- Experience: compact reverse-chronological rows (role · org · mono year range). Not a decorated timeline with connector dots.
- Latest articles: **max 3**, title + date + reading time, single column, then one text link to `/blog`.
- CTA: one heading, one line, one primary button. No full-bleed colored band.
- Total ≤ 8 sections; §8 forbids an excessively long homepage. Alternate `--bg` / `--bg-subtle` bands sparingly — at most two subtle bands on the whole page.

### `/about` — container `default`
- **First viewport:** `h1` plus the opening paragraph that states what he does and for whom.
- Two columns from `lg`: prose (measure-capped) left, sticky sidecard right holding portrait, location, availability, and links to `/resume`, `/now`, `/uses`.
- One portrait, real photo, explicit dimensions, `loading="lazy"`, radius `lg`. Not a circle avatar, not a duotone treatment.
- Optional deeper sections: background, how I work, outside work. Prose measure throughout.

### `/projects` index — container `default`
- **First viewport:** `h1` "Projects", one clarifying line, and the first card's media edge visible so it is obvious this is a gallery.
- Optional filter row of `x-tag-pill` (tech/type) below the heading, wrapping. Filtering is a plain GET link set, not JS.
- Grid per §3.4. Featured project spans 2 columns from `md` with taller media.
- Card content: media, title, one-line summary, ≤3 tech tags, mono year. Card is one link; nested interactive elements are not allowed.

### `/projects/{slug}` case study — container `wide` hero → `prose` body
Fixed narrative order: hero → at-a-glance → problem → approach → build → outcome → gallery → related content → next/prev.
- **First viewport:** project name, one-line what-it-is, and the at-a-glance strip (role, year, stack, status, live/repo links). The reader must know what it is and Angga's role without scrolling.
- Hero media is the LCP: `wide`, `aspect-ratio: 16/9`, **not lazy-loaded**, `fetchpriority="high"`, explicit `width`/`height`. If a project has no media, the hero collapses to type — no placeholder box.
- At-a-glance is a definition list (`<dl>`), mono values, 1 col base → 2 col `sm` → 4 col `md`. Not stat cards, not big animated numbers.
- Body is prose at `72ch`; gallery and wide media use `.prose-bleed`.
- Outcome states concrete results in plain text. If no metrics exist, describe the result — no invented numbers.
- Related content: linked posts (§93), then prev/next project.

### `/blog` index — container `default`
- **First viewport:** `h1` "Blog", one line of scope, and the first two post titles legible.
- Single-column bordered list (§3.4). Each row: title (`--text-lg`, 2-line clamp), dek (2-line clamp), mono meta = date · reading time · category.
- From `lg`, an `18rem` right rail with categories and top tags. Below `lg` these move to the page bottom, not above the list — the list is the point.
- Search input, when present, sits directly under `h1`, full width, submits by GET.
- Pagination at the bottom. No infinite scroll (§84).

### `/blog/{slug}` post — container `prose`
- **First viewport:** breadcrumbs, `h1`, and the mono meta line (date · reading time · category). Featured image is *below* the title, never above it — the headline is the LCP and it paints before any image.
- Order: breadcrumbs → `h1` → meta → featured image (optional) → ToC (`<details>` below `lg`, sticky rail at `lg`) → body → tags → author card → related posts → prev/next.
- If a featured image exists it is the LCP: explicit dimensions, `fetchpriority="high"`, not lazy.
- Reading experience per §55: `72ch`, line-height `1.75`, generous heading spacing, styled code blocks, captioned images, scrollable tables, bordered blockquotes. No sidebar widget stack, no share bar that follows the scroll, no reading-progress bar.
- Author card: small portrait, one line, links. One card, at the end.

### `/contact` — container `narrow`
- **First viewport:** `h1`, one line on what to expect (and response time), and the first field visible.
- Single-column form: name, email, subject, message, honeypot, CSRF. Labels above controls. Submit is full-width below `sm`.
- Direct alternatives (email, social) sit below the form, not competing with it.
- Success renders as a full replacement panel with `role="status"`, not a toast. Errors render an `x-alert` summary at the top of the form plus inline field errors, and focus moves to the summary.

### `/now` — container `narrow`
- **First viewport:** `h1` "Now", the mono "Last updated {date}" line, and the first item. The date is the credibility signal — it is never hidden.
- 3–5 short blocks (working on / learning / reading / life). Prose, `h2` per block. No cards, no icons.

### `/uses` — container `default`
- **First viewport:** `h1` "Uses", one line of framing, and the first category heading.
- Grouped lists: hardware, editor & terminal, dev tools, services, desk. `h2` per group, `dl` or `ul` inside, 2 columns from `sm`. Item name in `--fg` plus a short mono/plain note.
- Affiliate or external links get `rel="nofollow noopener"` and are visually identical to other links.

### `/resume` — container `default`
- **First viewport:** name, role, contact row, and a "Download PDF" button if one exists.
- Sections: summary, experience, skills, education, selected projects, links. Experience rows: role · org · mono date range · 2–4 bullets.
- Print stylesheet is required: single column, black on white, no header/footer/nav, URLs expanded after link text, no page-break inside a role block.
- `DECISION:` the PDF is an uploaded static file, not generated. Zero server-side PDF dependency; if absent, the button is simply not rendered.

### 404 (and 403 / 419 / 429 / 500 / 503) — container `narrow`
- **First viewport:** code + plain-language explanation + what to do next. Full site header and footer present — an error page is still the site.
- 404 per §61 offers: search field, "Back home" link, up to 3 popular projects, up to 3 latest posts.
- 419 says the session expired and to resubmit; 429 says slow down and when to retry; 500/503 apologise and offer home + contact and never leak a stack trace.
- No oversized "404" typographic art, no illustration, no animation.

---

## 6. Required-states matrix

Every row must be implemented and visually verified before the owning page is called done.
Empty states carry a heading, one explanatory line, and one action where one exists — never a bare "No results".

### 6.1 Empty

| Scenario | Affected pages | Required treatment |
| --- | --- | --- |
| No projects at all | `/`, `/projects` | `/projects`: bordered panel, "No projects published yet", one line, link to `/blog`. On `/`: the whole Selected Projects section is **omitted** — never an empty heading. |
| No published posts | `/`, `/blog` | `/blog`: panel, "No articles published yet", link to `/projects` and RSS. On `/`: Latest Articles section omitted. |
| No search results | `/blog?q=`, 404 search | Echo the query verbatim (escaped), "No results for “{q}”", plus: clear-search link, 5 most recent posts, categories. Keep the search input populated and focusable. |
| No results for a tag/category filter | `/blog`, `/projects` | Same panel plus a "Clear filter" link; the active filter pill stays visible with `aria-current`. |
| No related content | `/projects/{slug}`, `/blog/{slug}` | Entire Related section is omitted. Prev/next still renders if either neighbour exists. |
| No prev or no next neighbour | detail pages | Render the existing side only; the missing side is a non-interactive `<span>` with muted text, so the row does not jump. |
| No featured image | `/blog/{slug}`, `/projects/{slug}`, cards | No placeholder graphic. Post hero collapses to type; post-card reflows without a thumb; project-card shows an initials block on `--bg-muted`. |
| No tags on an item | detail pages, cards | Tag row omitted entirely. No "Uncategorized" pill. |
| Empty `/now` or `/uses` content | `/now`, `/uses` | If the page has no content, it is disabled at the route/settings level (§7) and 404s. Never ship an empty page shell. |
| No PDF resume uploaded | `/resume` | Download button not rendered. No disabled button. |

### 6.2 Loading

| Scenario | Affected pages | Required treatment |
| --- | --- | --- |
| Turbo page navigation | all | Turbo's native progress bar, restyled to 2px, `--accent`, top of viewport, `z-50`, `--turbo-progress-bar` custom property. No overlay, no spinner, no skeleton screens. |
| Form submit (contact) | `/contact` | Submit enters `loading` state: `aria-busy="true"`, spinner in the icon slot, label unchanged, double-submit blocked. Turbo Drive handles the request; no full-page overlay. |
| Lazy images below the fold | listings, article body | Space reserved by explicit dimensions or `aspect-ratio`; the reserved box is `--bg-muted`. **No shimmer/skeleton animation.** CLS from images must be 0. |
| Lightbox opening a full-size image | project gallery, article figures | The thumbnail stays visible as the backdrop fades; full image gets `decoding="async"`; a mono "Loading…" label appears only after 400ms. |
| Slow font swap | all | Metric-matched fallback (§2.1) means text is readable immediately and does not reflow. Nothing is hidden waiting on a font. |

### 6.3 Error

| Scenario | Affected pages | Required treatment |
| --- | --- | --- |
| Form validation failure | `/contact` | `x-alert` error summary at top listing each failing field as a link that focuses the control; each field gets `aria-invalid`, `--danger` border, `--danger-soft` fill, message wired by `aria-describedby`; submitted values are repopulated; focus moves to the summary. |
| 419 CSRF / session expired | any POST | Custom 419 page in site chrome: "Your session expired", instruction to go back and resubmit, link home. Never a raw Laravel exception. |
| 429 rate limited | `/contact` | Custom 429 page or an inline `role="alert"`: "Too many attempts. Try again in N minutes." Never silently swallow the submit. |
| 500 / 503 | any | Custom page in site chrome, plain apology, home + contact links, zero stack trace, zero debug data. 503 mentions maintenance. |
| 403 | signed/preview URLs | Custom page: "You do not have access to this page", link home. |
| Broken or missing image at runtime | anywhere | `onerror` is not used. Space is pre-reserved; a failed image shows the `--bg-muted` box with its `alt` text visible inside at `--text-xs`. Never a browser broken-image icon in a card grid. |
| Embed provider not whitelisted | article body | Renders as a plain external link with the URL as its label, inside a bordered note — never an empty iframe (§73). |
| JS disabled or failed to boot | all | Theme falls back to `prefers-color-scheme`; mobile nav degrades to a `<details>`-based or always-visible link list; copy buttons and lightbox are absent, not inert. No page depends on JS to read its content. |

### 6.4 Long content

| Scenario | Affected pages | Required treatment |
| --- | --- | --- |
| Very long post/project title | cards, detail `h1`, breadcrumbs, `<title>` | `h1` wraps freely with `overflow-wrap: anywhere`; cards clamp to 2 lines; last breadcrumb truncates with ellipsis and keeps its full text as the accessible name. Never truncate an `h1`. |
| Long unbroken string — URL, hash, package name | prose, cards, tables | `overflow-wrap: anywhere` on all text containers; `min-width: 0` on flex/grid children so a long child cannot force the row wider. |
| Wide code block | article, case study | Horizontal scroll on the block only; the page never widens. Wrapper is focusable with an accessible name. Verified at 320px. |
| Wide table | article body | `overflow-x: auto` wrapper, `tabindex="0"`, `role="region"`, `aria-label`; header row keeps its own background; the table never sets a fixed px width. |
| Very long article, 40+ headings | `/blog/{slug}` | Sticky ToC rail scrolls internally (`max-height` + `overflow-y: auto`); mobile `<details>` ToC is collapsed by default. |
| Many tags on one item | detail, cards | Detail pages show all tags, wrapping. Cards show a maximum of 3, then `+N` as plain text. |
| 20+ pages of pagination | `/blog`, `/projects` | Windowed pagination with `…`; below `sm` the compact Prev / "Page N of M" / Next form. Never a 20-number row. |
| Extremely long single word in a heading | all | `hyphens: auto` plus `overflow-wrap: anywhere`. Verified at 320px. |
| Deeply nested prose lists | article body | Styled to 3 levels; deeper levels inherit level 3. Left indent capped so nested content never leaves the measure. |
| Very long form input value | `/contact` | `textarea` grows to its `min-height` then scrolls; single-line inputs scroll internally. No layout shift. |

---

## 7. Motion

Motion exists to explain a change of state. If a reader would not notice its absence, it should not exist.

### 7.1 Allowed

| What | Properties | Duration | Easing |
| --- | --- | --- | --- |
| Link / button / card hover and active | `color`, `background-color`, `border-color`, `opacity` | `120ms` | `ease-out` |
| Focus ring appearing | `box-shadow`, `outline-color` | `0ms` — instant | none |
| Mobile nav panel in/out | `transform: translateX`, backdrop `opacity` | `200ms` in / `160ms` out | `cubic-bezier(.32,.72,0,1)` |
| Theme toggle icon swap | `opacity`, `transform: rotate` ≤15° | `150ms` | `ease-out` |
| Card media zoom on hover | `transform: scale(1.02)` max | `200ms` | `ease-out` |
| Lightbox open/close | `opacity`, `transform: scale(.98→1)` | `180ms` | `ease-out` |
| Copy-button label swap | `opacity` | `120ms` | `ease-out` |
| `<details>` disclosure | native, or `grid-template-rows` `0fr→1fr` | `180ms` | `ease-out` |
| Turbo progress bar | `width`, `opacity` | Turbo default | Turbo default |
| Flash message appearing | `opacity` + `translateY(-4px)` | `180ms` | `ease-out` |

**Ceilings:** duration ≤ `200ms` for anything under the finger, ≤ `300ms` absolute. Only
`transform`, `opacity`, `color`, `background-color`, `border-color`, `box-shadow`, `outline-color`,
and `grid-template-rows` may be transitioned. Never `transition: all`. Never animate `width`,
`height`, `top`, `left`, `margin`, or `filter`.

### 7.2 Forbidden outright

Scroll-triggered reveals or staggered fade-ups on sections · parallax of any kind · continuous or
infinite animation (pulse, ping, spin, bounce, gradient drift, marquee, blinking cursor) — the one
exception is the form-submit spinner while `aria-busy` is true · animated number counters ·
typewriter text · cursor followers · page-transition crossfades on Turbo visits (Turbo's default
instant swap stays) · CSS View Transitions in v1 · `scroll-behavior: smooth` globally (allowed only
on same-page anchor jumps, and it must respect reduced-motion) · any JS animation library
(GSAP, Framer, AOS, Lottie, anime.js) · autoplaying video or GIF above the fold.

`DECISION:` no scroll-reveal library and no `IntersectionObserver` reveal pattern. It delays content, hurts perceived speed, breaks on Turbo restore visits, and is the single clearest tell of a generic AI landing page (§50).

### 7.3 Reduced motion

Ship exactly this, near the end of the stylesheet:

```css
@media (prefers-reduced-motion: reduce) {
  *, *::before, *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
  html { scroll-behavior: auto !important; }
  /* State must still be legible without movement */
  [data-nav-panel] { transition: none !important; transform: none !important; }
  .card-media img, .card:hover .card-media img { transform: none !important; }
  dialog[open] { transform: none !important; }
}
```

- Transitions are neutralised, never state. A reduced-motion user still gets the panel, the hover color change, the focus ring, the "Copied" label — they just appear instantly.
- Do not use `animation: none` on the Turbo progress bar; its width change is meaningful feedback and is not decorative motion.
- Any `element.animate()` or scroll call in a Stimulus controller must check `window.matchMedia('(prefers-reduced-motion: reduce)').matches` and skip the animation, not just shorten it.
- Reduced motion is not reduced *state*: nothing may become invisible or unreachable under this query.

---

## 8. Dark mode mechanics

Three user-selectable values — `light`, `dark`, `system` — with `system` as the default (§52).

### 8.1 Strategy

- Class-based: `.dark` on `<html>`. Tailwind uses `@custom-variant dark (&:where(.dark, .dark *))` (§2.8).
- Stored preference lives in `localStorage` under key `theme` with exactly one of `light` / `dark` / `system`. `system` may also be represented by removing the key; treat a missing key as `system`.
- `DECISION:` `localStorage`, not a cookie. There is no server-rendered theme branch, so a cookie would buy nothing and would break full-page caching. The inline script (§8.2) removes the flash without server involvement.
- `<html>` also carries `data-theme="light|dark|system"` so CSS and Stimulus can read the *chosen* value (not just the resolved one) — needed to render the toggle's current state correctly.
- `color-scheme` is set on `:root` and switched with the class so native scrollbars, form controls, and `<dialog>` backdrops follow.
- Media queries are never used to *style* the theme. `prefers-color-scheme` is read once, in the resolver, only when the stored value is `system`.

### 8.2 Anti-flash — exact technique

Place this as the **first** child of `<head>`, before any `<link rel="stylesheet">` and before the Vite tags. It must be inline and synchronous — a deferred or bundled script paints light first. If a CSP is enabled, whitelist it with a nonce or hash; do not move it into an external file.

```html
<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="system">
<head>
<meta charset="utf-8">
<script>
  (function () {
    try {
      var stored = localStorage.getItem('theme');            // 'light' | 'dark' | 'system' | null
      var choice = (stored === 'light' || stored === 'dark') ? stored : 'system';
      var dark = choice === 'dark' ||
        (choice === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
      var root = document.documentElement;
      root.classList.toggle('dark', dark);
      root.dataset.theme = choice;
      root.style.colorScheme = dark ? 'dark' : 'light';
      var meta = document.querySelector('meta[name="theme-color"]');
      if (meta) meta.setAttribute('content', dark ? '#0C0A09' : '#FFFFFF');
    } catch (e) { /* private mode: fall through to prefers-color-scheme defaults */ }
  })();
</script>
<meta name="theme-color" content="#FFFFFF">
<!-- ...stylesheets, Vite tags... -->
```

Notes:
- `try/catch` is mandatory: Safari private mode throws on `localStorage`.
- Put the `<meta name="theme-color">` tag after the script; the script's `querySelector` result may be `null` on first paint, which the guard handles, and Blade re-renders the correct value server-side.
- Fallback when JS is unavailable: `:root { color-scheme: light dark; }` plus a `@media (prefers-color-scheme: dark)` block that applies the dark token values to `:root` **only when `html` lacks both `.dark` and `.light`**. Keeps a no-JS visitor on their OS preference.

### 8.3 Surviving Turbo navigation

Turbo replaces `<body>` and merges `<head>`, but **`<html>` and its attributes persist**, so the `.dark` class survives every Turbo visit automatically. Three things still need care:

1. **`theme-color` on merge.** Turbo may re-insert the server-rendered `<meta name="theme-color">`. Re-apply the resolved value on `turbo:load`.
2. **Restore visits from cache.** Turbo's preview snapshot is taken from the live DOM, so it already carries the class. Nothing to do — but never store theme state on `<body>` or on any element inside it, or it will be lost.
3. **Stimulus controller lifecycle.** The toggle controller `connect()`s on every Turbo render. It must read state from `document.documentElement.dataset.theme` rather than holding its own, and it must not re-write `localStorage` on connect.

```js
// app/javascript/controllers/theme_controller.js — sketch, not final code
static targets = ['label']
connect() { this.render() }                       // reflect current state, do not mutate it
toggle() {
  const order = ['light', 'dark', 'system']
  const next  = order[(order.indexOf(this.current) + 1) % 3]
  next === 'system' ? localStorage.removeItem('theme') : localStorage.setItem('theme', next)
  this.apply(next)                                 // same logic as the inline script
  this.render()
}
get current() { return document.documentElement.dataset.theme || 'system' }
```

- A `matchMedia('(prefers-color-scheme: dark)')` `change` listener is registered once and only takes effect while the choice is `system`. Register it on `document` lifecycle, not per Turbo render, to avoid duplicate listeners.
- The toggle announces the applied theme through a polite live region (§4). Its `aria-label` names the state it will switch *to*.
- Verify: reload on a dark-OS machine with `theme=light` stored, then Turbo-navigate three pages. No flash at any point, and the toggle still reads "Light".

---

## 9. Accessibility contract

Target: **WCAG 2.2 AA**. Accessibility is never traded for a visual effect (§53).

### 9.1 Landmarks

Exactly one of each per page, in this order inside `<body>`:

```html
<a class="skip-link" href="#main">Skip to content</a>   <!-- first focusable -->
<header>  <nav aria-label="Primary"> … </nav> </header>
<main id="main" tabindex="-1"> … </main>
<footer>  <nav aria-label="Footer"> … </nav> </footer>
```

- Additional `<nav>` elements (Breadcrumb, Pagination, Table of contents) each carry a distinct `aria-label`.
- `<section>` only when it has an accessible name (`aria-labelledby` pointing at its heading). Otherwise `<div>`.
- Article body is wrapped in `<article>`; post metadata uses `<time datetime="…">`.
- `main` takes `tabindex="-1"` so the skip link moves focus, not just the scroll position.
- Turbo: after each visit, focus is reset to `main` and the new page title is announced via a polite live region. Otherwise a screen-reader user hears nothing when the page swaps.

### 9.2 Headings

- **Exactly one `h1` per page.** The site name in the header is **not** an `h1` — it is a link inside `<header>`.
- No level skipping. Card titles inside a listing are `h2` or `h3` consistent with the page's outline, never a styled `div`.
- Rich content from the editor starts at `h2`; the renderer demotes any authored `h1` to `h2`.
- Every heading in an article body gets a stable `id` for the ToC and deep links; the anchor link is `aria-label="Link to this section"`.
- Visually hidden headings are allowed to label a landmark, using `.sr-only` (clip pattern, not `display:none`).

### 9.3 Focus-visible ring

One ring, everywhere, no exceptions:

```css
:where(a, button, input, select, textarea, summary, [tabindex], dialog, .card-link):focus-visible {
  outline: var(--ring-width) solid var(--accent);   /* 2px */
  outline-offset: var(--ring-offset);               /* 2px */
  border-radius: inherit;
}
:focus:not(:focus-visible) { outline: none; }        /* no ring on mouse click */
```

- `outline: none` without a replacement is forbidden. Removing the ring is a gate failure.
- The ring's 3:1 contrast against both `--bg` and `--bg-muted` is verified in §2.7.
- On `--bg-subtle`/`--bg-muted` surfaces the `2px` offset keeps the ring readable; where an element sits flush to a container edge, add `outline-offset: -2px` rather than dropping the ring.
- Card pattern: the card is not focusable. The title link is, and the card renders the ring via `:has(:focus-visible)` / `focus-within`. Only one focus stop per card.
- Focus order follows DOM order. No positive `tabindex` values anywhere.
- Focus is never trapped except inside the open mobile nav panel and the open lightbox `<dialog>`.

### 9.4 Labels and forms

- Every control has a programmatic label: `<label for>` (preferred), or `aria-label` for icon-only buttons (theme toggle, nav toggle, copy, lightbox close).
- **No placeholder-as-label.** Placeholders are optional examples only.
- Required fields: the `required` attribute plus the visible word "required" in the label. Never an asterisk alone.
- Hints and errors are linked with `aria-describedby`; invalid controls get `aria-invalid="true"`.
- The honeypot field is `aria-hidden="true"`, `tabindex="-1"`, off-screen via the clip pattern — never `display:none` on a field a bot-detection heuristic depends on, and never focusable.
- Search inputs use `type="search"` inside a `<form role="search">`.
- Button vs link: navigation is an `<a>`, action is a `<button>`. A `<div>` is never made clickable.

### 9.5 Images and media

| Case | Alt policy |
| --- | --- |
| Content image in an article | Descriptive `alt` written by the author; the CMS field is required for non-decorative images |
| Project hero / screenshot | Describe what the screen shows, not "screenshot" |
| Decorative or duplicated by adjacent text | `alt=""` — present and empty, never missing |
| Portrait of Angga | `alt="Angga Artupas"` |
| Logo in header | `alt="Artupski"`, or `alt=""` when adjacent text already names the site |
| Icon inside a labelled button | `aria-hidden="true"` on the SVG |
| Icon-only button | `aria-label` on the button, `aria-hidden` on the SVG |
| Figure with a caption | `alt` describes the image; the `<figcaption>` adds context; they do not duplicate each other |

- Every `<img>` has `width` and `height` (or `aspect-ratio`) to reserve space.
- No text baked into images for meaningful content.
- No autoplay. Any embedded video is click-to-play with a real `<iframe title="…">`.

### 9.6 Keyboard paths

**Mobile nav:** `Tab` reaches the toggle → `Enter`/`Space` opens → focus moves to the first link in the panel → `Tab` cycles inside the panel only → `Shift+Tab` wraps back → `Escape` closes and returns focus to the toggle → background scroll is locked while open → `aria-expanded` reflects state at all times.

**Lightbox:** `Enter` on a focused figure link opens the `<dialog>` → focus moves to the close button → `Tab` cycles between close and any prev/next → `Escape` closes (native `<dialog>` behaviour) → focus returns to the triggering figure. Backdrop click also closes. No swipe-only affordance.

**Code block:** the scroll wrapper is `tabindex="0"` with `role="region"` and an `aria-label`, so a keyboard user can scroll a wide block with arrow keys. The copy button is a real `<button>` in the tab order.

**ToC / anchors:** anchor jumps move focus to the target heading (`tabindex="-1"` on headings that are jump targets), not just the scroll position.

**Pagination and filters:** all plain links, reachable and operable with `Enter`. Nothing requires a pointer.

### 9.7 Non-negotiables

- Contrast floor 4.5:1 body, 3:1 large text and non-text boundaries (§2.7).
- **State is never conveyed by color alone.** Status badges pair color with text; the current nav item pairs accent with weight *and* an underline rule; invalid fields pair the red border with an icon *and* a message; links in prose are underlined, not merely colored.
- Touch targets ≥44×44px effective; ≥8px spacing between adjacent targets.
- Zoom to 200% at 1280px wide and to 400% at 320px: no loss of content or function, no horizontal scroll of the page.
- Content is readable and operable with JS disabled (§6.3).
- Reduced motion honoured per §7.3.
- `lang="en"` on `<html>`; any passage in another language carries its own `lang`.
- No `aria-*` attribute is added where native semantics already do the job.

---

## 10. FINISH GATE

No UI subtask may be called done until every applicable item passes. Each is objectively checkable —
verify in a browser at the §3.1 sizes, in both themes, with a keyboard. "Looks fine" is not a pass.
If an item genuinely does not apply to the subtask, state that; do not silently skip it.

### Layout and overflow

1. At **320px** width: no horizontal page scroll, no clipped text, no element wider than the viewport. Verified on every page in scope.
2. `document.documentElement.scrollWidth <= window.innerWidth` holds at 320, 375, 390, 430, 768, 1024, 1440.
3. Wide code blocks scroll horizontally **within the block**; the page does not widen and the block does not wrap code.
4. Wide tables scroll inside a focusable wrapper with an accessible name; the page does not widen.
5. Article body measure is between 60 and 75 characters per line at every breakpoint ≥768px.
6. No nested `<x-container>`; page gutters match §2.3 at all three steps.
7. Zoom to 200% at 1280px and 400% at 320px: all content and controls remain reachable, no page-level horizontal scroll.

### Content resilience

8. Every listing renders correctly with **0, 1, 2, and many** items; empty states match §6.1 and no section renders an empty heading.
9. A 120-character title and an unbroken 60-character string both render without overflow in cards, headings, breadcrumbs, and tables.
10. Missing featured image, missing tags, missing related content, and missing prev/next each render the §6.1 treatment — no broken-image icon, no placeholder graphic, no layout jump.
11. All images have `width`/`height` or `aspect-ratio`; measured CLS is `0` on every page.
12. The LCP image (project hero, post featured image) is **not** `loading="lazy"`, carries `fetchpriority="high"`, and has explicit dimensions. Below-fold images are `loading="lazy" decoding="async"`.

### States

13. Every interactive element has all its §4 states implemented: default, hover, focus-visible, active, plus disabled/loading/empty/error where applicable.
14. Every interactive element shows a **visible focus-visible ring** matching §9.3. Zero instances of `outline: none` without a replacement.
15. Contact form validation failure renders the error summary, moves focus to it, repopulates values, and marks each field with `aria-invalid` + a linked message.
16. Custom 404, 403, 419, 429, 500, 503 pages all render in full site chrome with no stack trace.
17. Turbo navigation shows the restyled 2px progress bar; no skeleton screens and no full-page spinners exist anywhere.

### Theme

18. Theme does not flash on first paint — hard reload with `theme` set opposite to the OS preference, in both directions, and observe no light frame.
19. Theme survives Turbo navigation across at least three visits, plus back/forward, plus a restore visit; the toggle still displays the correct current value.
20. All three values (`light`, `dark`, `system`) are reachable from the toggle, and `system` follows a live OS change.
21. No `dark:` utility outside the three exceptions in §2.8; no raw hex in any Blade template or utility class.
22. Every text/background pair in use appears in §2.7 and meets 4.5:1 body / 3:1 large; verified in both themes with a contrast checker.

### Accessibility

23. Exactly one `<h1>` per page; heading levels never skip; a heading-outline check produces a sensible tree.
24. Landmarks are present and unique per §9.1; skip link is the first focusable element and moves focus to `#main`.
25. Full keyboard pass on every page — mobile nav opens/traps/`Escape`-closes and restores focus; lightbox does the same; code blocks scroll with arrow keys; no positive `tabindex`; no keyboard trap outside the two allowed cases.
26. **No element relies on color alone** to convey state: badges, current nav item, invalid fields, and prose links each carry a second signal.
27. All touch targets ≥44×44px with ≥8px separation.
28. Every `<img>` has an `alt` attribute — descriptive, or explicitly empty for decorative. None missing.
29. `prefers-reduced-motion: reduce` removes all transitions and transforms per §7.3 while every state remains legible and every control remains operable.
30. Page is readable and navigable with JavaScript disabled; theme falls back to the OS preference.
31. Axe or Lighthouse accessibility audit reports **zero** violations on `/`, `/projects/{slug}`, `/blog/{slug}`, and `/contact`.

### Anti-slop

32. Zero gradient fills, zero `backdrop-filter`, zero decorative background art, zero glow or colored shadow. Shadows appear only on the three surfaces named in §2.5.
33. No radius above `10px` on any block container. No infinite or scroll-triggered animation. No JS animation library in `package.json`.
34. Homepage has ≤8 sections in the §8 order, shows ≤3 projects and ≤3 articles, and its first viewport delivers name, role, positioning line, and both CTAs at 320px.
35. Public bundle ships no admin, editor, or Filament assets; total public JS stays under 30 KB gzipped (Turbo + Stimulus + ≤4 controllers); no font file exceeds 120 KB.
36. Print stylesheet verified on `/resume` and one article: single column, no nav or footer chrome, links legible.
