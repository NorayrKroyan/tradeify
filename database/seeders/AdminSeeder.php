<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password) {
            $this->command?->warn('ADMIN_EMAIL or ADMIN_PASSWORD is missing; administrator was not created.');
            return;
        }

        $admin = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => env('ADMIN_NAME', 'Platform Administrator'),
                'password' => Hash::make($password),
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        $admin->syncRoles(['super-admin']);
    }
}
