<?php

use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    #[Validate('required|string')]
    public string $name = '';

    #[Validate('required|string|unique:users,username')]
    public string $username = '';

    #[Validate('required|string')]
    public string $role = '';

    public ?int $assigned_gate = null;

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    #[Validate('required')]
    public string $password_confirmation = '';

    public function submit(): void
    {
        $validated = $this->validate();

        try {
            User::create($validated);

            $this->resetCreateForm();
            $this->modal('create-user')->close();
            $this->dispatch('refresh-user');

            Toaster::success('User created successfully!');
        } catch (Exception) {
            Toaster::error('Failed to create user. Please try again.');
        }
    }

    public function resetCreateForm(): void
    {
        $this->resetValidation();
        $this->reset();
    }
};
