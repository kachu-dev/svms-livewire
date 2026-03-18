<?php

use App\Models\Student;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

new #[Layout('layouts::auth', ['heading' => 'Student Registration'])] class extends Component
{
    #[Validate('required|string')]
    public string $username = '';

    #[Validate('required|string|min:8|confirmed')]
    public string $password = '';

    #[Validate('required|string')]
    public string $password_confirmation = '';

    public function register()
    {
        $this->validate();

        $student = Student::select('studentid', 'rfidtag', 'firstname', 'lastname')
            ->where('studentid', $this->username)
            ->first();

        if (! $student) {
            $this->addError('username', 'Student ID not found. Please check your ID number.');

            return;
        }

        if (User::where('username', $this->username)->where('role', 'student')->exists()) {
            $this->addError('username', 'This student ID is already registered.');

            return;
        }

        $user = User::create([
            'name' => $student->firstname.' '.$student->lastname,
            'username' => $this->username,
            'email' => $this->username.'@adzu.edu.ph',
            'password' => Hash::make($this->password),
            'role' => 'student',
        ]);

        event(new Registered($user));

        activity('auth')
            ->causedBy($user)
            ->withProperties([
                'ip' => request()->ip(),
                'student_id' => $student->studentid,
            ])
            ->log('Student account registered');

        auth()->login($user);

        return $this->redirect(route('student.violations.index'));
    }
};
