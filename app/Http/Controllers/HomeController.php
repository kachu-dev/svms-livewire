<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        return match (true) {
            $user->can('access-staff-area') => redirect()->route('staff.violations.index'),
            $user->can('access-guard-area') => redirect()->route('guard.violations.recent'),
            $user->can('access-student-area') => redirect()->route('student.violations.index'),
            default => $this->handleNoAccess(),
        };
    }

    private function handleNoAccess(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login')
            ->with('error', 'You do not have access to any area.');
    }
}
