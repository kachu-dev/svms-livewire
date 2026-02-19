<?php

use App\Models\User;
use App\Models\ViolationType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::app', ['title' => 'Update User'])] class extends Component
{
    public ?User $user = null;

    #[Validate('required|string')]
    public $name;

    #[Validate('required|string|unique:users,username')]
    public $username;

    #[Validate('required|string')]
    public $role;

    #[Validate('required|integer')]
    public $assigned_gate;

    #[Validate('required|string|min:8|confirmed')]
    public $password = '';

    #[Validate('required')]
    public $password_confirmation = '';

    public function submit(): void
    {
        $this->validate();

        User::create([
            'name'          => $this->name,
            'username'      => $this->username,
            'role'          => $this->role,
            'assigned_gate' => $this->assigned_gate,
            'password'      => Hash::make($this->password),
        ]);

        Toaster::success('User created successfully!');

        $this->redirectRoute('staff.users-mgt.index');
    }

    public function resetCreateForm(): void
    {
        $this->resetValidation();
        $this->reset();
    }
};
