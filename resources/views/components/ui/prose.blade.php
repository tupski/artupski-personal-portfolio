{{-- Prose wrapper — the only place bare HTML is styled (§4) --}}
<div {{ $attributes->class(['prose-custom']) }}>
    {{ $slot }}
</div>
