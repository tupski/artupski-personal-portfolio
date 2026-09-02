<x-filament-panels::pages.auth.simple
    :heading="__('filament-panels::auth/pages/login.heading')"
    :subheading="__('filament-panels::auth/pages/login.subheading')"
>
    <x-slot name="form">
        {{ $this->form }}
    </x-slot>

    @if ($this->hasMultiFactorChallengeForm())
        <x-slot name="multiFactorChallengeForm">
            {{ $this->multiFactorChallengeForm }}
        </x-slot>
    @endif

    <x-slot name="actions">
        <x-filament-panels::form.actions
            :actions="$this->getFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />

        @if ($this->hasMultiFactorChallengeForm())
            <x-filament-panels::form.actions
                :actions="$this->getMultiFactorChallengeFormActions()"
                :full-width="$this->hasFullWidthMultiFactorChallengeFormActions()"
            />
        @endif
    </x-slot>
</x-filament-panels::pages.auth.simple>
