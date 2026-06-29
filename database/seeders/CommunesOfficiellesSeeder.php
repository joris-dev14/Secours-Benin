<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;

class CommunesOfficiellesSeeder extends Seeder
{
    public function run(): void
    {
        // Vider la table d'abord
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \App\Models\Hopital::truncate();
            Commune::truncate();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');


        $communes = [
            // Alibori
            ['nom' => 'Banikoara',   'departement' => 'Alibori',        'numero_vert' => '01 45 10 02 02', 'latitude' => 11.3000, 'longitude' => 2.4333],
            ['nom' => 'Malanville',  'departement' => 'Alibori',        'numero_vert' => '01 45 10 03 03', 'latitude' => 11.8667, 'longitude' => 3.3833],
            ['nom' => 'Kandi',       'departement' => 'Alibori',        'numero_vert' => '01 45 10 04 04', 'latitude' => 11.1333, 'longitude' => 2.9333],

            // Atacora
            ['nom' => 'Tanguiéta',   'departement' => 'Atacora',        'numero_vert' => '01 45 10 05 05', 'latitude' => 10.6167, 'longitude' => 1.2667],
            ['nom' => 'Natitingou',  'departement' => 'Atacora',        'numero_vert' => '01 45 10 06 06', 'latitude' => 10.3167, 'longitude' => 1.3833],

            // Atlantique
            ['nom' => 'Allada',      'departement' => 'Atlantique',     'numero_vert' => '01 45 10 21 21', 'latitude' => 6.6667,  'longitude' => 2.1500],
            ['nom' => 'Ouidah',      'departement' => 'Atlantique',     'numero_vert' => '01 45 10 22 22', 'latitude' => 6.3611,  'longitude' => 2.0853],
            ['nom' => 'Calavi',      'departement' => 'Atlantique',     'numero_vert' => '01 45 10 23 23', 'latitude' => 6.4489,  'longitude' => 2.3556],
            ['nom' => 'Cococodji',   'departement' => 'Atlantique',     'numero_vert' => '01 45 10 24 24', 'latitude' => 6.3833,  'longitude' => 2.3167],
            ['nom' => 'GDIZ',        'departement' => 'Atlantique',     'numero_vert' => '01 45 10 25 25', 'latitude' => 6.5000,  'longitude' => 2.2333],

            // Borgou
            ['nom' => 'Parakou',     'departement' => 'Borgou',         'numero_vert' => '01 45 10 07 07', 'latitude' => 9.3333,  'longitude' => 2.6333],

            // Collines
            ['nom' => 'Dassa',       'departement' => 'Collines',       'numero_vert' => '01 45 10 11 11', 'latitude' => 7.7667,  'longitude' => 2.1833],
            ['nom' => 'Savalou',     'departement' => 'Collines',       'numero_vert' => '01 45 10 12 12', 'latitude' => 7.9333,  'longitude' => 1.9750],

            // Donga
            ['nom' => 'Djougou',     'departement' => 'Donga',          'numero_vert' => '01 45 10 08 08', 'latitude' => 9.7000,  'longitude' => 1.6667],
            ['nom' => 'Bassila',     'departement' => 'Donga',          'numero_vert' => '01 45 10 09 09', 'latitude' => 9.0000,  'longitude' => 1.6667],

            // Littoral
            ['nom' => 'St-Jean',     'departement' => 'Littoral',       'numero_vert' => '01 45 10 18 18', 'latitude' => 6.3654,  'longitude' => 2.4183],
            ['nom' => 'Sodjatimè',   'departement' => 'Littoral',       'numero_vert' => '01 45 10 19 19', 'latitude' => 6.3700,  'longitude' => 2.4100],
            ['nom' => 'Tokpa (GIS)', 'departement' => 'Littoral',       'numero_vert' => '01 45 10 20 20', 'latitude' => 6.3550,  'longitude' => 2.4200],

            // Mono-Couffo
            ['nom' => 'Lokossa',     'departement' => 'Mono-Couffo',    'numero_vert' => '01 45 10 26 26', 'latitude' => 6.6333,  'longitude' => 1.7167],
            ['nom' => 'Dogbo',       'departement' => 'Mono-Couffo',    'numero_vert' => '01 45 10 27 27', 'latitude' => 6.7833,  'longitude' => 1.7833],
            ['nom' => 'Comè',        'departement' => 'Mono-Couffo',    'numero_vert' => '01 45 10 28 28', 'latitude' => 6.4167,  'longitude' => 1.8833],

            // Ouémé-Plateau
            ['nom' => 'Porto-Novo',  'departement' => 'Ouémé-Plateau',  'numero_vert' => '01 45 10 15 15', 'latitude' => 6.4969,  'longitude' => 2.6289],
            ['nom' => 'Sèmè-kraké',  'departement' => 'Ouémé-Plateau',  'numero_vert' => '01 45 10 16 16', 'latitude' => 6.3667,  'longitude' => 2.6500],
            ['nom' => 'Pobè',        'departement' => 'Ouémé-Plateau',  'numero_vert' => '01 45 10 17 17', 'latitude' => 6.9667,  'longitude' => 2.6667],

            // Zou
            ['nom' => 'Bohicon',     'departement' => 'Zou',            'numero_vert' => '01 45 10 13 13', 'latitude' => 7.1833,  'longitude' => 2.0667],
            ['nom' => 'Covè',        'departement' => 'Zou',            'numero_vert' => '01 45 10 14 14', 'latitude' => 7.2667,  'longitude' => 2.3667],

            // Cotonou (Littoral - centre principal)
            ['nom' => 'Cotonou',     'departement' => 'Littoral',       'numero_vert' => '70 00 00 00',    'latitude' => 6.3654,  'longitude' => 2.4183,
             'centre_samu' => 'SAMU Cotonou Centre'],
        ];

        foreach ($communes as $data) {
            Commune::create(array_merge($data, [
                'centre_samu'      => $data['centre_samu'] ?? null,
                'rayon_couverture' => 25,
                'redirection_auto' => false,
                'statut'           => 'active',
            ]));
        }
    }
}