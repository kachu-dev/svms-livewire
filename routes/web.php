<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/login', 'pages::auth.login')->middleware('guest')->name('login');

Route::livewire('/home', 'pages::auth.login')->name('home');

Route::get('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();

    return redirect('/login');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'can:access-staff-area' ])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {

        // Violations routes
        Route::livewire('/violations', 'pages::violations.staff.index')
            ->name('violations.index');
        Route::livewire('/violations/deleted', 'pages::violations.staff.deleted')
            ->name('violations.deleted');
        Route::livewire('/violations/create', 'pages::violations.staff.create')
            ->name('violations.create');
        Route::livewire('/violations/{violation}', 'pages::violations.staff.detail')
            ->name('violations.detail');

        // Policy routes
        Route::livewire('/policy', 'pages::policy.staff.index')
            ->name('policy.index');
        Route::livewire('/policy/deactivated', 'pages::policy.staff.deleted')
            ->name('policy.deleted');

        // Users routes
        Route::livewire('/users', 'pages::users-mgt.staff.index')
            ->name('users-mgt.index');
        Route::livewire('/users/deactivated', 'pages::users-mgt.staff.deleted')
            ->name('users-mgt.deleted');

        Route::livewire('/display-policy', 'pages::policy.student.display-policy')
            ->name('policy.display-policy');
    });

Route::middleware(['auth', 'can:access-guard-area'])
    ->prefix('guard')
    ->name('guard.')
    ->group(function () {

        Route::livewire('/violations/create', 'pages::violations.guard.create')
            ->name('violations.create');
        Route::livewire('/violations/recent', 'pages::violations.guard.recent')
            ->name('violations.recent');
    });
