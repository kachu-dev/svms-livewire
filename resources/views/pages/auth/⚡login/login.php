<?php

use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Component;

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
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'student') {
                $student = Student::find($user->username);

                if ($student) {
                    $user->update([
                        'student_id' => $student->studentid,
                        'student_program' => $student->program,
                        'student_year' => $student->year,
                    ]);
                }

                return $this->redirect(route('student.policy.display-policy'));
            }

            if ($user->role === 'osa') {
                return $this->redirect(route('staff.violations.index'));
            }

            if ($user->role === 'guard') {
                return $this->redirect(route('guard.violations.create'));
            }

            return $this->redirect(route('home'));
        }
        $this->addError('credentials', 'Credentials is incorrect.');
        $this->reset(['username', 'password']);
        session()->flash('error', 'Invalid credentials');
    }
};
