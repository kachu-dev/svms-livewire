<?php

use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

new #[Layout('layouts::auth')] class extends Component
{
    #[Validate('required')]
    public $username;

    #[Validate('required')]
    public $password;

    public function login()
    {
        $this->validate();

        $credentials = [
            'username' => $this->username,
            'password' => $this->password
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'osa') {
                return $this->redirect(route('staff.violations.index'));
            } elseif ($user->role === 'guard') {
                return $this->redirect(route('guard.violations.create'));
            } else {
                return $this->redirect(route('home'));
            }
        }
        $this->addError('credentials', 'Credentials is incorrect.');
        $this->reset(['username', 'password']);
        session()->flash('error', 'Invalid credentials');
    }
};
