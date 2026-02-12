<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::firstOrCreate(
    ['email' => env('ADMIN_EMAIL')],
    [
        'name' => 'Admin',
        'password' => bcrypt(env('ADMIN_PASSWORD')),
        'role' => 'admin',
        'status' => 'actif',
    ]
);
    }
}
