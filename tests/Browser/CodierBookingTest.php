<?php

use App\Models\Codier\Buchung;
use App\Models\Codier\Termin;

beforeEach(function () {
    copyTestDatabaseOnce();
    // Ensure waiting list is deactivated so the course booking form is visible
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
    $page = visit('/admin/codiertermine/create')
        ->type('#form\\.beginn', '14:00')
        ->type('#form\\.ende', '14:30')
        ->type('#form\\.ort', 'Radlerhaus München XXX, Platenstraße 4 80336 München');

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

    $page->click('button[wire\:target="create"]')
        ->assertSee('Edit ' . $datum);
    $termin = Termin::where("datum", $datum)->first();
    $terminId = $termin->id;

    // -------------------------------------------------------------
    // c) As a not logged-in user, view the booking page and book
    // -------------------------------------------------------------
    // Log out or navigate as a guest
    $guestPage = visit('/codierbuchung')
        ->assertSee('Buchung eines Termins im Radlerhaus für eine Fahrrad-Codierung')
        ->assertSee('von 14:00 bis 14:30, Ort: Radlerhaus München XXX, Platenstraße 4 80336 München, freie Plätze: 1.')
        ->click('#form\\.termin_id-' . $terminId)
        ->wait(1)
        ->click("#form\\.uhrzeit-14\\:00")
        ->select('#form\\.anrede', 'Herr')
        ->type('#form\\.vorname', 'Max')
        ->type('#form\\.nachname', 'Mustermann')
        ->type('#form\\.postleitzahl', '80331')
        ->type('#form\\.ort', 'München')
        ->type('#form\\.strasse', 'Musterstr.')
        ->type('#form\\.hsnr', '1')
        ->type('#form\\.telefonnr', '08912345678')
        ->type('#form\\.email', 'max@example.com')
        ->submit()
        ->wait(1)
        ->assertSee('Bestätigung');

    // -------------------------------------------------------------
    // d) As admin, verify booking arrived and free places decreased by 1
    // -------------------------------------------------------------
    // 1. Verify in database:
    $buchung = Buchung::where('email', 'max@example.com')->first();
    expect($buchung)->toBeTruthy();
    $buchungId = $buchung->id;

    // 2. Verify in Filament Admin UI:
    visit('/admin/codierbuchungen/' . $buchungId . '/edit')
        ->assertSee('Edit max@example.com');
    visit('/admin/codiertermine/' . $terminId . '/edit')
        ->assertSee('Edit ' . $datum);

    $guestPage = visit('/codierbuchung')
        ->assertSee('Buchung eines Termins im Radlerhaus für eine Fahrrad-Codierung')
        ->assertSee('von 14:00 bis 14:30, Ort: Radlerhaus München XXX, Platenstraße 4 80336 München, freie Plätze: 0.');
});
