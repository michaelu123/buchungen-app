<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::livewire("buchung/ok", "pages::buchung.ok")->name("buchung.ok");
Route::livewire("tkbuchung", "pages::technik.create")->name("tkbuchung");

require __DIR__ . '/settings.php';
