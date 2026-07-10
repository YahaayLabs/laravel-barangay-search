<div class="card">
    <header>
        <p class="eyebrow">Checkout</p>
        <h2 class="card-title">Delivery address</h2>
        <p class="sub">Powered by <code>&lt;livewire:barangay-search /&gt;</code></p>
    </header>

    <form class="fields" wire:submit="continue">
        <div class="row two">
            <label class="field">
                <span>Full name</span>
                <input type="text" wire:model.blur="fullName" autocomplete="name" />
            </label>
            <label class="field">
                <span>Mobile</span>
                <input type="tel" wire:model.blur="phone" autocomplete="tel" />
            </label>
        </div>

        <label class="field">
            <span>Street</span>
            <input
                type="text"
                wire:model.blur="street"
                placeholder="House no., street, subdivision"
                autocomplete="street-address"
            />
        </label>

        {{--
            Nested Livewire: wire:ignore keeps the parent morph from wiping the child's snapshot.
            :key keeps a stable identity across parent re-renders.
        --}}
        <div class="field field-barangay" wire:ignore>
            <span>Barangay — package component</span>
            <livewire:barangay-search
                wire:model="barangay"
                :key="'demo-barangay-search'"
                placeholder="e.g. Poblacion Batangas"
                :clearable="true"
            />
        </div>

        @if($this->selectionLabel)
            <div class="selection" wire:key="selection-{{ md5($this->selectionLabel) }}">
                <span class="selection-dot"></span>
                <span>{{ $this->selectionLabel }}</span>
            </div>
        @endif

        <label class="field">
            <span>Delivery notes <span style="font-weight:400;color:#94a3b8">(optional)</span></span>
            <textarea wire:model.blur="notes" rows="2" placeholder="Landmark, gate code…"></textarea>
        </label>

        @if($flash)
            <div class="banner {{ $flashType === 'ok' ? 'ok' : 'error' }}" role="status">
                {{ $flash }}
            </div>
        @endif

        <button type="submit" class="btn">Continue</button>
    </form>
</div>
