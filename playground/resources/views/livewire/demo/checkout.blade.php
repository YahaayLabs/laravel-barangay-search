<div class="gis-demo-card">
    <header>
        <p class="gis-demo-eyebrow">Checkout</p>
        <h2 class="gis-demo-card-title">Delivery address</h2>
        <p class="gis-demo-sub">Powered by <code>&lt;livewire:barangay-search /&gt;</code></p>
    </header>

    <form class="gis-demo-fields" wire:submit="continue">
        <div class="gis-demo-row-2">
            <label class="gis-demo-field">
                <span>Full name</span>
                <input type="text" wire:model.blur="fullName" autocomplete="name" />
            </label>
            <label class="gis-demo-field">
                <span>Mobile</span>
                <input type="tel" wire:model.blur="phone" autocomplete="tel" />
            </label>
        </div>

        <label class="gis-demo-field">
            <span>Street</span>
            <input
                type="text"
                wire:model.blur="street"
                placeholder="House no., street, subdivision"
                autocomplete="street-address"
            />
        </label>

        {{-- Nested Livewire: wire:ignore preserves child snapshot --}}
        <div class="gis-demo-field gis-demo-field-highlight" wire:ignore>
            <span>Barangay — package component</span>
            <livewire:barangay-search
                wire:model="barangay"
                :key="'demo-barangay-search'"
                placeholder="e.g. Poblacion Batangas"
                :clearable="true"
            />
        </div>

        @if($this->selectionLabel)
            <div class="gis-demo-selection" wire:key="selection-{{ md5($this->selectionLabel) }}">
                <span class="gis-demo-selection-dot"></span>
                <span>{{ $this->selectionLabel }}</span>
            </div>
        @endif

        <label class="gis-demo-field">
            <span>Delivery notes <span style="font-weight:400;color:var(--gis-text-faint)">(optional)</span></span>
            <textarea wire:model.blur="notes" rows="2" placeholder="Landmark, gate code…"></textarea>
        </label>

        @if($flash)
            <div class="gis-demo-banner {{ $flashType === 'ok' ? 'ok' : 'error' }}" role="status">
                {{ $flash }}
            </div>
        @endif

        <button type="submit" class="gis-demo-btn">Continue</button>
    </form>
</div>
