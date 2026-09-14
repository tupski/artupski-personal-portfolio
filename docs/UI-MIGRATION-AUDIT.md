# UI MIGRATION AUDIT — Flux UI adoption

Scope: design-system / UI-layer refactor only. No T5–T11 work.
Branch: `main`. Baseline commit: `fdf6405`.
Audit date basis: repository state at `fdf6405` + `livewire/flux` v2.19.0 installed during this phase.

Direction source for this work: `docs/DESIGN-CONTRACT.md` (binding) + `antislop` core and
`antislop-ui` skills. Where the migration brief and the contract disagree, both are recorded in
[§9 Conflicts](#9-conflicts-and-how-they-were-resolved) with the resolution actually applied.

---

## 1. Current UI architecture

| Layer | What exists today |
| --- | --- |
| Rendering | Laravel 13 + Blade only. Server-rendered, JS-light. |
| Public CSS | `resources/css/app.css` (565 lines) — Tailwind v4 `@theme inline` + hand-written token block + `@font-face` + `.prose-custom` |
| Public JS | `resources/js/app.js` — Turbo 8 + Stimulus 3, 3 controllers (`theme`, `mobile-nav`, `copy`) |
| Build | Vite 8 + `laravel-vite-plugin` + `@tailwindcss/vite`. Two entries: `app.css`, `app.js` |
| Fonts | Self-hosted in `public/fonts/` — Inter variable (latin + latin-ext), JetBrains Mono 400/700 |
| Blade components | 25 files under `resources/views/components/` |
| Admin | Filament 5 panel `tupasadmin`, 10 resources, 3 widgets, 2 custom pages |
| Routes | `routes/web.php` is **empty** (comment only). No public page exists yet. |

Consequence worth stating plainly: **there is no public page to migrate.** The "public website UI"
in this phase is the component library plus the layout shell. That is what was refactored.

---

## 2. Current component inventory (25 files)

| Component | Bytes | External usages | Verdict |
| --- | --- | --- | --- |
| `ui/button` | 2577 | 2 (`blocks/cta`, `blocks/project-highlight`) | **Delete** → `flux:button` |
| `ui/card` | 625 | 2 (`cards/post-card`, `cards/project-card`) | **Delete** → `flux:card` |
| `ui/alert` | 2681 | 1 (`blocks/callout`) | **Delete** → `flux:callout` |
| `ui/form-field` | 3168 | 0 | **Delete** → `flux:field` + `flux:input`/`flux:textarea`/`flux:select` |
| `navigation/breadcrumbs` | 1110 | 0 | **Delete** → `flux:breadcrumbs` |
| `navigation/pagination` | 2195 | 0 | **Delete** → `flux:pagination` |
| `ui/container` | 378 | 1 (`layouts/app`) | Keep — contract §3.2 widths |
| `ui/heading` | 1175 | 0 | Keep — contract §2.2 scale + §9.2 anchor/eyebrow |
| `ui/badge` | 652 | 0 | Keep — contract status vocabulary |
| `ui/prose` | 145 | 1 (`content/rich-content`) | Keep — contract §3.3/§4 measure |
| `ui/tag-pill` | 919 | 0 | Keep — domain (tag/category states) |
| `navigation/nav-desktop` | 1004 | 1 | Keep — §4 fixed vocabulary + `aria-current` |
| `navigation/nav-mobile` | 3774 | 1 | Keep — Stimulus focus-trap sheet (§4, §9.6) |
| `navigation/theme-toggle` | 1677 | 1 | Keep — §8 three-state, `theme` localStorage key |
| `cards/project-card` | 2732 | 0 | Keep — domain; retarget surface to `flux:card` |
| `cards/post-card` | 1779 | 0 | Keep — domain; retarget surface to `flux:card` |
| `content/rich-content` | 1178 | 0 | Keep — only sanctioned `{!! !!}` site |
| `content/code-block` | 2021 | 1 (`blocks/code`) | Keep — domain (§4 copy states) |
| `content/blocks/{callout,code,cta,image,project-highlight}` | 381–1091 | via `rich-content` | Keep — domain; `callout`/`cta`/`project-highlight` retargeted |
| `media/figure` | 1317 | 1 (`blocks/image`) | Keep — domain (caption, LCP, error state) |
| `layouts/app` | 4664 | — | Keep — shell per §3.5, §8.2, §9.1 |

---

## 3. Current design tokens (`app.css`)

| Group | Tokens present | Assessment |
| --- | --- | --- |
| Typefaces | `--font-sans`, `--font-mono` | Keep. Self-hosted, contract §2.1 satisfied. |
| Type scale | `--text-2xs` … `--text-5xl` | Keep. Contract §2.2 exact. |
| Spacing | `--space-0` … `--space-32` | Keep. Contract §2.3 exact. |
| Radius | `--radius-none/sm/md/lg/full` | Keep + **extend**: `xl/2xl/3xl/4xl` capped at 10px. |
| Elevation | `--ring-width`, `--ring-offset`, `--shadow-overlay`, `--shadow-overlay-dark` | Keep. |
| Colour | `--bg`, `--bg-subtle`, `--bg-muted`, `--fg`, `--fg-muted`, `--fg-subtle`, `--line`, `--line-strong`, `--line-control`, `--accent`, `--accent-strong`, `--accent-soft`, `--danger(-soft)`, `--success(-soft)`, `--warn(-soft)` | Keep, all in use. No dead token found. |
| Tailwind mapping | `@theme inline` block, `@custom-variant dark`, breakpoints `sm 480 / md 768 / lg 1024 / xl 1280 / 2xl 1536` | Keep. Contract §2.8, §3.1. |

No unused token was found, so nothing was removed for being merely present.

---

## 4. Current Tailwind usage

- Semantic utilities resolve through the token layer: `bg-bg`, `text-fg-muted`, `border-line`, `text-accent`. No raw hex in Blade — contract §2.8 rule is currently honoured.
- Arbitrary-value utilities are used where no token exists: `rounded-[var(--radius-md)]`, `text-[var(--text-lg)]`. These keep the token as the single source, so they are acceptable but noisy; the new radius/type utilities from the `@theme` block replace most of them.
- `dark:` variants appear only in `navigation/theme-toggle.blade.php` (sun/moon swap), which is contract §2.8 exception 1. Compliant.
- `line-clamp`, `text-wrap-balance`, `overflow-wrap-anywhere` used for long-content resilience (§6.4).

---

## 5. Current Filament customisation

| Item | State | Assessment |
| --- | --- | --- |
| Panel | `AdminPanelProvider`, id/path `tupasadmin` | Fine |
| Brand | `->brandName('Artupski')` | Fine |
| Colour | `Color::Amber` | **Mismatch** — Tailwind amber (`#f59e0b` family), not contract ember `#B4400F` / `#E8703A` |
| Font | Not set — Filament default | **Mismatch** — public site is Inter, admin renders a different face |
| Dark mode | `->darkMode(true)` | Fine |
| `CustomLogin` | `extends Login`, **no `$view` override** | **Dead code** — `filament/pages/auth/custom-login.blade.php` is never rendered. Filament's default login is what users see. |
| `resources/views/filament/forms/seo-preview.blade.php` | Emoji `🖼️` as placeholder art, `bg-gradient-to-br from-amber-100 to-orange-100`, hardcoded `text-blue-700` / `text-green-700` / `gray-*` | **Violates** contract §1 (no gradient fills) and antislop R-04 (emoji as UI), R-01 (gradient without purpose), R-29 (palette discipline) |
| `ContentStatsOverview` | 6 equal stat cards, each with a decorative `descriptionIcon` | **Hierarchy failure** (antislop-ui *Stat Cards*): the actionable metric (unread messages) sits 5th. Numbers themselves are real DB counts, so R-17 is satisfied. |
| `RecentActivityWidget` | Builds `$activities` from 3 queries, then queries `Post::whereRaw('1 = 0')` and never uses the collection | **Functional defect** (C-2, R-26): the widget renders a permanently empty table. |
| `UnreadMessagesWidget` | Real query, `limit(5)`, `paginated(false)` | Acceptable; heading/columns tuned. |
| No custom Filament theme | — | Filament ships its own palette/radius/type; nothing ties it to the public brand |

---

## 6. Current duplicated patterns

| Duplication | Where | Resolution |
| --- | --- | --- |
| Button: variants × sizes × loading + inline spinner SVG | `ui/button` duplicates what `flux:button` provides | Deleted, calls retargeted |
| Card: bordered surface + hover + focus-within | `ui/card` vs `flux:card` | Deleted, calls retargeted |
| Alert: 4 types + hand-written inline SVG icons | `ui/alert` vs `flux:callout` | Deleted, call retargeted |
| Form control: label + hint + error + `aria-describedby` wiring | `ui/form-field` reimplements `flux:field`/`flux:label`/`flux:error` | Deleted, unused |
| Breadcrumb `<nav aria-label="Breadcrumb"><ol>` | `navigation/breadcrumbs` vs `flux:breadcrumbs` | Deleted, unused |
| Pagination windowed numbers + prev/next | `navigation/pagination` vs `flux:pagination` | Deleted, unused |
| Inline SVG icon markup repeated in alert, button, nav, theme-toggle, code-block | No `<x-icon>` component exists despite contract §2.1 naming one | **Partially resolved**: adopted `flux:icon` as the single icon primitive for new markup; existing hand-written SVGs in nav/theme-toggle/code-block left in place because each carries component-specific state logic and rewriting them is churn without behaviour change. Recorded as remaining work. |

---

## 7. Flux UI adoption plan

Flux v2.19.0 (`livewire/flux`, MIT) installed. Available primitives were read from
`vendor/livewire/flux/stubs/resources/views/flux/`, not from documentation.

**Free tier — actually present in vendor:** accent, aside, avatar, badge, brand, breadcrumbs,
button, callout, card, checkbox, container, description, dropdown, error, field, fieldset, flag,
footer, header, heading, icon, input, label, legend, link, main, menu, modal, navbar, navlist,
navmenu, otp, pagination, profile, progress, radio, select, separator, sidebar, skeleton, spacer,
subheading, switch, table, text, textarea, toast, toggle, tooltip.

**Pro tier only — absent from vendor:** accordion, autocomplete, calendar, charts, color picker,
combobox, command palette, composer, context menu, date picker, rich text editor, file upload,
kanban, listbox, pillbox, slider, tabs, time picker, timeline.

| Brief item | Flux tier | Action |
| --- | --- | --- |
| Button, Icon, Input, Textarea, Select, Checkbox, Radio, Switch/Toggle, Field, Label, Error, Badge, Dropdown, Modal, Tooltip, Table, Pagination, Breadcrumb, Menu, Avatar, Callout, Toast, Skeleton | Free | Adopt as the primitive layer |
| Tabs, Rich text editor, File upload, Date/Time picker, Command palette, Charts, Slider, Accordion | **Pro only** | Not adoptable. Filament already provides tabs/editor/file-upload/date-picker for admin. Public site has no need for them in T5–T11 scope. |

### Asset strategy — the decisive constraint

| Asset | Raw | gzip |
| --- | --- | --- |
| `flux/dist/flux.css` | 20,639 | 3,147 |
| `flux/dist/flux-lite.min.js` (what the free tier actually serves) | 134,281 | **33,667** |
| `livewire/livewire` `livewire.min.js` | 257,772 | **85,073** |

`@fluxScripts` expands to `app('livewire')->forceAssetInjection()` plus a `<script>` for
`flux-lite.min.js`. Using it on a public page therefore costs **+118.7 kB gzip** and forces
Livewire onto every public visit.

Contract §10 item 35 caps total public JS at **< 30 kB gzip** and requires that the public bundle
ship **no admin, editor, or Filament assets**. Adding Flux JS to the public bundle would put it at
roughly **5× the ceiling**.

**Resolution applied:** Flux is used on the public side as a **server-rendered, CSS-only primitive
layer**. `@fluxScripts` and `@fluxAppearance` are deliberately **not** included in the public
layout. Flux's interactive components (modal, dropdown, tooltip, toast, switch) are consequently
unavailable publicly, which is what the contract already required: §4 lists modal, tooltip,
dropdown, accordion, tabs, carousel and toast as "not built in v1" for the public site. The
contract's own progressive-enhancement ladder (§10 preamble: HTML → CSS → Turbo → Stimulus →
dependency) and the brief's §12 instruction to prefer server-rendered Blade both point the same way.

Filament keeps Livewire/Alpine regardless — it is an admin application and is not covered by the
public JS budget. Flux is therefore fully available inside admin-facing custom Blade.

### Retheming approach

Flux's stubs style themselves with hardcoded `zinc-*` utilities (102 × `text-zinc-800`, 78 ×
`text-zinc-400`, 59 × `bg-zinc-800`, …) and `rounded-xl`/`rounded-2xl`. Two options were considered:

1. `php artisan flux:publish` and rewrite the stubs. Rejected: high churn, and every Flux upgrade
   would re-open the diff. This is the "fragile markup override" the brief's §7 forbids.
2. Remap the theme variables Flux's utilities resolve to. Chosen: `--color-zinc-*` is redirected to
   the contract's warm-neutral stone ramp, and `--radius-xl/2xl/3xl/4xl` are capped at the contract's
   10px ceiling, in one place. Flux upgrades do not invalidate it.

Flux's accent triple is mapped onto the contract's §2.6 `DECISION` that the primary button is
*inverted neutral*, not accent-filled:

| Flux variable | Maps to | Effect |
| --- | --- | --- |
| `--color-accent` | `var(--fg)` | Primary button fill = `--fg`, label = `--bg` |
| `--color-accent-foreground` | `var(--bg)` | Button label |
| `--color-accent-content` | `var(--accent)` (ember) | Flux links/accents render ember, per contract §2.6 |

The `.dark` overrides in `flux.css` live in `@layer theme`; the remap is written unlayered so it
wins in both themes without `!important`.

---

## 8. Compatibility issues found

| Issue | Detail | Handling |
| --- | --- | --- |
| Livewire forced by `@fluxScripts` | `AssetManager::scripts()` calls `forceAssetInjection()` | Do not use `@fluxScripts` publicly |
| Dual theme keys | Flux's `@fluxAppearance` writes `localStorage['flux.appearance']`; contract §8.1 mandates `localStorage['theme']` with `light`/`dark`/`system` | Do not use `@fluxAppearance`; keep the contract's inline anti-flash script (§8.2) |
| Radius ceiling | `flux:card` default `rounded-xl` (12px), `flux:callout` `rounded-xl` — both over contract's 10px cap | `--radius-xl` capped at 10px |
| Cool palette | Flux defaults to zinc | `--color-zinc-*` → stone ramp |
| `@source "../../flux-pro/stubs"` in `flux.css` | References a path that does not exist without Pro | Harmless; Tailwind skips a missing source. Verified in build output. |
| `flux:button` emits `wire:loading.attr` | Only when `loading` is truthy and the variant is not `$js.` | Harmless without Livewire — attribute is inert |
| `@blaze` directive | Flux stubs use it for compile-time folding | Provided by Flux's own Blade integration; verified by a successful render |
| Focus ring specificity | Contract §9.3 ring is `:where(...)` (0 specificity) | Safe: it is unlayered, and unlayered CSS outranks Tailwind's `@layer utilities` |
| Tailwind version | Project has 4.3.3; Flux v2 requires ≥ 4.2 | Satisfied |

---

## 9. Conflicts and how they were resolved

Recorded as required by the brief ("prioritize the skill and document the conflict").

### C-1 — Flux Free cannot supply every primitive the brief lists

Brief §2 lists Tabs, Table, Rich text editor, File upload, Date picker among Flux priorities.
11 of those are Pro-only. **Resolved by user decision:** Free tier as the primary system;
Pro-only primitives stay as custom Blade or are served by Filament. Documented in §7.

### C-2 — Brief §2 (Flux everywhere public) vs contract §10 item 35 (< 30 kB gzip public JS)

Irreconcilable as literally written. **Resolution:** Flux adopted as a CSS-only primitive layer
publicly; the interactive half is admin-only. Rationale in §7. The brief's own §12
("prefer server-rendered Blade + progressive enhancement") and its instruction to prioritise the
skill support this reading, and the skill (`antislop`) has no rule requiring client-side components.

### C-3 — Brief §2 lists Modal/Dialog/Tooltip/Tabs/Toast/Avatar/Table as adoptable; contract §4 says "Not built in v1"

The contract's list exists to keep the public bundle and the interaction surface small.
**Resolution:** the contract wins for the public site; Flux's implementations remain available for
admin-facing custom Blade, where Filament already loads the required JS.

### C-4 — `antislop` R-37 requires design direction before UI work

Direction exists: `docs/DESIGN-CONTRACT.md` is explicit brand guidance (identity, palette, type,
radius, motion, contrast floor). R-37 is satisfied without inventing a `DESIGN.md`, and no rule
collides with the contract. Dials derived from the contract's stated intent
("calm on the homepage, tighter on listings", "nothing that moves without reason",
"premium means restraint plus precision"):

> Reading this as: personal developer portfolio for hiring managers and peer developers, in a
> typographic near-monochrome editorial language, dial **ENERGY 1 / RHYTHM 2 / MOTION 1**.

RHYTHM 2 rather than 1 because the contract already differentiates density per surface (calm home,
tight listings, generous article bodies, and up to two `--bg-subtle` bands). MOTION 1 because §7.2
forbids scroll-reveal, parallax and infinite animation outright.

### C-5 — `antislop` R-04 / R-01 vs existing Filament `seo-preview` markup

The emoji and gradient violate both. **Resolution:** skill and contract win; the markup is rewritten
with Filament tokens.

### C-6 — Contract §2.1 names `<x-icon>`; the component does not exist

**Resolution:** `flux:icon` adopted as the icon primitive (inline SVG, `currentColor`, `aria-hidden`
by default) — it satisfies §2.1's actual requirements. The contract's component name is stale and
should be updated in a later docs pass; recorded under Remaining Issues.

---

## 10. Accessibility concerns

| Concern | Status |
| --- | --- |
| `ui/alert` used `onclick="this.closest('[role]').remove()"` — inline handler, no Stimulus, removes the live region rather than hiding it | Removed with the component; `flux:callout` replaces it |
| Card as a single link (`ui/card` `href` → `<a>` wrapping everything) | Rebuilt as `flux:card` surface + one title link stretched over the card, so the card holds exactly one focus stop while remaining fully clickable |
| `ui/button` `disabled` on an `<a>` set `pointer-events-none` + `aria-disabled` but left it focusable-in-name-only | Removed with the component |
| Global focus ring (§9.3) present and unlayered | Verified intact after the Flux import |
| Reduced-motion block (§7.3) present | Keep |
| `.skip-link`, `main tabindex="-1"`, landmark order (§9.1) | Keep, unchanged |
| `theme-toggle` announces next state via `sr-only aria-live` | Keep |
| Filament `seo-preview` colour-only semantics | Rewritten |
| Contrast floor (§2.7) | Token values unchanged; the stone remap keeps Flux text on the same neutral family the contract measured |

---

## 11. Responsive concerns

- Contract §3.1 breakpoints (`sm` 480, not 640) are already set in `@theme`; Flux components inherit
  them, so no Flux component can reintroduce a 640px step.
- `flux:card` uses `p-6`/`rounded-xl` at all widths; the card media inside the project card keeps its
  own `aspect-video`. No new overflow path.
- The mobile nav panel, pagination compaction and long-content rules (§6.4) are untouched.
- Filament's own responsive behaviour is Filament's; only colours/type/radius were retargeted.

---

## 12. Bundle-size concerns

Baseline measured on this machine (`npm run build`, vite 8.2.2):

| Asset | Raw | gzip |
| --- | --- | --- |
| `app.css` | 83.21 kB | 16.49 kB |
| `app.js` | 141.22 kB | **36.90 kB** |

Design target: **< 30 kB gzip** (contract §10 item 35). Current overshoot: **+6.9 kB**.

Investigation result: the bundle is 8 modules. The two imports in `app.js` are
`@hotwired/turbo` and `@hotwired/stimulus`; Turbo is the overwhelming majority of the payload, and
Stimulus plus the three controllers add the remainder. There is no React/Vue/Alpine, no unused
import, no duplicate behaviour, and no unnecessary controller — `theme`, `mobile-nav` and `copy` are
each required by contract §8, §4 and §4 respectively. The overshoot is Turbo's floor, not waste.

The Flux adoption does **not** worsen this: no Flux JS is added publicly. Flux's contribution is CSS
only, which is why the CSS figure moves and the JS figure does not.

---

## 13. Files changed by this phase

Deleted (6): `ui/button`, `ui/card`, `ui/alert`, `ui/form-field`, `navigation/breadcrumbs`,
`navigation/pagination`.

Modified: `resources/css/app.css`, `vite.config.js`, `resources/views/components/cards/project-card.blade.php`,
`resources/views/components/cards/post-card.blade.php`,
`resources/views/components/content/blocks/callout.blade.php`,
`resources/views/components/content/blocks/cta.blade.php`,
`resources/views/components/content/blocks/project-highlight.blade.php`,
`app/Providers/Filament/AdminPanelProvider.php`, `app/Filament/Pages/Auth/CustomLogin.php`,
`app/Filament/Widgets/ContentStatsOverview.php`, `app/Filament/Widgets/RecentActivityWidget.php`,
`resources/views/filament/pages/auth/custom-login.blade.php`,
`resources/views/filament/forms/seo-preview.blade.php`, `composer.json`, `composer.lock`, `.gitignore`.

Added: `resources/css/filament/admin/theme.css`, `docs/UI-MIGRATION-AUDIT.md`.

Backend contracts (schema, migrations, models, policies, enums, services, seeders, factories, auth
logic) are untouched, per brief §14.
