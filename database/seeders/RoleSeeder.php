<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    private const ROLES = [
        'Admin' => 'ADMIN',
    ];
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [];
        foreach (Role::ROLES as $key => $value) {
            $roles[$value] = Role::factory()->create(["name" => $value]);
        }
        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['Admin']]->id,
        ]);
    }
}
