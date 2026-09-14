{{-- Temporary landing page while the public routes of Task 5 are unbuilt.

     Standalone on purpose: the shared layout renders its navigation from the
     database, and every one of those destinations is still a 404, so reusing the
     chrome would ship dead links (R-24, R-26). This page claims only what is
     true (R-38): the site is being built, and the admin panel is the one part
     that already works. --}}
<!DOCTYPE html>
<html lang="en" data-theme="system">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>

    {{-- Anti-flash theme script — first child of <head>, before any stylesheet (§8.2) --}}
    <script>
        (function () {
            try {
                var stored = localStorage.getItem('theme');
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

    {{-- Preload Inter for LCP protection (§2.1) --}}
    <link rel="preload" as="font" type="font/woff2" href="/fonts/InterVariable-latin.woff2" crossorigin>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="grid min-h-dvh place-items-center bg-bg font-sans text-fg antialiased">
    {{-- The one interactive element. The page has no navigation yet, so the
         theme toggle stands alone (§8: light / dark / system). --}}
    <div class="fixed right-4 top-4">
        <x-navigation.theme-toggle />
    </div>

    <main id="main" tabindex="-1" class="w-full max-w-[40rem] px-6 text-center">
        <p class="font-mono text-[var(--text-2xs)] uppercase tracking-[0.06em] text-fg-subtle">
            {{ config('app.name') }}
        </p>

        <h1 class="mt-4 text-[var(--text-3xl)] font-bold leading-[1.2] tracking-tight text-balance">
            This site is under construction.
        </h1>

        <p class="mt-3 text-fg-muted">
            The public pages are not published yet. Content is already being prepared in the
            <a
                href="{{ route('filament.tupasadmin.auth.login') }}"
                class="text-accent underline underline-offset-2 transition-colors duration-120 hover:text-accent-strong"
            >admin panel</a>.
        </p>
    </main>
</body>
</html>
