<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'Deleted Policy'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $search = '';

    public $classification;

    #[Computed]
    public function policies()
    {
        return ViolationType::onlyTrashed()
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification', $this->classification))
            ->paginate(10);
    }

    public function restore($policyId): void
    {
        $policy = ViolationType::onlyTrashed()->findOrFail($policyId);
        $policy->restore();
    }

    public function updating($property, $value): void
    {
        if (in_array($property, ['search', 'classification', 'dateFrom', 'dateTo'])) {
            $this->resetPage();
        }
    }
};
