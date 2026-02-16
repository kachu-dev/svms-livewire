<?php

use App\Models\User;
use App\Models\ViolationType;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

new #[Layout('layouts::app', ['title' => 'All Policies'])] class extends Component
{
    use WithoutUrlPagination, WithPagination;

    public $search = '';

    #[Computed]
    public function users()
    {
        return User::query()
            ->when($this->search, fn ($q) => $q->search($this->search))
            ->paginate(10);
    }

    public function delete($userId): void
    {
        $user = User::findOrFail($userId);
        $user->delete();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
};
