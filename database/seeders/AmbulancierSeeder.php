<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ambulancier;
use Illuminate\Support\Facades\Hash;

class AmbulancierSeeder extends Seeder
{
    public function run(): void
    {
        Ambulancier::create([
            'nom'          => 'Koudjo',
            'prenom'       => 'Jean',
            'matricule'    => 'AMB-104',
            'mot_de_passe' => Hash::make('amb123'),
            'ambulance_id' => null,
            'centre'       => 'SAMU Cotonou Centre',
            'statut'       => 'disponible',
        ]);
    }
}