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
                'email' => 'john@example.com',
                'password' => 'doctor123',
                'specialization' => 'Pediatrics',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Michael',
                'lastname' => 'Garcia',
                'email' => 'michael@example.com',
                'password' => 'doctor123',
                'specialization' => 'General Medicine',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Sarah',
                'lastname' => 'Lim',
                'email' => 'sarah@example.com',
                'password' => 'doctor123',
                'specialization' => 'General Medicine',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'James',
                'lastname' => 'Torres',
                'email' => 'james@example.com',
                'password' => 'doctor123',
                'specialization' => 'Internal Medicine',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Patricia',
                'lastname' => 'Fernandez',
                'email' => 'patricia@example.com',
                'password' => 'doctor123',
                'specialization' => 'General Surgeon',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'role' => $user['role'],
                    'specialization' => $user['specialization'] ?? null, 
                    'status' => 'active',
                    'password_hash' => Hash::make($user['password']),
                    'account_activated' => 1,
                    'is_first_login' => 1,
                ]
            );
        }
    }
}