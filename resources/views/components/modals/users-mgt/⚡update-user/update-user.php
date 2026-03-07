<?php

use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    public ?User $user = null;

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string')]
    public string $role = '';

    #[Validate('string')]
    public ?string $assigned_gate = null;

    #[Validate('nullable|string|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    #[On('update-user')]
    public function setFields($id): void
    {
        try {
            $this->resetErrorBag();

            $this->user = User::findOrFail($id);

            $this->name = $this->user->name;
            $this->username = $this->user->username;
            $this->role = $this->user->role;
            $this->assigned_gate = $this->user->assigned_gate;

            $this->modal('update-user')->show();
        } catch (Exception) {
            Toaster::error('User not found');
        }
    }

    public function save(): void
    {
        if (! $this->user instanceof User) {  // ← Add null check
            Toaster::error('No user selected for update.');

            return;
        }

        $this->validate();

        try {
            $this->user->update(
                $this->only(['name', 'username', 'role', 'assigned_gate'])
            );

            if ($this->password !== '' && $this->password !== '0') {
                $this->user->update([
                    'password' => Hash::make($this->password),
                ]);
            }

            $this->modal('update-user')->close();
            $this->dispatch('refresh-user');
        } catch (Exception) {
            Toaster::error('Failed to update user. Please try again.');
        }
    }
};
