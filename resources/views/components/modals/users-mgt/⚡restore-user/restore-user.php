<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    public ?User $user = null;

    public string $name = '';

    #[On('restore-user')]
    public function setFields($id): void
    {
        try {
            $this->user = User::onlyTrashed()->findOrFail($id);

            $this->name = $this->user->name;

            $this->modal('restore-user')->show();
        } catch (Exception) {
            Toaster::error('User not found.');
        }
    }

    public function restore(): void
    {
        if (! $this->user instanceof User) {
            Toaster::error('No user selected for restoration.');

            return;
        }

        try {
            $this->user->restore();

            $this->modal('restore-user')->close();
            $this->dispatch('refresh-del-user');

            Toaster::success('User reactivated successfully!');
        } catch (Exception) {
            Toaster::error('Failed to restore User. Please try again.');
        }
    }
};
