<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $yr = now()->year;
        DB::table('sk_basisdaten')->insert([
            'jahr' => $yr,
            'betrag' => 22,
            'offen' => false,
            'sknummer' => 1,
            'gueltigab' => "1. März " . $yr,
            'gueltigbis' => "28. Februar " . $yr,
        ]);
    }
}
