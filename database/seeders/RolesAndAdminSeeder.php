<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RolesAndAdminSeeder extends Seeder
{
    public function run(): void {
        $admin = Role::firstOrCreate(['name'=>'admin']);
        $editor= Role::firstOrCreate(['name'=>'editor']);
        $user  = Role::firstOrCreate(['name'=>'user']);

        // contoh admin
        $u = User::firstOrCreate(
            ['email'=>'admin@example.com'],
            ['name'=>'Admin','password'=>bcrypt('password')]
        );
        $u->syncRoles(['admin']);

        // contoh editor
        $e = User::firstOrCreate(
            ['email'=>'editor@example.com'],
            ['name'=>'Editor','password'=>bcrypt('password')]
        );
        $e->syncRoles(['editor']);

        // contoh user
        $usr = User::firstOrCreate(
            ['email'=>'user@example.com'],
            ['name'=>'User','password'=>bcrypt('password')]
        );
        $usr->syncRoles(['user']);
    }
}