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
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            
            <input
                type="text"
                wire:model.live.debounce.{{ $debounce }}ms="query"
                placeholder="{{ $placeholder }}"
                class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $inputClass }}"
                wire:loading.class="opacity-50"
            />

            <div class="absolute inset-y-0 right-0 pr-3 flex items-center space-x-2">
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="search">
                    <svg class="animate-spin h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                {{-- Clear Button --}}
                @if($clearable && $selected)
                    <button
                        type="button"
                        wire:click="clear"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

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
                            <svg class="w-4 h-4 text-gray-400 mt-1 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
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
                <svg class="w-5 h-5 mx-auto mb-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                {{ config('barangay-search.ui.no_results_text') }}
            </div>
        @endif
    </div>

    {{-- Selected Barangay Display --}}
    @if($selected && !$showDropdown)
        <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start justify-between">
                <div class="flex items-start flex-1">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
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
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @endif
</div>
