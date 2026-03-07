<?php

use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
    Route::livewire('/register', 'pages::auth.register')->name('register');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', function (): Redirector|RedirectResponse {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});

// Staff area routes
Route::middleware(['auth', 'can:access-staff-area'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        // Violations management
        Route::prefix('violations')->name('violations.')->group(function () {
            Route::livewire('/', 'pages::violations.staff.index')->name('index');
            Route::livewire('/create', 'pages::violations.staff.create')->name('create');
            Route::livewire('/deleted', 'pages::violations.staff.deleted')->name('deleted');
            Route::livewire('/{violation}/stage/{stage}', 'pages::violations.staff.detail')->name('detail');
        });

        // Student profile
        Route::livewire('/student-profile/{studentId}', 'pages::violations.staff.student-profile')
            ->name('violations.student');

        // Policy management
        Route::prefix('policy')->name('policy.')->group(function () {
            Route::livewire('/', 'pages::policy.staff.index')->name('index');
            Route::livewire('/deactivated', 'pages::policy.staff.deleted')->name('deleted');
        });

        // User management
        Route::prefix('users')->name('users-mgt.')->group(function () {
            Route::livewire('/', 'pages::users-mgt.staff.index')->name('index');
            Route::livewire('/deactivated', 'pages::users-mgt.staff.deleted')->name('deleted');
        });
    });

// Guard area routes
Route::middleware(['auth', 'can:access-guard-area'])
    ->prefix('guard')
    ->name('guard.')
    ->group(function () {
        Route::prefix('violations')->name('violations.')->group(function () {
            Route::livewire('/create', 'pages::violations.guard.create')->name('create');
            Route::livewire('/recent', 'pages::violations.guard.recent')->name('recent');
        });
    });

// Student area routes
Route::middleware(['auth', 'verified', 'can:access-student-area'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {
        // Policy display
        Route::livewire('/policies', 'pages::policy.student.display-policy')
            ->name('policy.display-policy');

        // Violations
        Route::livewire('/violations', 'pages::violations.student.index')
            ->name('violations.index');
    });

/*Route::get('/email/verify', function () {
    return view('pages.auth.⚡verify-email.verify-email');
})->middleware('auth')->name('verification.notice');*/

Route::livewire('/email/verify', 'pages::auth.verify-email')->middleware('auth')->name('verification.notice');

// Handle the verification link click
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route('student.violations.index');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::livewire('/test', 'pages::test');
