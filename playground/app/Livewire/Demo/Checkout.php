<?php

namespace App\Livewire\Demo;

use Livewire\Attributes\On;
use Livewire\Component;

/**
 * Marketing / GIF demo: checkout-style form using livewire:barangay-search.
 */
class Checkout extends Component
{
    public string $fullName = 'Maria Santos';

    public string $phone = '+63 917 555 0142';

    public string $street = '123 Mabini Street';

    public $barangay = null;

    public string $notes = '';

    public ?string $flash = null;

    public ?string $flashType = null;

    public function continue(): void
    {
        if (empty($this->barangay)) {
            $this->flash = 'Please select a barangay before continuing.';
            $this->flashType = 'error';

            return;
        }

        $this->flash = 'Ready to submit (demo only — no network POST).';
        $this->flashType = 'ok';
    }

    /**
     * Keep selection preview in sync when the nested component dispatches events
     * (works alongside wire:model / Modelable).
     */
    #[On('barangay-selected')]
    public function onBarangaySelected(array $barangay = []): void
    {
        // Livewire 3 may pass named params as the array itself or nested.
        $this->barangay = $barangay['barangay'] ?? $barangay;
        $this->flash = null;
        $this->flashType = null;
    }

    #[On('barangay-cleared')]
    public function onBarangayCleared(): void
    {
        $this->barangay = null;
    }

    public function getSelectionLabelProperty(): ?string
    {
        if (! is_array($this->barangay)) {
            return null;
        }

        if (! empty($this->barangay['full_address'])) {
            return (string) $this->barangay['full_address'];
        }

        $parts = array_filter([
            $this->barangay['name'] ?? null,
            $this->barangay['municipality'] ?? ($this->barangay['city'] ?? null),
            $this->barangay['province'] ?? null,
        ]);

        return $parts ? implode(', ', $parts) : null;
    }

    public function render()
    {
        return view('livewire.demo.checkout')
            ->layout('layouts.demo', [
                'title' => 'laravel-barangay-search',
            ]);
    }
}
