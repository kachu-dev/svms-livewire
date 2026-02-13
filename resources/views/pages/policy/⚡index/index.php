<?php

use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $search = '';

    public $classification;

    #[Computed]
    public function policies()
    {
        return ViolationType::query()
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification', $this->classification))
            ->where('deactivated', false)
            ->paginate(10);
    }

    public function deactivate($id)
    {
        $policy = ViolationType::find($id);
        $policy->update(['deactivated' => ! $policy->deactivated]);
    }
};
