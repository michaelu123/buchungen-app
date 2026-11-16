<?php

use App\Models\RFSFP\Buchung;
use App\Models\RFSFP\Kurs;
use App\Models\RFSWartelisten\BasisDaten;

beforeEach(function () {
    copyTestDatabaseOnce();
    // Ensure waiting list is deactivated so the course booking form is visible
    BasisDaten::first()?->update(['rfsfp_warteliste' => 0]);
});

test('admin creates a course, guest books it, and admin verifies reduced places', function () {
    // -------------------------------------------------------------
    // a) Login as admin
    // -------------------------------------------------------------
    $page = visit('/admin/login');
    $page->assertSee('Sign in');
    $page
        ->type('#form\\.email', 'admin@admin.com')
        ->type('#form\\.password', 'xxxx1234')
        ->submit()
        ->assertSee('Dashboard');

    // -------------------------------------------------------------
    // b) As admin, create a new course
    // -------------------------------------------------------------
    // You can create it via the Filament UI:
    $page = visit('/admin/rfsfpkurse/create')
        ->type('#form\\.nummer', 'RFSFP-TEST-01')
        ->type('#form\\.uhrzeit', '14:00 - 17:00')
        ->type('#form\\.kursplätze', '1');

    $datum = now()->addDays(2)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.datum');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$datum}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($datum);

    $eday = now()->addDays(3)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.ersatztermin');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$eday}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($eday);

    $page->click('button[wire\:target="create"]')
        ->assertSee('Edit RFSFP-TEST-01');
    $kurs = Kurs::where("nummer", "RFSFP-TEST-01")->first();
    $kursId = $kurs->id;
    $initialRestplaetze = $kurs->restplätze;
    expect($initialRestplaetze)->toBe(1);

    // could create kurs also like this:
    // $datum = now()->addDays(1);
    // $eday = now()->addDays(2);
    // $kurs = Kurs::create([
    //     'nummer' => 'RFSFP-TEST-01',
    //     'uhrzeit' => '14:00 - 17:00',
    //     'datum' => $datum,
    //     'ersatztermin' => $eday,
    //     'kursplätze' => 1,
    //     'restplätze' => 1,
    //     'rvp' => 'RVP',
    //     'trainer' => 'Trainer1',
    //     'co_trainer' => 'Trainer2',
    //     'kommentar' => 'Kommentar',
    // ]);
    // $kursId = $kurs->id;
    // $initialRestplaetze = Kurs::where('nummer', 'RFSFP-TEST-01')->value('restplätze'); // 1
    // expect($initialRestplaetze)->toBe(1);

    // -------------------------------------------------------------
    // c) As a not logged-in user, view the booking page and book
    // -------------------------------------------------------------
    // Log out or navigate as a guest
    $guestPage = visit('/rfsfpbuchung')
        ->assertSee('Anmeldung zum Kurs "Fahrpraxis für Anfänger:innen" der Radfahrschule München')
        ->assertSee('RFSFP-TEST-01: 14:00 - 17:00')
        ->assertSee('freie Plätze: 1')
        ->type('#form\\.email', 'max@example.com')
        ->click('#form\\.kurs_id-' . $kursId) // Select the created course
        ->select('#form\\.anrede', 'Herr')
        ->type('#form\\.vorname', 'Max')
        ->type('#form\\.nachname', 'Mustermann')
        ->type('#form\\.postleitzahl', '80331')
        ->type('#form\\.ort', 'München')
        ->type('#form\\.strasse', 'Musterstr.')
        ->type('#form\\.hsnr', '1')
        ->type('#form\\.telefonnr', '08912345678')
        ->type('#form\\.kontoinhaber', 'ich')
        ->type('#form\\.iban', 'DE91100000000123456789')
        ->check('#form\\.lastschriftok')
        ->type('#form\\.mitteilung', 'Test Mitteilung')
        ->submit();
    $guestPage->assertSee('Bestätigung');
    // visit('/admin/rfsfpbuchungen')->assertSee("RFSFP-Buchungen");

    // -------------------------------------------------------------
    // d) As admin, verify booking arrived and free places decreased by 1
    // -------------------------------------------------------------
    // 1. Verify in database:
    $updatedKurs = Kurs::where('nummer', 'RFSFP-TEST-01')->first();
    expect($updatedKurs->restplätze)->toBe($initialRestplaetze - 1);
    $buchung = Buchung::where('email', 'max@example.com')->first();
    expect($buchung)->toBeTruthy();
    $buchungId = $buchung->id;

    // 2. Verify in Filament Admin UI:
    visit('/admin/rfsfpbuchungen/' . $buchungId . '/edit')
        ->assertSee('Edit max@example.com');
    visit('/admin/rfsfpkurse/RFSFP-TEST-01/edit')
        ->assertSee('Edit RFSFP-TEST-01');

    $guestPage = visit('/rfsfpbuchung')
        ->assertSee('Anmeldung zum Kurs "Fahrpraxis für Anfänger:innen" der Radfahrschule München')
        ->assertSee('RFSFP-TEST-01')
        ->assertSee('ausgebucht');
});
