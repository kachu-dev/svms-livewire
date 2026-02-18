<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public $typeSearch;

    #[Computed]
    public function filteredTypes()
    {
        $query = ViolationType::query();

        if ($this->typeSearch) {
            $search = '%'.$this->typeSearch.'%';
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', $search)
                    ->orWhere('name', 'like', $search);
            });
        }

        return $query->select('id', 'code', 'name', 'classification')
            ->orderBy('classification')
            ->where('classification', 'minor')
            ->get()
            ->groupBy('classification');
    }
};
