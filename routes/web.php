<?php

use App\Livewire\Company;
use App\Livewire\Employee;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/companies', Company::class)
    ->middleware(['auth'])
    ->name('companies');

Route::get('/employees', Employee::class)
    ->middleware(['auth'])
    ->name('employees');

require __DIR__.'/auth.php';
