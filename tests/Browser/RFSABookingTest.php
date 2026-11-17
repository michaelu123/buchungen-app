<?php

use App\Models\RFSA\Buchung;
use App\Models\RFSA\Kurs;
use App\Models\RFSWartelisten\BasisDaten;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

beforeEach(function () {
    copyTestDatabaseOnce();
    // Ensure waiting list is deactivated so the course booking form is visible
    BasisDaten::first()?->update(['rfsa_warteliste' => 0]);
});

test('admin creates a course, guest books it, and admin verifies reduced places', function () {
    // -------------------------------------------------------------
    // a) Login as admin
    // -------------------------------------------------------------
    login();
    $admin = User::where("email", "admin@admin.com")->first();
    $this->assertAuthenticatedAs($admin);

    // -------------------------------------------------------------
    // b) As admin, create a new course
    // -------------------------------------------------------------
    // You can create it via the Filament UI:
    $page = visit('/admin/rfsakurse/create')
        ->type('#form\\.nummer', 'RFSA-TEST-01')
        ->type('#form\\.uhrzeit', '14:00 - 17:00')
        ->type('#form\\.kursplätze', '1');

    $day1 = now()->addDays(2)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.tag1');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$day1}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($day1);

    $day2 = now()->addDays(3)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.tag2');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$day2}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($day2);

    $day3 = now()->addDays(4)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.tag3');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$day3}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($day3);

    $day4 = now()->addDays(5)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.tag4');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$day4}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($day4);

    $eday1 = now()->addDays(6)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.ersatztermin1');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$eday1}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($eday1);

    $eday2 = now()->addDays(7)->format('Y-m-d');
    $stateValue = $page->script(<<<JS
    (() => {
        const displayInput = document.getElementById('form.ersatztermin2');
        if (!displayInput) return 'INPUT_NOT_FOUND';
        const wrapper = displayInput.closest('[x-data]');
        const alpineData = Alpine.\$data(wrapper);
        alpineData.state = '{$eday2}';
        return alpineData.state;
    })()    
    JS);
    expect($stateValue)->toBe($eday2);

    $page->click('button[wire\:target="create"]')
        ->assertSee('Edit RFSA-TEST-01');
    $kurs = Kurs::where("nummer", "RFSA-TEST-01")->first();
    $kursId = $kurs->id;
    $initialRestplaetze = $kurs->restplätze;
    expect($initialRestplaetze)->toBe(1);

    // could create kurs also like this:
    // $day1 = now()->addDays(1);
    // $day2 = now()->addDays(2);
    // $day3 = now()->addDays(3);
    // $day4 = now()->addDays(4);
    // $eday1 = now()->addDays(5);
    // $eday2 = now()->addDays(6);
    // $kurs = Kurs::create([
    //     'nummer' => 'RFSA-TEST-01',
    //     'uhrzeit' => '14:00 - 17:00',
    //     'tag1' => $day1,
    //     'tag2' => $day2,
    //     'tag3' => $day3,
    //     'tag4' => $day4,
    //     'ersatztermin1' => $eday1,
    //     'ersatztermin2' => $eday2,
    //     'kursplätze' => 1,
    //     'restplätze' => 1,
    //     'rvp' => 'RVP',
    //     'lehrer' => 'Lehrer1',
    //     'co_lehrer' => 'Lehrer2',
    //     'co_lehrer2' => 'Lehrer3',
    //     'kommentar' => 'Kommentar',
    // ]);
    // $kursId = $kurs->id;
    // $initialRestplaetze = Kurs::where('nummer', 'RFSA-TEST-01')->value('restplätze'); // 1
    // expect($initialRestplaetze)->toBe(1);

    // -------------------------------------------------------------
    // c) As a not logged-in user, view the booking page and book
    // -------------------------------------------------------------
    // Log out or navigate as a guest
    Auth::logout();
    $this->assertGuest();

    $guestPage = visit('/rfsabuchung')
        ->assertSee('Anmeldung zu einem Kurs für Anfänger:innen')
        ->assertSee('RFSA-TEST-01: 14:00 - 17:00')
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
    // visit('/admin/rfsabuchungen')->assertSee("RFSA-Buchungen");

    // -------------------------------------------------------------
    // d) As admin, verify booking arrived and free places decreased by 1
    // -------------------------------------------------------------
    // 1. Verify in database:
    $updatedKurs = Kurs::where('nummer', 'RFSA-TEST-01')->first();
    expect($updatedKurs->restplätze)->toBe($initialRestplaetze - 1); // i.e. 0
    $buchung = Buchung::where('email', 'max@example.com')->first();
    expect($buchung)->toBeTruthy();
    $buchungId = $buchung->id;

    // 2. Check that frontend shows course as booked out
    $guestPage = visit('/rfsabuchung')
        ->assertSee('Anmeldung zu einem Kurs für Anfänger:innen')
        ->assertSee('RFSA-TEST-01')
        ->assertSee('ausgebucht');

    // 3. Verify in Filament Admin UI:
    login();
    $page = visit('/admin/rfsabuchungen/' . $buchungId . '/edit')
        ->assertSee('Edit max@example.com');
    $kursIdInForm = (int) $page->value("#form\\.kurs_id");
    expect($kursIdInForm)->toBe($kursId);
    $kursName = $page->text('select[id="form.kurs_id"] option:checked');
    expect($kursName)->toContain('RFSA-TEST-01');
    visit('/admin/rfsakurse/RFSA-TEST-01/edit')
        ->assertSee('Edit RFSA-TEST-01');
});
