<div class="relative {{ $containerClass }}" x-data="{ open: @entangle('showDropdown') }" style="position: relative; width: 100%;">
    {{-- Label --}}
    @if($label)
        <label style="display: block; font-size: 0.8rem; font-weight: 500; color: #475569; margin-bottom: 0.35rem;">
            {{ $label }}
            @if($required)
                <span style="color: #ef4444;">*</span>
            @endif
        </label>
    @endif

    {{-- Search Input --}}
    <div style="position: relative;">
        <div style="position: relative; display: flex; align-items: center;">
            <div style="position: absolute; left: 0.65rem; top: 0; bottom: 0; display: flex; align-items: center; pointer-events: none; z-index: 1;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>

            <input
                type="text"
                wire:model.live.debounce.{{ $debounce }}ms="query"
                placeholder="{{ $placeholder }}"
                class="{{ $inputClass }}"
                style="display: block; width: 100%; box-sizing: border-box; padding: 0.55rem 2rem 0.55rem 2rem; font-size: 0.9rem; line-height: 1.4; color: #0f172a; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; outline: none;"
                wire:loading.class="opacity-50"
                onfocus="this.style.borderColor='#34d399'; this.style.boxShadow='0 0 0 3px rgba(16,185,129,0.15)'"
                onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'"
            />

            <div style="position: absolute; right: 0.5rem; top: 0; bottom: 0; display: flex; align-items: center; gap: 0.25rem;">
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="search" style="display: flex; align-items: center;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" aria-hidden="true" style="animation: barangay-spin 0.7s linear infinite;">
                        <circle cx="12" cy="12" r="10" stroke="#94a3b8" stroke-width="3" opacity="0.25"></circle>
                        <path fill="#059669" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" opacity="0.85"></path>
                    </svg>
                </div>

                {{-- Clear Button --}}
                @if($clearable && $selected)
                    <button
                        type="button"
                        wire:click="clear"
                        style="display: flex; align-items: center; justify-content: center; padding: 0.15rem; border: none; background: transparent; color: #94a3b8; cursor: pointer; border-radius: 999px; line-height: 0;"
                        title="Clear"
                        aria-label="Clear selection"
                    >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        @if($hint)
            <p style="margin: 0.35rem 0 0; font-size: 0.75rem; color: #94a3b8;">{{ $hint }}</p>
        @endif

        @if($errorMessage)
            <p style="margin: 0.35rem 0 0; font-size: 0.8rem; color: #dc2626;">{{ $errorMessage }}</p>
        @endif
    </div>

    {{-- Results Dropdown --}}
    <div
        x-show="open"
        @click.away="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none; position: absolute; z-index: 50; left: 0; right: 0; margin-top: 0.35rem; background: #fff; border: 1px solid #e2e8f0; border-radius: 0.5rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.08); max-height: 15rem; overflow-y: auto;"
    >
        @if(count($results) > 0)
            <ul style="list-style: none; margin: 0; padding: 0.25rem;">
                @foreach($results as $index => $barangay)
                    <li
                        wire:key="barangay-{{ $index }}"
                        wire:click="selectBarangay({{ json_encode($barangay) }})"
                        style="padding: 0.55rem 0.65rem; cursor: pointer; border-radius: 0.375rem; display: flex; align-items: flex-start; gap: 0.5rem;"
                        onmouseover="this.style.background='#f1f5f9'"
                        onmouseout="this.style.background='transparent'"
                    >
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0; margin-top: 0.2rem;">
                            <path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div style="min-width: 0; flex: 1;">
                            <div style="font-weight: 600; font-size: 0.875rem; color: #0f172a; line-height: 1.3;">
                                {{ $barangay['name'] ?? '' }}
                            </div>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.1rem; line-height: 1.35;">
                                {{ $barangay['full_address'] ?? trim(implode(', ', array_filter([$barangay['municipality'] ?? $barangay['city'] ?? null, $barangay['province'] ?? null]))) }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @elseif(!empty($query) && !$isLoading)
            <div style="padding: 0.85rem; text-align: center; font-size: 0.8rem; color: #64748b;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" aria-hidden="true" style="display: block; margin: 0 auto 0.35rem;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                {{ config('barangay-search.ui.no_results_text') }}
            </div>
        @endif
    </div>

    {{-- Selected Barangay Display --}}
    @if($selected && !$showDropdown)
        <div style="margin-top: 0.65rem; padding: 0.65rem 0.75rem; background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 0.5rem;">
            <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 0.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.45rem; min-width: 0; flex: 1;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="flex-shrink: 0; margin-top: 0.15rem;">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div style="min-width: 0;">
                        <div style="font-weight: 600; font-size: 0.875rem; color: #065f46; line-height: 1.3;">
                            {{ $selected['name'] ?? '' }}
                        </div>
                        <div style="font-size: 0.75rem; color: #047857; margin-top: 0.1rem; line-height: 1.35;">
                            {{ $selected['full_address'] ?? '' }}
                        </div>
                        @if(isset($selected['code']))
                            <div style="font-size: 0.7rem; color: #059669; margin-top: 0.2rem;">
                                Code: {{ $selected['code'] }}
                            </div>
                        @endif
                    </div>
                </div>
                @if($clearable)
                    <button
                        type="button"
                        wire:click="clear"
                        style="display: flex; align-items: center; justify-content: center; padding: 0.2rem; border: none; background: transparent; color: #059669; cursor: pointer; border-radius: 999px; line-height: 0; flex-shrink: 0;"
                        title="Clear"
                        aria-label="Clear selection"
                    >
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                            <path d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
    @endif

    <style>
        @keyframes barangay-spin {
            to { transform: rotate(360deg); }
        }
    </style>
</div>
