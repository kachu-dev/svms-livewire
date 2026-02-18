<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'User Management'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $search = '';

    #[Computed]
    public function users()
    {
        return User::onlyTrashed()
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->paginate(10);
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
        $this->reset('search');
    }
};
