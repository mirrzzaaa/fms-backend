<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Administrator (IT / Document Controller)
        User::firstOrCreate(
            ['email' => 'admin@liongroup.co.id'],
            [
                'name' => 'FMS Administrator',
                'password' => Hash::make('LionGroup2026!'),
                'role' => 'admin',
            ]
        );

        // Akun Viewer (Auditor / Staff Operational)
        User::firstOrCreate(
            ['email' => 'viewer@liongroup.co.id'],
            [
                'name' => 'FMS Viewer',
                'password' => Hash::make('LionGroup2026!'),
                'role' => 'viewer',
            ]
        );
    }
}