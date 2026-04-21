<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
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
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['Admin']]->id,
        ]);

        $user = User::factory()->create([
            'name' => 'Codierung',
            'email' => 'codier@admin.com',
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['Codier']]->id,
        ]);

        $user = User::factory()->create([
            'name' => 'RFS',
            'email' => 'rfs@admin.com',
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['RFS']]->id,
        ]);

        $user = User::factory()->create([
            'name' => 'RFSA',
            'email' => 'rfsa@admin.com',
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['RFSA']]->id,
        ]);

        $user = User::factory()->create([
            'name' => 'RFSF',
            'email' => 'rfsf@admin.com',
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['RFSF']]->id,
        ]);

        $user = User::factory()->create([
            'name' => 'RFSFP',
            'email' => 'rfsfp@admin.com',
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['RFSFP']]->id,
        ]);

        $user = User::factory()->create([
            'name' => 'Technik',
            'email' => 'tk@admin.com',
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['Technik']]->id,
        ]);

        $user = User::factory()->create([
            'name' => 'Saisonkarten',
            'email' => 'sk@admin.com',
            'password' => 'xxxx1234',
        ]);
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $roles[Role::ROLES['Saisonkarten']]->id,
        ]);
    }
}
