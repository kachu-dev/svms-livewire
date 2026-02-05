<?php

use Illuminate\Support\Facades\Route;

Route::livewire('/violations/create', 'pages::violations.create');
Route::livewire('/violations', 'pages::violations.index');

Route::get('/', function (): never {
    $students = DB::connection('school_db')->table('students')->get();
    dd($students);
});
