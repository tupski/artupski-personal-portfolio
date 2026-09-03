<!DOCTYPE html>
<html lang="en" class="scroll-smooth" data-theme="system">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $pageTitle ?? config('app.name') }}</title>

  {{-- Anti-flash theme script — MUST be first child of <head>, before stylesheet (§8.2) --}}
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

  {{-- Vite assets --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  {{-- SEO seam — Task 6 will inject <x-seo> here via @stack('seo') --}}
  @stack('seo')
</head>
<body class="min-h-dvh bg-bg text-fg font-sans antialiased">
  {{-- Skip link (§9.1) --}}
  <a class="skip-link" href="#main">Skip to content</a>

  {{-- Header (§3.5) --}}
  <header class="sticky top-0 z-30 h-14 bg-bg border-b border-line md:h-16">
    <x-ui.container size="default">
      <nav aria-label="Primary" class="flex items-center justify-between h-14 md:h-16">
        {{-- Site identity from SiteSetting, not hard-coded --}}
        <a href="/" class="font-semibold text-fg hover:text-accent transition-colors duration-120">
          {{ $siteName ?? config('app.name') }}
        </a>

        {{-- Desktop nav (§4) --}}
        <x-navigation.nav-desktop :items="$headerNavItems ?? collect()" />

        <div class="flex items-center gap-3">
          {{-- Theme toggle — present in both navs (§4) --}}
          <x-navigation.theme-toggle />

          {{-- Mobile hamburger --}}
          <x-navigation.nav-mobile :items="$headerNavItems ?? collect()" />
        </div>
      </nav>
    </x-ui.container>
  </header>

  {{-- Main landmark (§9.1) --}}
  <main id="main" tabindex="-1">
    {{ $slot }}
  </main>

  {{-- Footer (§3.5) --}}
  <footer class="bg-bg-subtle border-t border-line mt-16">
    <x-ui.container size="default">
      <div class="py-12 md:py-16 md:grid md:grid-cols-3 md:gap-8">
        {{-- Identity --}}
        <div>
          <p class="font-semibold text-fg">{{ $siteName ?? config('app.name') }}</p>
          @if(!empty($siteHeadline))
            <p class="mt-2 text-sm text-fg-muted">{{ $siteHeadline }}</p>
          @endif
        </div>

        {{-- Footer nav --}}
        <nav aria-label="Footer" class="mt-8 md:mt-0">
          <ul class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
            @foreach($footerNavItems ?? collect() as $item)
              <li>
                <a
                  href="{{ $item->url }}"
                  @if($item->target_blank) target="_blank" rel="noopener" @endif
                  class="text-fg-muted hover:text-accent transition-colors duration-120"
                >{{ $item->label }}</a>
              </li>
            @endforeach
          </ul>
        </nav>

        {{-- Social --}}
        <div class="mt-8 md:mt-0">
          @if(!empty($socialLinks) && count($socialLinks) > 0)
            <ul class="flex flex-wrap gap-x-6 gap-y-2 text-sm">
              @foreach($socialLinks as $label => $url)
                <li>
                  <a
                    href="{{ $url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="text-fg-muted hover:text-accent transition-colors duration-120"
                  >{{ $label }}</a>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>

      {{-- Copyright --}}
      <div class="border-t border-line py-6 text-xs text-fg-subtle font-mono">
        &copy; {{ date('Y') }} {{ $siteName ?? config('app.name') }}. All rights reserved.
      </div>
    </x-ui.container>
  </footer>
</body>
</html>
