<?php

use App\Models\RFSWartelisten\BasisDaten;
use Illuminate\Support\Facades\DB;

function copyTestDatabaseOnce(): void
{
    static $copied = false;

    if ($copied) {
        return;
    }

    $sourcePath = database_path('database.test.sqlite');

    if (!file_exists($sourcePath)) {
        return;
    }

    $pdo = DB::connection()->getPdo();

    // If data was already committed to memory, skip copying
    try {
        if ((int) $pdo->query('SELECT COUNT(*) FROM main.rfs_basisdaten')->fetchColumn() > 0) {
            $copied = true;

            return;
        }
    } catch (Throwable) {
        // Table doesn't exist yet
    }

    try {
        $pdo->exec('ATTACH DATABASE ' . $pdo->quote($sourcePath) . ' AS disk_db');
    } catch (Throwable) {
        // Already attached
    }

    $stmt = $pdo->query("SELECT name FROM disk_db.sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $stmt->closeCursor();

    $pdo->exec('PRAGMA foreign_keys = OFF');
    foreach ($tables as $table) {
        try {
            $pdo->exec("INSERT OR REPLACE INTO main.{$table} SELECT * FROM disk_db.{$table}");
        } catch (Throwable) {
            //
        }
    }
    $pdo->exec('PRAGMA foreign_keys = ON');

    $bd = BasisDaten::all()->first();
    $bd->update([
        "rfsa_warteliste" => 1,
        "rfsfg_warteliste" => 1,
        "rfsfa_warteliste" => 1,
        "rfsfs_warteliste" => 1,
        "rfsfp_warteliste" => 1,
    ]);


    if (DB::transactionLevel() > 0) {
        DB::commit();
        DB::beginTransaction();
    }

    $copied = true;
}

beforeEach(function () {
    copyTestDatabaseOnce();
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
