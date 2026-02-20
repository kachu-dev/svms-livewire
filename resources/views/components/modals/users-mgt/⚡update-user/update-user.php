<?php

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Update User'])] class extends Component
{
    public ?User $user = null;

    #[Validate('required|string')]
    public $name;

    #[Validate('required|string')]
    public $username;

    #[Validate('required|string')]
    public $role;

    #[Validate('required|string')]
    public $assigned_gate;

    #[Validate('nullable|string|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    #[On('update-user')]
    public function setFields($id): void
    {
        $this->reset(['password', 'password_confirmation']);

        $this->user = User::find($id);

        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->role = $this->user->role;
        $this->assigned_gate = $this->user->assigned_gate;

        $this->modal('update-user')->show();
    }

    public function save(): void
    {
        $this->validate();

        $this->user->update(
            $this->only(['name', 'username', 'role', 'assigned_gate'])
        );

        if (! empty($this->password)) {
            $this->user->update([
                'password' => Hash::make($this->password),
            ]);
        }

        Toaster::success('User updated successfully!');

        $this->redirectRoute('staff.users-mgt.index');
    }
};
