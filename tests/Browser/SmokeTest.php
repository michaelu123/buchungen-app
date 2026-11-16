<?php

beforeEach(function () {
    copyTestDatabaseOnce();
});

test('booking overview page renders correctly', function () {
    visit('/buchungen')
        ->assertNoSmoke()
        ->assertSee('Anmeldungsformulare zu Kursen des ADFC München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

// test('public booking pages load without JavaScript errors', function (string $path) {
//     visit($path)
//         ->assertNoSmoke()
//         ->assertNoJavaScriptErrors();
// })->with([
//     '/buchungen',
//     '/codierbuchung',
//     '/skbuchung',
//     '/rfsabuchung',
//     '/rfsfpbuchung',
//     '/tkbuchung',
// ]);

test('RFSA page renders correctly', function () {
    visit('/rfsabuchung')
        ->assertNoSmoke()
        ->assertSee('Anmeldung zu einem Kurs für Anfänger:innen der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('RFSFP page renders correctly', function () {
    visit('/rfsfpbuchung')
        ->assertNoSmoke()
        ->assertSee('Anmeldung zum Kurs "Fahrpraxis für Anfänger:innen" der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('TK page renders correctly', function () {
    visit('/tkbuchung')
        ->assertNoSmoke()
        ->assertSee('Anmeldung zu einem Technikkurs')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('Codier page renders correctly', function () {
    visit('/codierbuchung')
        ->assertNoSmoke()
        ->assertSee('Buchung eines Termins im Radlerhaus für eine Fahrrad-Codierung')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('SK page renders correctly', function () {
    visit('/skbuchung')
        ->assertNoSmoke()
        ->assertSee('Bestellung einer Saisonkarte')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});
