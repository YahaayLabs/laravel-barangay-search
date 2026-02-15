@props([
    'useMaryUi' => config('barangay-search.ui.use_mary_ui', true)
])

@if($useMaryUi)
    @include('barangay-search::livewire.partials.mary-ui')
@else
    @include('barangay-search::livewire.partials.vanilla')
@endif
