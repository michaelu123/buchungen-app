<?php

use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use App\Models\RFSWartelisten\BasisDaten;

beforeEach(function () {
    copyTestDatabaseOnce();
    // Ensure waiting list is deactivated so the course booking form is visible
    BasisDaten::first()?->update(['rfsa_warteliste' => 0]);
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
    //   MUH: this does not work for tag1 up to ersatztermin2. We would have to click on the field with
    //   $page->click('button[aria-label="Tag 1"]');
    //   and then click around in the popped up calendar for the next days, perhaps changing
    //   even the month or year for the next few days. Too complicated.
    // $page = visit('/admin/rfsakurse/create')
    //   ->type('#form\\.nummer', 'RFSA-TEST-01')
    //   ->type('#form\\.uhrzeit', '14:00 - 17:00')
    //   ->type('#form\\.tag1', now()->addDays(2)->format('Y-m-d'))
    //   ->type('#form\\.tag2', now()->addDays(3)->format('Y-m-d'))
    //   ->type('#form\\.tag3', now()->addDays(4)->format('Y-m-d'))
    //   ->type('#form\\.tag4', now()->addDays(5)->format('Y-m-d'))
    //   ->type('#form\\.ersatztermin1', now()->addDays(6)->format('Y-m-d'))
    //   ->type('#form\\.ersatztermin2', now()->addDays(7)->format('Y-m-d'))
    //   ->type('#form\\.kursplätze', '1')
    //   ->submit()
    //   ->assertSee('Edit RFSA-TEST-01');

    $day1 = now()->addDays(1);
    $day2 = now()->addDays(2);
    $day3 = now()->addDays(3);
    $day4 = now()->addDays(4);
    $eday1 = now()->addDays(5);
    $eday2 = now()->addDays(6);
    $kurs = Kurs::create([
        'nummer' => 'RFSA-TEST-01',
        'uhrzeit' => '14:00 - 17:00',
        'tag1' => $day1,
        'tag2' => $day2,
        'tag3' => $day3,
        'tag4' => $day4,
        'ersatztermin1' => $eday1,
        'ersatztermin2' => $eday2,
        'kursplätze' => 1,
        'restplätze' => 1,
        'rvp' => 'RVP',
        'lehrer' => 'Lehrer1',
        'co_lehrer' => 'Lehrer2',
        'co_lehrer2' => 'Lehrer3',
        'kommentar' => 'Kommentar',
    ]);
    $kursId = $kurs->id;
    $initialRestplaetze = Kurs::where('nummer', 'RFSA-TEST-01')->value('restplätze'); // 1

    // -------------------------------------------------------------
    // c) As a not logged-in user, view the booking page and book
    // -------------------------------------------------------------
    // Log out or navigate as a guest
    $guestPage = visit('/rfsabuchung')
        ->assertSee('Anmeldung zu einem Kurs für Anfänger:innen')
        ->assertSee('RFSA-TEST-01: 14:00 - 17:00')
        ->assertSee('freie Plätze: 1')
        ->type('#form\\.email', 'max@example.com')
        ->click('#form\\.kurs_id-'.$kursId) // Select the created course
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
    // visit('/admin/rfsabuchungen')->assertSee("RFSA-Buchungen");

    // -------------------------------------------------------------
    // d) As admin, verify booking arrived and free places decreased by 1
    // -------------------------------------------------------------
    // 1. Verify in database:
    $updatedKurs = Kurs::where('nummer', 'RFSA-TEST-01')->first();
    expect($updatedKurs->restplätze)->toBe($initialRestplaetze - 1);
    $buchung = Buchung::where('email', 'max@example.com')->first();
    expect($buchung)->toBeTruthy();
    $buchungId = $buchung->id;

    // 2. Verify in Filament Admin UI:
    visit('/admin/rfsabuchungen/'.$buchungId.'/edit')
        ->assertSee('Edit max@example.com');
    visit('/admin/rfsakurse/RFSA-TEST-01/edit')
        ->assertSee('Edit RFSA-TEST-01');

    $guestPage = visit('/rfsabuchung')
        ->assertSee('Anmeldung zu einem Kurs für Anfänger:innen')
        ->assertSee('RFSA-TEST-01')
        ->assertSee('ausgebucht');
});
