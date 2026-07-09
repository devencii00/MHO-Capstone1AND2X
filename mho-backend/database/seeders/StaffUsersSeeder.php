<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffUsersSeeder extends Seeder
{
    public function run(): void
    {
        $users = [

            // Receptionists
            [
                'role' => 'receptionist',
                'firstname' => 'Angela',
                'lastname' => 'Reyes',
                'email' => 'receptionist@example.com',
                'password' => 'reception123',
            ],
            [
                'role' => 'receptionist',
                'firstname' => 'Karen',
                'lastname' => 'Lopez',
                'email' => 'receptionist2@example.com',
                'password' => 'reception123',
            ],

            // Doctors
            [
                'role' => 'doctor',
                'firstname' => 'John',
                'lastname' => 'Dela Cruz',
                'email' => 'doctor1@example.com',
                'password' => 'doctor123',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Michael',
                'lastname' => 'Garcia',
                'email' => 'doctor2@example.com',
                'password' => 'doctor123',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Sarah',
                'lastname' => 'Lim',
                'email' => 'doctor3@example.com',
                'password' => 'doctor123',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'James',
                'lastname' => 'Torres',
                'email' => 'doctor4@example.com',
                'password' => 'doctor123',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Patricia',
                'lastname' => 'Fernandez',
                'email' => 'doctor5@example.com',
                'password' => 'doctor123',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'role' => $user['role'],
                    'status' => 'active',
                    'password_hash' => Hash::make($user['password']),
                    'account_activated' => 1,
                    'is_first_login' => 1,
                ]
            );
        }
    }
}