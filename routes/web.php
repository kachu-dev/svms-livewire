<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/violations/create', 'pages::violations.create');
Route::livewire('/violations', 'pages::violations.index');

Route::livewire('/policy/create', 'pages::policy.create');
Route::livewire('/policy', 'pages::policy.index');
Route::livewire('/policy/deactivated', 'pages::policy.deactivated');
Route::livewire('/policy/{id}/update', 'pages::policy.update');
/*
Route::get('/', function (): never {
    $students = DB::connection('school_db')->table('students')->get();
    dd($students);
});*/
