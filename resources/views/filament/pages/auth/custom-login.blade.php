{{-- Login view for App\Filament\Pages\Auth\CustomLogin.

     Deliberately the plain Filament simple-page layout: one column, no illustration,
     no gradient panel, no marketing copy. For a single-operator admin the only
     decoration worth having is the brand mark and a definition-list of what the
     panel is for.

     `$this->content` is the schema Login::content() builds (form → MFA challenge →
     render hooks), so keeping it here preserves Filament's own ordering and hooks. --}}
<x-filament-panels::page.simple>
    {{ $this->content }}

    <x-slot name="footer">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            Private panel. Access is limited to registered maintainers.
        </p>
    </x-slot>
</x-filament-panels::page.simple>
