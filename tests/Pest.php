<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature', 'Browser');

pest()->tia()->defaultBranch('master');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function copyTestDatabaseOnce(): void
{
    static $copied = false;

    if ($copied) {
        return;
    }

    $sourcePath = database_path('database.test.sqlite');

    if (! file_exists($sourcePath)) {
        return;
    }

    $pdo = Illuminate\Support\Facades\DB::connection()->getPdo();

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
        $pdo->exec('ATTACH DATABASE '.$pdo->quote($sourcePath).' AS disk_db');
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

    if (Illuminate\Support\Facades\DB::transactionLevel() > 0) {
        Illuminate\Support\Facades\DB::commit();
        Illuminate\Support\Facades\DB::beginTransaction();
    }

    $copied = true;
}

function something()
{
}
