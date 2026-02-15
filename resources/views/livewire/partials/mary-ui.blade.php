<div class="relative {{ $containerClass }}" x-data="{ open: @entangle('showDropdown') }">
    {{-- Label --}}
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Search Input --}}
    <div class="relative">
        <x-input
            wire:model.live.debounce.{{ config('barangay-search.search.debounce_ms', 300) }}ms="query"
            :placeholder="$placeholder"
            icon="o-map-pin"
            :clearable="$clearable"
            wire:loading.class="opacity-50"
            class="{{ $inputClass }}"
        >
            <x-slot:append>
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="search">
                    <x-loading class="w-4 h-4 text-primary" />
                </div>

                {{-- Clear Button --}}
                @if($clearable && $selected)
                    <button
                        type="button"
                        wire:click="clear"
                        class="p-1 hover:bg-gray-100 rounded-full transition"
                    >
                        <x-icon name="o-x-mark" class="w-4 h-4 text-gray-400" />
                    </button>
                @endif
            </x-slot:append>
        </x-input>

        {{-- Hint --}}
        @if($hint)
            <p class="mt-1 text-sm text-gray-500">{{ $hint }}</p>
        @endif

        {{-- Error Message --}}
        @if($errorMessage)
            <p class="mt-1 text-sm text-red-600">{{ $errorMessage }}</p>
        @endif
    </div>

    {{-- Results Dropdown --}}
    <div
        x-show="open"
        @click.away="$wire.closeDropdown()"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-y-auto"
        style="display: none;"
    >
        @if(count($results) > 0)
            <ul class="py-1 divide-y divide-gray-100">
                @foreach($results as $index => $barangay)
                    <li
                        wire:key="barangay-{{ $index }}"
                        wire:click="selectBarangay({{ json_encode($barangay) }})"
                        class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors duration-150"
                    >
                        <div class="flex items-start">
                            <x-icon name="o-map-pin" class="w-4 h-4 text-gray-400 mt-1 mr-2 flex-shrink-0" />
                            <div class="flex-1">
                                <div class="font-medium text-gray-900">{{ $barangay['name'] }}</div>
                                <div class="text-sm text-gray-500">
                                    {{ $barangay['full_address'] }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @elseif(!empty($query) && !$isLoading)
            <div class="px-4 py-3 text-sm text-gray-500 text-center">
                <x-icon name="o-magnifying-glass" class="w-5 h-5 mx-auto mb-1 text-gray-400" />
                {{ config('barangay-search.ui.no_results_text') }}
            </div>
        @endif
    </div>

    {{-- Selected Barangay Display --}}
    @if($selected && !$showDropdown)
        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start justify-between">
                <div class="flex items-start flex-1">
                    <x-icon name="o-check-circle" class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" />
                    <div>
                        <div class="font-medium text-blue-900">{{ $selected['name'] }}</div>
                        <div class="text-sm text-blue-700">
                            {{ $selected['full_address'] }}
                        </div>
                        @if(isset($selected['code']))
                            <div class="text-xs text-blue-600 mt-1">
                                Code: {{ $selected['code'] }}
                            </div>
                        @endif
                    </div>
                </div>
                @if($clearable)
                    <button
                        type="button"
                        wire:click="clear"
                        class="p-1 hover:bg-blue-100 rounded-full transition"
                    >
                        <x-icon name="o-x-mark" class="w-4 h-4 text-blue-600" />
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
