<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'User Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public string $sortBy = 'name';

    public string $sortDirection = 'asc';

    public string $search = '';

    public ?string $role = null;

    public ?string $gate = null;

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
        if (in_array($property, ['search', 'role', 'gate'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'role', 'gate']);
        $this->resetPage();
    }

    private function baseQuery(): Builder
    {
        return User::onlyTrashed()
            ->where('role', '!=', 'student')
            ->when($this->search, fn (Builder $q) => $q->search($this->search))
            ->when($this->role, fn (Builder $q) => $q->where('role', $this->role))
            ->when($this->gate, fn (Builder $q) => $q->where('assigned_gate', $this->gate));
    }

    #[Computed]
    public function users()
    {
        return $this->baseQuery()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(12);
    }

    #[Computed]
    public function gates()
    {
        return User::onlyTrashed()
            ->distinct()
            ->pluck('assigned_gate')
            ->filter()
            ->sort()
            ->values();
    }

    #[On('refresh-del-user')]
    public function refreshTable(): void {}
};
