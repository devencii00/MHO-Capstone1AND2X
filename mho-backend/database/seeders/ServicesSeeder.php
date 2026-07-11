<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
    $services = [
   
            [
                'service_name' => 'Pediatrics',
                'description' => 'Routine newborn check-up, growth monitoring, and developmental milestone assessment.',
                'price' => 500.00,
                'duration_minutes' => 15,
            ],
            [
                'service_name' => 'Pediatrics',
                'description' => 'Pediatric immunization, allergy assessment, and acute pediatric illness treatment.',
                'price' => 400.00,
                'duration_minutes' => 20,
            ],


            [
                'service_name' => 'General Medicine',
                'description' => 'General medical consultation, physical check-up, and basic diagnostic review.',
                'price' => 300.00,
                'duration_minutes' => 15,
            ],
            [
                'service_name' => 'General Medicine',
                'description' => 'Issuance of medical certificates, work fitness evaluation, and preventative lifestyle counseling.',
                'price' => 200.00,
                'duration_minutes' => 20,
            ],

      
            [
                'service_name' => 'Internal Medicine',
                'description' => 'Management and check-up for chronic conditions like hypertension, diabetes, and cardiovascular risk factors.',
                'price' => 600.00,
                'duration_minutes' => 20,
            ],
            [
                'service_name' => 'Internal Medicine',
                'description' => 'Comprehensive internal organ system review and diagnostic evaluation for adult patients.',
                'price' => 550.00,
                'duration_minutes' => 15,
            ],

          
            [
                'service_name' => 'General Surgeon',
                'description' => 'Pre-operative evaluation, surgical risk assessment, and minor minor operating room case consultation.',
                'price' => 700.00,
                'duration_minutes' => 15,
            ],
            [
                'service_name' => 'General Surgeon',
                'description' => 'Post-operative check-up, surgical wound care, stitch removal, and healing progress monitoring.',
                'price' => 450.00,
                'duration_minutes' => 20,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['service_name' => $service['service_name']],
                $service
            );
        }
    }
}
