<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;
use App\Models\Hopital;

class CommuneSeeder extends Seeder
{
    public function run(): void
    {
        $communes = [
            [
                'nom'              => 'Cotonou',
                'departement'      => 'Littoral',
                'centre_samu'      => 'SAMU Cotonou Centre',
                'numero_vert'      => '70 00 00 00',
                'latitude'         => 6.3654,
                'longitude'        => 2.4183,
                'rayon_couverture' => 25,
                'redirection_auto' => true,
                'statut'           => 'active',
                'hopitaux'         => ['CHD Cotonou', "Hôpital de l'Amitié", 'Clinique Les Grâces'],
            ],
            [
                'nom'              => 'Abomey-Calavi',
                'departement'      => 'Atlantique',
                'centre_samu'      => 'SAMU Abomey-Calavi',
                'numero_vert'      => '70 00 00 01',
                'latitude'         => 6.4489,
                'longitude'        => 2.3556,
                'rayon_couverture' => 25,
                'redirection_auto' => true,
                'statut'           => 'active',
                'hopitaux'         => ['HGD', 'Clinique Calavi'],
            ],
            [
                'nom'              => 'Ouidah',
                'departement'      => 'Atlantique',
                'centre_samu'      => 'SAMU Ouidah',
                'numero_vert'      => '70 00 00 02',
                'latitude'         => 6.3611,
                'longitude'        => 2.0853,
                'rayon_couverture' => 25,
                'redirection_auto' => true,
                'statut'           => 'active',
                'hopitaux'         => ['HZV Ouidah'],
            ],
        ];

        foreach ($communes as $data) {
            $hopitaux = $data['hopitaux'];
            unset($data['hopitaux']);

            $commune = Commune::create($data);

            foreach ($hopitaux as $nomHopital) {
                Hopital::create([
                    'commune_id' => $commune->id,
                    'nom'        => $nomHopital,
                ]);
            }
        }
    }
}