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

    public $classification = '';

    #[Computed]
    public function users()
    {
        return User::onlyTrashed()
            ->where('role', '!=', 'student')
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->when($this->classification, fn ($q) => $q->where('role', $this->classification))
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
        $this->reset(['search', 'classification']);
    }

    #[On('refresh-del-user')]
    public function refreshTable(): void {}
};
