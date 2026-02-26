<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View {
//     return view('welcome');
// })->name('home');
// Route::view('dashboard', 'dashboard')->name('dashboard')->middleware(['auth', 'verified']);

Route::redirect("/", "/buchungen");

Route::livewire("buchung/ok", "pages::buchung.ok")->name("buchung.ok");
Route::livewire("emailverifikation/{emailb64}", "pages::buchung.emailverifikation")->name("emailverifikation");

Route::livewire("buchungen", "pages::buchungen")->name("buchungen");
Route::livewire("tkbuchung", "pages::technik.create")->name("tkbuchung");
Route::livewire("rfsabuchung", "pages::rfsa.create")->name("rfsabuchung");
Route::livewire("rfsfbuchung", "pages::rfsf.create")->name("rfsfbuchung");
Route::livewire("rfsfpbuchung", "pages::rfsfp.create")->name("rfsfpbuchung");

Route::livewire("phpinfo", "pages::phpinfo")->name("phpinfo");

require __DIR__ . '/settings.php';
