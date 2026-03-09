<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::auth', ['heading' => 'Login'])] class extends Component
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
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'student') {
                return $this->redirect(route('student.violations.index'));
            }

            if ($user->role === 'osa') {
                return $this->redirect(route('staff.violations.index'));
            }

            if ($user->role === 'guard') {
                return $this->redirect(route('guard.violations.create'));
            }

            return $this->redirect(route('home'));
        }

        $this->addError('credentials', 'Credentials are incorrect.');
        $this->reset(['username', 'password']);
        session()->flash('error', 'Invalid credentials');
    }
};
