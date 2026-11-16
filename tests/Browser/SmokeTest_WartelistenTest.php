<?php

use App\Models\RFSWartelisten\BasisDaten;

beforeEach(function () {
    copyTestDatabaseOnce();
    $bd = BasisDaten::all()->first();
    $bd->update([
        'rfsa_warteliste' => 1,
        'rfsfg_warteliste' => 1,
        'rfsfa_warteliste' => 1,
        'rfsfs_warteliste' => 1,
        'rfsfp_warteliste' => 1,
    ]);

});

test('RFSA Warteliste page renders correctly', function () {
    visit('/rfsabuchung')
        ->assertNoSmoke()
        ->assertSee('Warteliste für Anfängerkurse der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('RFSFP Warteliste page renders correctly', function () {
    visit('/rfsfpbuchung')
        ->assertNoSmoke()
        ->assertSee('Warteliste für Fahrpraxiskurse der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('warteliste_rfsa page renders correctly', function () {
    visit('/warteliste_rfsa')
        ->assertNoSmoke()
        ->assertSee('Warteliste für Anfängerkurse der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('warteliste_rfsfg page renders correctly', function () {
    visit('/warteliste_rfsfg')
        ->assertNoSmoke()
        ->assertSee('Warteliste für Fahrsicherheitstraining-Grundkurse der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('warteliste_rfsfa page renders correctly', function () {
    visit('/warteliste_rfsfa')
        ->assertNoSmoke()
        ->assertSee('Warteliste für Fahrsicherheitstraining-Aufbaukurse der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('warteliste_rfsfs page renders correctly', function () {
    visit('/warteliste_rfsfs')
        ->assertNoSmoke()
        ->assertSee('Warteliste für Fahrsicherheitstraining-Senior:innenkurse der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('warteliste_rfsfp page renders correctly', function () {
    visit('/warteliste_rfsfp')
        ->assertNoSmoke()
        ->assertSee('Warteliste für Fahrpraxiskurse der Radfahrschule München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('warteliste_rfsf page renders correctly', function () {
    visit('/warteliste_rfsf')
        ->assertNoSmoke()
        ->assertSee('Warteliste zu Fahrsicherheits-Kursen des ADFC München')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});

test('warteliste_ok page renders correctly', function () {
    visit('/warteliste_ok')
        ->assertNoSmoke()
        ->assertSee('Bestätigung')
        ->assertSee('Sie wurden in die Warteliste eingetragen!')
        ->assertScreenshotMatches()
        ->assertNoJavaScriptErrors();
});
