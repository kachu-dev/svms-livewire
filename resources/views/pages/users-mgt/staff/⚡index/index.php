<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'User Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $search = '';

    public $role;

    public $gate;

    #[Computed]
    public function users()
    {
        return User::query()
            ->where('role', '!=', 'student')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->role, fn ($q) => $q->where('role', $this->role))
            ->when($this->gate, fn ($q) => $q->where('assigned_gate', $this->gate))
            ->paginate(11);
    }

    public function delete($userId): void
    {
        $user = User::findOrFail($userId);
        $user->delete();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'role', 'gate']);
    }

    #[Computed]
    public function gates()
    {
        return User::distinct()
            ->pluck('assigned_gate')
            ->filter()
            ->sort()
            ->values();
    }

    #[On('refresh-user')]
    public function refreshTable(): void {}
};
