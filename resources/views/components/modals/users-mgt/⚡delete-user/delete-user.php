<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    public ?User $user = null;

    public string $name = '';

    #[On('confirm-delete-user')]
    public function setFields($id): void
    {
        try {
            $this->user = User::findOrFail($id);

            $this->name = $this->user->name;

            $this->modal('delete-user')->show();
        } catch (Exception) {
            Toaster::error('User not found.');
        }
    }

    public function delete(): void
    {
        if (! $this->user instanceof User) {
            Toaster::error('No user selected for deactivation.');

            return;
        }
        try {
            $this->user->delete();

            $this->modal('delete-user')->close();

            $this->dispatch('refresh-user');

            Toaster::success('User deactivated successfully!');
        } catch (Exception) {
            Toaster::error('Failed to deactivate user. Please try again.');
        }
    }
};
