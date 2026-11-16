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
        ->type('#form\\.beginn', '22:00')
        ->type('#form\\.ende', '22:30')
        ->type('#form\\.ort', 'Radlerhaus München YYY, Platenstraße 4 80336 München');

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
    [$page1, $page2] = visit(['/codierbuchung', '/codierbuchung']);

    $page1->assertSee('Buchung eines Termins im Radlerhaus für eine Fahrrad-Codierung')
        ->assertSee('von 22:00 bis 22:30, Ort: Radlerhaus München YYY, Platenstraße 4 80336 München, freie Plätze: 1.')
        ->click('#form\\.termin_id-' . $terminId)
        ->wait(1)
        ->click("#form\\.uhrzeit-22\\:00")
        ->select('#form\\.anrede', 'Herr')
        ->type('#form\\.vorname', 'Max1')
        ->type('#form\\.nachname', 'Mustermann')
        ->type('#form\\.postleitzahl', '80331')
        ->type('#form\\.ort', 'München')
        ->type('#form\\.strasse', 'Musterstr.')
        ->type('#form\\.hsnr', '1')
        ->type('#form\\.telefonnr', '08912345678')
        ->type('#form\\.email', 'max1@example.com');

    $page2->assertSee('Buchung eines Termins im Radlerhaus für eine Fahrrad-Codierung')
        ->assertSee('von 22:00 bis 22:30, Ort: Radlerhaus München YYY, Platenstraße 4 80336 München, freie Plätze: 1.')
        ->click('#form\\.termin_id-' . $terminId)
        ->wait(1)
        ->click("#form\\.uhrzeit-22\\:00")
        ->select('#form\\.anrede', 'Herr')
        ->type('#form\\.vorname', 'Max2')
        ->type('#form\\.nachname', 'Mustermann')
        ->type('#form\\.postleitzahl', '80331')
        ->type('#form\\.ort', 'München')
        ->type('#form\\.strasse', 'Musterstr.')
        ->type('#form\\.hsnr', '2')
        ->type('#form\\.telefonnr', '08912345678')
        ->type('#form\\.email', 'max2@example.com');

    $pages = [$page1, $page2];
    shuffle($pages);
    foreach ($pages as $page) {
        $page->script(<<<'JS'
    (() => {
        const form = document.querySelector('form');
        const button = form?.querySelector('button[type="submit"]');

        if (!form || !button) {
            throw new Error('Booking form or submit button not found');
        }

        form.requestSubmit(button);
    })()
    JS);
    }

    // wait for requests to return, are there other methods?
    $page1->assertSee("Buchung");
    $page2->assertSee("Buchung");

    $path1 = parse_url($page1->url(), PHP_URL_PATH);
    $path2 = parse_url($page2->url(), PHP_URL_PATH);
    dump("paths=" . $path1 . "," . $path2);

    if ($path1 == "/codierbuchung") {
        expect($path2)->toBe("/buchung/ok");
        $page1->assertSee("Die Uhrzeit wurde inzwischen vergeben,");
    } else {
        expect($path1)->toBe("/buchung/ok");
        expect($path2)->toBe("/codierbuchung");
        $page2->assertSee("Die Uhrzeit wurde inzwischen vergeben,");
    }

    // -------------------------------------------------------------
    // d) As admin, verify booking arrived and free places decreased by 1
    // -------------------------------------------------------------
    // 1. Verify in database:
    $buchung1 = Buchung::where('email', 'max1@example.com')->first();
    $buchung2 = Buchung::where('email', 'max2@example.com')->first();

    if ($path1 == "/codierbuchung") {
        expect($buchung2)->toBeTruthy();
        $buchungId = $buchung2->id;
        visit('/admin/codierbuchungen/' . $buchungId . '/edit')
            ->assertSee('Edit max2@example.com');
    } else {
        expect($buchung1)->toBeTruthy();
        $buchungId = $buchung1->id;
        visit('/admin/codierbuchungen/' . $buchungId . '/edit')
            ->assertSee('Edit max1@example.com');
    }

    // 2. Verify in Filament Admin UI:
    visit('/admin/codiertermine/' . $terminId . '/edit')
        ->assertSee('Edit ' . $datum);

    $guestPage = visit('/codierbuchung')
        ->assertSee('Buchung eines Termins im Radlerhaus für eine Fahrrad-Codierung')
        ->assertSee('von 22:00 bis 22:30, Ort: Radlerhaus München YYY, Platenstraße 4 80336 München, freie Plätze: 0.');
});
