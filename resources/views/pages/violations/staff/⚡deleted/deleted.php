<?php

use App\Models\Violation;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'Violation Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $sortBy = 'created_at';

    public $sortDirection = 'desc';

    public $search = '';

    public $classification;

    public $dateFrom;

    public $dateTo;

    public function sort($column): void
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
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('classification', $this->classification))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);
    }

    #[Computed]
    public function classifications()
    {
        return Violation::onlyTrashed()->distinct('classification')
            ->pluck('classification')
            ->sortDesc();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'classification', 'dateFrom', 'dateTo']);
    }

    public function restore($violationId): void
    {
        $violation = Violation::onlyTrashed()->findOrFail($violationId);
        $violation->restore();
    }

    public function updating($property, $value): void
    {
        if (in_array($property, ['search', 'classification', 'dateFrom', 'dateTo'])) {
            $this->resetPage();
        }
    }
};
