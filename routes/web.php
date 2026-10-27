<?php

use App\Http\Controllers\SkDownloadController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function (): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View {
//     return view('welcome');
// })->name('home');
// Route::view('dashboard', 'dashboard')->name('dashboard')->middleware(['auth', 'verified']);

Route::redirect("/", "/buchungen");

Route::livewire("buchung/ok", "pages::buchung.ok")->name("buchung.ok");
Route::livewire("buchung/notok", "pages::buchung.notok")->name("buchung.notok");
Route::livewire("emailverifikation/{param}", "pages::buchung.emailverifikation")->name("emailverifikation");

Route::livewire("buchungen", "pages::buchungen")->name("buchungen");
Route::livewire("tkbuchung", "pages::technik.create")->name("tkbuchung");
Route::livewire("rfsabuchung", "pages::rfsa.create")->name("rfsabuchung");
// Route::livewire("rfsfbuchung", "pages::rfsf.create")->name("rfsfbuchung");
Route::livewire("rfsfpbuchung", "pages::rfsfp.create")->name("rfsfpbuchung");
Route::livewire("codierbuchung", "pages::codier.create")->name("codierbuchung");
Route::livewire("codierabmeldung/{encid}", "pages::codier.abmelden")->name("codier.abmelden");
Route::livewire("skbuchung", "pages::saisonkarten.create")->name("skbuchung");
Route::get("skdownload/{encNr}/{type}", [SkDownloadController::class, "download"])->name("skdownload");

Route::livewire("warteliste_rfsa", "pages::rfs_wartelisten.create")->name("warteliste_rfsa");
Route::livewire("warteliste_rfsfg", "pages::rfs_wartelisten.create")->name("warteliste_rfsfg");
Route::livewire("warteliste_rfsfa", "pages::rfs_wartelisten.create")->name("warteliste_rfsfa");
Route::livewire("warteliste_rfsfs", "pages::rfs_wartelisten.create")->name("warteliste_rfsfs");
Route::livewire("warteliste_rfsfp", "pages::rfs_wartelisten.create")->name("warteliste_rfsfp");
Route::livewire("warteliste_rfsf", "pages::rfsf.wartelisten")->name("wartelisten_rfsf");
Route::livewire("warteliste_ok", "pages::rfs_wartelisten.ok")->name("warteliste.ok");

Route::livewire("phpinfo", "pages::phpinfo")->name("phpinfo");

require __DIR__ . '/settings.php';
