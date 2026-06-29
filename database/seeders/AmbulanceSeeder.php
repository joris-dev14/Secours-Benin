<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ambulance;

class AmbulanceSeeder extends Seeder
{
    public function run(): void
    {
        Ambulance::create([
            'matricule' => 'AMB-101',
            'centre'    => 'SAMU Cotonou Centre',
            'commune'   => 'Cotonou',
            'statut'    => 'disponible',
            'latitude'  => 6.3654,
            'longitude' => 2.4183,
        ]);

        Ambulance::create([
            'matricule' => 'AMB-102',
            'centre'    => 'SAMU Cotonou Centre',
            'commune'   => 'Cotonou',
            'statut'    => 'disponible',
            'latitude'  => 6.3700,
            'longitude' => 2.4200,
        ]);

        Ambulance::create([
            'matricule' => 'AMB-103',
            'centre'    => 'SAMU Abomey-Calavi',
            'commune'   => 'Abomey-Calavi',
            'statut'    => 'disponible',
            'latitude'  => 6.4489,
            'longitude' => 2.3556,
        ]);
    }
}