<?php

use Illuminate\Support\Facades\Route;

Route::middleware([/* 'auth', 'can:access-staff-area' */])
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

        // Policy routes
        Route::livewire('/policy', 'pages::policy.staff.index')
            ->name('policy.index');
        Route::livewire('/policy/deleted', 'pages::policy.staff.deleted')
            ->name('policy.deleted');
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
