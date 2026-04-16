<?php

use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

Route::get('/svms', HomeController::class)->name('home');

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
            Route::livewire('/complete', 'pages::violations.staff.complete')->name('complete');
            Route::livewire('/create', 'pages::violations.staff.create')->name('create');
            Route::livewire('/deleted', 'pages::violations.staff.deleted')->name('deleted');
            Route::livewire('/delete-requests', 'pages::violations.staff.delete-requests')->name('delete-requests');
            Route::livewire('/update-requests', 'pages::violations.staff.update-requests')->name('update-requests');
            Route::livewire('/{violation}/stage/{stage?}', 'pages::violations.staff.detail')->name('detail');
            Route::livewire('/{violation}/stage', 'pages::violations.staff.detail')->name('detail.no-stage');
        });

        // Student profile
        Route::livewire('/student-profile/{studentId}', 'pages::violations.staff.student-profile')
            ->name('violations.student');

        // Policy management
        Route::prefix('policy')->name('policy.')->group(function () {
            Route::livewire('/', 'pages::policy.staff.index')->name('index');
            Route::livewire('/deactivated', 'pages::policy.staff.deleted')->name('deleted');
            Route::livewire('/template', 'pages::policy.staff.template')->name('template');
        });

        // User management
        Route::prefix('users')->name('users-mgt.')->group(function () {
            Route::livewire('/', 'pages::users-mgt.staff.index')->name('index');
            Route::livewire('/deactivated', 'pages::users-mgt.staff.deleted')->name('deleted');
        });

        Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
        Route::livewire('/dashboard2', 'pages::dashboard2')->name('dashboard2');
        Route::livewire('/logs', 'pages::logs')->name('logs');
    });

// Guard area routes
Route::middleware(['auth', 'can:access-guard-area'])
    ->prefix('guard')
    ->name('guard.')
    ->group(function () {
        Route::prefix('violations')->name('violations.')->group(function () {
            Route::livewire('/create', 'pages::violations.guard.create')->name('create');
            Route::livewire('/recent', 'pages::violations.guard.recent')->name('recent');
            Route::livewire('/requests', 'pages::violations.guard.requests')->name('requests');
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

Route::livewire('/email/verify', 'pages::auth.verify-email')->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {

    $request->fulfill();

    return redirect()->route('student.violations.index');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


/*Route::livewire('/test', 'pages::test');
Route::livewire('/table', 'pages::tabletest');*/
