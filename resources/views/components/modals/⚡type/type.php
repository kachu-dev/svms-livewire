<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public $typeSearch;

    public $selectedTypeId;

    public $selectedTypeLabel;

    #[Computed]
    public function filteredTypes()
    {
        $query = ViolationType::query();

        if ($this->typeSearch) {
            $query->where(function ($q) {
                $q->where('code', 'like', '%'.$this->typeSearch.'%')
                    ->orWhere('name', 'like', '%'.$this->typeSearch.'%');
            });
        }

        return $query->get()->groupBy('classification');
    }

    public function setType(int $id): void
    {
        /*$violation = ViolationType::findOrFail($id);

        $this->selectedTypeId = $violation->id;
        $this->selectedTypeLabel = "{$violation->code} — {$violation->name}";*/

        $this->dispatch('type-selected', violationId: $id);
        $this->modal('set-violation')->close();
    }
};
