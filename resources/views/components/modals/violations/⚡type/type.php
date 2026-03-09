<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public $typeSearch;

    public $selectedTypeId;

    public bool $minorOnly = false;

    #[Computed]
    public function filteredTypes()
    {
        $query = ViolationType::query();

        if ($this->minorOnly) {
            $query->where('classification', 'Minor');
        }

        if ($this->typeSearch) {
            $search = '%'.$this->typeSearch.'%';
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', $search)
                    ->orWhere('name', 'like', $search);
            });
        }

        return $query->select('id', 'code', 'name', 'classification')
            ->orderBy('classification')
            ->get()
            ->groupBy('classification');
    }

    public function clearSearch(): void
    {
        $this->reset('typeSearch');
    }

    #[On('reset-type')]
    public function resetType(): void
    {
        $this->reset('selectedTypeId');
    }
};
