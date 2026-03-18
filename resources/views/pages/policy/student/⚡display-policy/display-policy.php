<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::student', ['title' => 'Policy Management'])] class extends Component
{
    public string $search = '';

    #[Computed]
    public function filteredTypes()
    {
        return ViolationType::query()
            ->select('id', 'code', 'name', 'classification')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('code', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%")
                        ->orWhere('classification', 'like', "%{$this->search}%");
                });
            })
            ->orderBy('classification')
            ->get()
            ->groupBy('classification');
    }
};
