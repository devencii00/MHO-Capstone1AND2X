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
                'email' => 'angela@reception.com',
                'password' => 'global123',
            ],
            [
                'role' => 'receptionist',
                'firstname' => 'Karen',
                'lastname' => 'Lopez',
                'email' => 'karen@reception.com',
                'password' => 'global123',
            ],

            // Doctors
            [
                'role' => 'doctor',
                'firstname' => 'John',
                'lastname' => 'Dela Cruz',
                'email' => 'john@doctor.com',
                'password' => 'global123',
                'designation' => 'Pediatrics',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Michael',
                'lastname' => 'Garcia',
                'email' => 'michael@doctor.com',
                'password' => 'global123',
                'designation' => 'General Medicine',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Sarah',
                'lastname' => 'Lim',
                'email' => 'sarah@doctor.com',
                'password' => 'global123',
                'designation' => 'General Medicine',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'James',
                'lastname' => 'Torres',
                'email' => 'james@doctor.com',
                'password' => 'global123',
                'designation' => 'Internal Medicine',
            ],
            [
                'role' => 'doctor',
                'firstname' => 'Patricia',
                'lastname' => 'Fernandez',
                'email' => 'patricia@doctor.com',
                'password' => 'global123',
                'designation' => 'General Surgeon',
            ],

            // Laboratory Personnel
            [
                'role' => 'laboratory_personnel',
                'firstname' => 'Miguel',
                'lastname' => 'Santos',
                'email' => 'miguel@lab.com',
                'password' => 'global123',
                'designation' => 'Medical Technologist',
            ],
            [
                'role' => 'laboratory_personnel',
                'firstname' => 'Rosa',
                'lastname' => 'Aquino',
                'email' => 'rosa@lab.com',
                'password' => 'global123',
                'designation' => 'Radiologic Technologist',
            ],
            [
                'role' => 'laboratory_personnel',
                'firstname' => 'Danilo',
                'lastname' => 'Villanueva',
                'email' => 'danilo@lab.com',
                'password' => 'global123',
                'designation' => 'Laboratory Assistant',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['email' => $user['email']],
                [
                    'firstname' => $user['firstname'],
                    'lastname' => $user['lastname'],
                    'role' => $user['role'],
                    'designation' => $user['designation'] ?? null, 
                    'status' => 'active',
                    'password_hash' => Hash::make($user['password']),
                    'account_activated' => 1,
                    'is_first_login' => 1,
                ]
            );
        }
    }
}