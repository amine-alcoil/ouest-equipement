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
        $email = config('services.admin.email');
        $password = config('services.admin.password');

        if ($email && $password) {
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => 'Admin',
                    'password' => bcrypt($password),
                    'role' => 'admin',
                    'status' => 'actif',
                ]
            );
        } else {
            // Log a warning if seeding failed due to missing env variables
            \Illuminate\Support\Facades\Log::warning('Seeding skipped: ADMIN_EMAIL or ADMIN_PASSWORD not set in environment.');
        }
    }
}
