<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Commune;

class CommunesBeninSeeder extends Seeder
{
    public function run(): void
    {
        $communes = [
            'Alibori' => ['Banikoara', 'Kandi', 'Malanville'],
            'Atacora' => ['Natitingou', 'Tanguiéta'],
            'Atlantique' => ['Calavi', 'Allada', 'Ouidah', 'Cococodji', 'GDZI'],
            'Borgou' => [ 'Parakou'],
            'Collines' => ['Dassa-Zoumè', 'Savalou'],
            'Donga' => ['Bassila','Djougou'],
            'Littoral' => ['Cotonou', 'Sodjatimè','St Jean'],
            'Mono-Couffo' => ['Dogbo','Comè','Lokossa'],
            'Ouémé-Plateau' => ['Pobè','Porto-Novo', 'Sèmè-Kraké'],
            'Zou' => [ 'Bohicon', 'Covè'],
        ];

        foreach ($communes as $departement => $listeCommunes) {
            foreach ($listeCommunes as $nom) {
                Commune::firstOrCreate(
                    ['nom' => $nom],
                    [
                        'departement'      => $departement,
                        'centre_samu'      => null,
                        'numero_vert'      => null,
                        'latitude'         => null,
                        'longitude'        => null,
                        'rayon_couverture' => 25,
                        'redirection_auto' => false,
                        'statut'           => 'active',
                    ]
                );
            }
        }
    }
}