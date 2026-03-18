<?php

use App\Models\User;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

new class extends Component
{
    public string $name = '';

    public string $username = '';

    public string $role = '';

    public ?int $assigned_gate = null;

    public string $password = '';

    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string',
            'username' => 'required|string|unique:users,username',
            'role' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'assigned_gate' => $this->role === 'assigned_guard' ? 'required|integer' : 'nullable|integer',
        ];
    }

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
