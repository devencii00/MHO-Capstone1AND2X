<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'firstname' => 'Juan',
                'lastname' => 'Dela Cruz',
                'email' => 'patient1@test.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Maria',
                'lastname' => 'Santos',
                'email' => 'patient2@test.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Pedro',
                'lastname' => 'Reyes',
                'email' => 'patient3@test.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Ana',
                'lastname' => 'Garcia',
                'email' => 'patient4@test.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Mark',
                'lastname' => 'Lopez',
                'email' => 'patient5@test.com',
                'password' => 'patient123',
            ],
            [
    'firstname' => 'Carla',
    'lastname' => 'Mendoza',
    'email' => 'patient6@test.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Joshua',
    'lastname' => 'Flores',
    'email' => 'patient7@test.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Nicole',
    'lastname' => 'Castillo',
    'email' => 'patient8@test.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Kevin',
    'lastname' => 'Ramos',
    'email' => 'patient9@test.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Angela',
    'lastname' => 'Navarro',
    'email' => 'patient10@test.com',
    'password' => 'patient123',
],
        ];

        foreach ($patients as $patient) {
            User::firstOrCreate(
                ['email' => $patient['email']],
                [
                    'firstname' => $patient['firstname'],
                    'lastname' => $patient['lastname'],
                    'role' => 'patient',
                    'status' => 'active',
                    'password_hash' => Hash::make($patient['password']),
                    'account_activated' => 1,
                    'is_first_login' => 1,
                ]
            );
        }
    }
}