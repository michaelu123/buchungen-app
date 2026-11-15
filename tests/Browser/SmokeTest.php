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

    if (DB::transactionLevel() > 0) {
        DB::commit();
        DB::beginTransaction();
    }

    $copied = true;
}

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
