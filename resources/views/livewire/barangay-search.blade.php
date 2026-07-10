{{-- Single root required for nested Livewire morphing --}}
<div>
    @if($useMaryUi)
        @include('barangay-search::livewire.partials.mary-ui')
    @else
        @include('barangay-search::livewire.partials.vanilla')
    @endif
</div>
