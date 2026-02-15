<?php

namespace YahaayLabs\LaravelBarangaySearch\Livewire;

use Livewire\Component;
use Livewire\Attributes\Modelable;
use YahaayLabs\LaravelBarangaySearch\Services\BarangayService;
use GisPh\Core\Exceptions\GisPhException;

class BarangaySearch extends Component
{
    #[Modelable]
    public $selected = null;

    public string $query = '';
    public array $results = [];
    public bool $isLoading = false;
    public bool $showDropdown = false;
    public string $errorMessage = '';

    // Optional filters
    public ?string $municipalityCode = null;
    public ?string $cityCode = null;
    public ?string $provinceCode = null;

    // UI customization
    public string $placeholder = '';
    public string $label = '';
    public bool $required = false;
    public bool $clearable = true;
    public string $hint = '';

    // Component styling
    public string $containerClass = '';
    public string $inputClass = '';

    protected BarangayService $barangayService;

    /**
     * Component initialization
     */
    public function boot(BarangayService $barangayService): void
    {
        $this->barangayService = $barangayService;
    }

    /**
     * Mount the component
     */
    public function mount(
        $selected = null,
        ?string $municipalityCode = null,
        ?string $cityCode = null,
        ?string $provinceCode = null,
        string $placeholder = '',
        string $label = '',
        bool $required = false,
        bool $clearable = true,
        string $hint = ''
    ): void {
        $this->selected = $selected;
        $this->municipalityCode = $municipalityCode;
        $this->cityCode = $cityCode;
        $this->provinceCode = $provinceCode;
        $this->placeholder = $placeholder ?: config('barangay-search.ui.placeholder');
        $this->label = $label;
        $this->required = $required;
        $this->clearable = $clearable;
        $this->hint = $hint;

        // If selected is already set, populate the query
        if ($this->selected && is_array($this->selected)) {
            $this->query = $this->selected['name'] ?? '';
        }
    }

    /**
     * Watch for query changes (debounced)
     */
    public function updatedQuery(): void
    {
        $this->errorMessage = '';

        if (strlen($this->query) < config('barangay-search.search.min_query_length', 2)) {
            $this->results = [];
            $this->showDropdown = false;
            return;
        }

        $this->search();
    }

    /**
     * Perform search
     */
    public function search(): void
    {
        $this->isLoading = true;
        $this->errorMessage = '';

        try {
            $filters = array_filter([
                'municipality_code' => $this->municipalityCode,
                'city_code' => $this->cityCode,
                'province_code' => $this->provinceCode,
            ]);

            $this->results = $this->barangayService->search($this->query, $filters);
            $this->showDropdown = count($this->results) > 0;

            if (empty($this->results)) {
                $this->errorMessage = config('barangay-search.ui.no_results_text');
            }
        } catch (GisPhException $e) {
            $this->errorMessage = 'Failed to Barangay Searchs. Please try again.';
            $this->results = [];
            $this->dispatch('barangay-search-error', error: $e->getMessage());
        } finally {
            $this->isLoading = false;
        }
    }

    /**
     * Select a barangay from results
     */
    public function selectBarangay(array $barangay): void
    {
        $this->selected = $barangay;
        $this->query = $barangay['name'];
        $this->showDropdown = false;
        $this->results = [];

        $this->dispatch('barangay-selected', barangay: $barangay);
    }

    /**
     * Clear selection
     */
    public function clear(): void
    {
        $this->reset(['query', 'selected', 'results', 'showDropdown', 'errorMessage']);
        $this->dispatch('barangay-cleared');
    }

    /**
     * Close dropdown
     */
    public function closeDropdown(): void
    {
        $this->showDropdown = false;
    }

    /**
     * Get validation rules
     */
    public function rules(): array
    {
        return [
            'selected' => $this->required ? ['required', 'array'] : ['nullable', 'array'],
        ];
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('barangay-search::livewire.barangay-search', [
            'useMaryUi' => config('barangay-search.ui.use_mary_ui', true)
        ]);
    }
}
