<?php

use App\Models\ViolationType;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'Policy Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public string $sortBy = 'code';

    public string $sortDirection = 'asc';

    public string $search = '';

    public ?string $classification = null;

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'classification'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'classification']);
        $this->resetPage();
    }

    private function baseQuery(): Builder
    {
        return ViolationType::onlyTrashed()
            ->when($this->search, fn (Builder $q) => $q->search($this->search))
            ->when($this->classification, fn (Builder $q) => $q->where('classification', $this->classification));
    }

    #[Computed]
    public function policies()
    {
        return $this->baseQuery()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
    }

    public function restore(int $policyId): void
    {
        ViolationType::onlyTrashed()->findOrFail($policyId)->restore();
    }

    #[On('refresh-del-policy')]
    public function refreshTable(): void {}
};
