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
                'email' => 'juan@example.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Maria',
                'lastname' => 'Santos',
                'email' => 'maria@example.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Pedro',
                'lastname' => 'Reyes',
                'email' => 'pedro@example.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Ana',
                'lastname' => 'Garcia',
                'email' => 'ana@example.com',
                'password' => 'patient123',
            ],
            [
                'firstname' => 'Mark',
                'lastname' => 'Lopez',
                'email' => 'mark@example.com',
                'password' => 'patient123',
            ],
            [
    'firstname' => 'Carla',
    'lastname' => 'Mendoza',
    'email' => 'carla@example.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Joshua',
    'lastname' => 'Flores',
    'email' => 'joshua@example.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Nicole',
    'lastname' => 'Castillo',
    'email' => 'nicole@example.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Kevin',
    'lastname' => 'Ramos',
    'email' => 'kevin@example.com',
    'password' => 'patient123',
],
[
    'firstname' => 'Angela',
    'lastname' => 'Navarro',
    'email' => 'angela@example.com',
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