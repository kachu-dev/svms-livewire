<?php

use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'Violation Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public string $sortBy = 'created_at';

    public string $sortDirection = 'desc';

    public string $search = '';

    public ?string $classification = null;

    public ?string $dateFrom = null;

    public ?string $dateTo = null;

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function violations()
    {
        return Violation::onlyTrashed()
            ->with(['student', 'recordedBy'])
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification', $this->classification))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(9);
    }

    #[Computed]
    public function classifications()
    {
        return Violation::onlyTrashed()
            ->distinct()
            ->pluck('classification')
            ->sortDesc();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'classification', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    #[On('refresh-del-violation')]
    public function refreshTable(): void {}

    public function updating(string $property): void
    {
        if (in_array($property, ['search', 'classification', 'dateFrom', 'dateTo'])) {
            $this->resetPage();
        }
    }
};
