<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Regulateur;
use Illuminate\Support\Facades\Hash;

class RegulateurSeeder extends Seeder
{
    public function run(): void
    {
        Regulateur::create([
            'nom'          => 'Adjovi',
            'prenom'       => 'Kofi',
            'matricule'    => 'REG-001',
            'mot_de_passe' => Hash::make('reg123'),
            'centre'       => 'SAMU Cotonou Centre',
            'commune'      => 'Cotonou',
            'statut'       => 'actif',
        ]);
    }
}