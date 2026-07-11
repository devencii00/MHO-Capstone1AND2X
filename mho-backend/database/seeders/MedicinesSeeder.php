<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicinesSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'generic_name' => 'Paracetamol',
                'brand_name' => 'Biogesic',
                'indications' => 'For mild to moderate fever, headaches, muscle aches, and general pain relief.',
                'contraindications' => 'Severe liver disease, hypersensitivity to paracetamol.',
            ],
            [
                'generic_name' => 'Amoxicillin',
                'brand_name' => 'Amoxil',
                'indications' => 'Bacterial infections such as respiratory tract infections, ear infections, urinary tract infections.',
                'contraindications' => 'Allergy to penicillin-class antibiotics, mononucleosis.',
            ],
            [
                'generic_name' => 'Ibuprofen',
                'brand_name' => 'Advil',
                'indications' => 'Inflammation, fever, headaches, menstrual cramps, and mild to moderate pain.',
                'contraindications' => 'Peptic ulcer disease, severe renal impairment, hypersensitivity to NSAIDs, pregnancy (third trimester).',
            ],
            [
                'generic_name' => 'Cetirizine',
                'brand_name' => 'Zyrtec',
                'indications' => 'Allergic rhinitis, hay fever, urticaria (hives), and other allergic reactions.',
                'contraindications' => 'Severe renal impairment, hypersensitivity to cetirizine or hydroxyzine.',
            ],
            [
                'generic_name' => 'Salbutamol',
                'brand_name' => 'Ventolin',
                'indications' => 'Asthma attacks, bronchospasm, chronic obstructive pulmonary disease (COPD).',
                'contraindications' => 'Hypersensitivity to salbutamol, tachyarrhythmias.',
            ],
            [
                'generic_name' => 'Metformin',
                'brand_name' => 'Glucophage',
                'indications' => 'Type 2 diabetes mellitus, insulin resistance, polycystic ovary syndrome (PCOS).',
                'contraindications' => 'Severe renal impairment, metabolic acidosis, diabetic ketoacidosis.',
            ],
            [
                'generic_name' => 'Omeprazole',
                'brand_name' => 'Losec',
                'indications' => 'Gastroesophageal reflux disease (GERD), gastric ulcers, duodenal ulcers, Zollinger-Ellison syndrome.',
                'contraindications' => 'Hypersensitivity to omeprazole, concomitant use with rilpivirine.',
            ],
            [
                'generic_name' => 'Losartan',
                'brand_name' => 'Cozaar',
                'indications' => 'Hypertension, diabetic nephropathy, heart failure, stroke prevention.',
                'contraindications' => 'Pregnancy, severe hepatic impairment, hypersensitivity to losartan.',
            ],
            [
                'generic_name' => 'Amoxicillin/Clavulanic Acid',
                'brand_name' => 'Co-Amoxiclav',
                'indications' => 'Respiratory tract infections, sinusitis, otitis media, skin and soft tissue infections.',
                'contraindications' => 'Allergy to penicillins, severe hepatic impairment, history of jaundice with prior use.',
            ],
            [
                'generic_name' => 'Cefalexin',
                'brand_name' => 'Keflex',
                'indications' => 'Skin infections, respiratory tract infections, urinary tract infections, bone infections.',
                'contraindications' => 'Hypersensitivity to cephalosporins, severe immediate reaction to penicillins.',
            ],
            [
                'generic_name' => 'Loperamide',
                'brand_name' => 'Imodium',
                'indications' => 'Acute and chronic diarrhea, traveler\'s diarrhea.',
                'contraindications' => 'Dysentery with blood in stool, acute ulcerative colitis, bacterial enterocolitis.',
            ],
            [
                'generic_name' => 'Mefenamic Acid',
                'brand_name' => 'Ponstan',
                'indications' => 'Menstrual cramps, mild to moderate pain, dental pain, musculoskeletal pain.',
                'contraindications' => 'Peptic ulcer, inflammatory bowel disease, severe renal impairment, hypersensitivity to NSAIDs.',
            ],
            [
                'generic_name' => 'Mefloquine',
                'brand_name' => 'Lariam',
                'indications' => 'Malaria prophylaxis and treatment, particularly for chloroquine-resistant strains.',
                'contraindications' => 'History of psychiatric disorders, history of seizures, severe hepatic impairment, hypersensitivity to mefloquine.',
            ],
            [
                'generic_name' => 'Doxycycline',
                'brand_name' => 'Vibramycin',
                'indications' => 'Acne vulgaris, bacterial infections, malaria prophylaxis, rickettsial infections.',
                'contraindications' => 'Pregnancy, children under 8 years, hypersensitivity to tetracyclines.',
            ],
            [
                'generic_name' => 'Ranitidine',
                'brand_name' => 'Zantac',
                'indications' => 'Gastric and duodenal ulcers, GERD, Zollinger-Ellison syndrome, heartburn relief.',
                'contraindications' => 'Hypersensitivity to ranitidine, porphyria.',
            ],
        ];

        foreach ($medicines as $medicine) {
            Medicine::firstOrCreate(
                ['generic_name' => $medicine['generic_name']],
                $medicine
            );
        }
    }
}
