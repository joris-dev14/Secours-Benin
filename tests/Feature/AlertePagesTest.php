<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\Ambulance;
use App\Models\Citoyen;
use App\Models\Commune;
use App\Models\Mission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertePagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_citizen_tracking_page_displays_the_latest_alert_and_mission(): void
    {
        $citoyen = Citoyen::factory()->create();
        $commune = Commune::create([
            'nom' => 'Cotonou',
            'departement' => 'Littoral',
            'centre_samu' => 'SAMU Cotonou',
            'numero_vert' => '112',
            'latitude' => 6.3654,
            'longitude' => 2.4183,
            'rayon_couverture' => 20,
            'redirection_auto' => true,
            'statut' => 'actif',
        ]);
        $alerte = Alerte::create([
            'citoyen_id' => $citoyen->id,
            'commune' => $commune->nom,
            'latitude' => 6.3654,
            'longitude' => 2.4183,
            'photo' => 'alertes/test.jpg',
            'description' => 'Accident',
            'statut' => 'en_attente',
        ]);
        $ambulance = Ambulance::create([
            'matricule' => 'AB-001',
            'centre' => 'Littoral',
            'commune' => $commune->nom,
            'statut' => 'disponible',
        ]);
        Mission::create([
            'alerte_id' => $alerte->id,
            'ambulance_id' => $ambulance->id,
            'statut' => 'en_route',
        ]);

        $response = $this->withSession(['citoyen_id' => $citoyen->id])->get('/citoyen/suivi-alerte');

        $response->assertStatus(200);
        $response->assertViewHas('alerte', fn ($value) => $value->id === $alerte->id);
        $response->assertViewHas('mission', fn ($value) => $value->id !== null);
    }

    public function test_the_citizen_history_page_displays_the_user_alerts(): void
    {
        $citoyen = Citoyen::factory()->create();
        $commune = Commune::create([
            'nom' => 'Abomey-Calavi',
            'departement' => 'Atlantique',
            'centre_samu' => 'SAMU Atlantique',
            'numero_vert' => '112',
            'latitude' => 6.35,
            'longitude' => 2.42,
            'rayon_couverture' => 20,
            'redirection_auto' => true,
            'statut' => 'actif',
        ]);
        Alerte::create([
            'citoyen_id' => $citoyen->id,
            'commune' => $commune->nom,
            'latitude' => 6.35,
            'longitude' => 2.42,
            'photo' => 'alertes/test2.jpg',
            'description' => 'Autre',
            'statut' => 'terminee',
        ]);

        $response = $this->withSession(['citoyen_id' => $citoyen->id])->get('/citoyen/historique');

        $response->assertStatus(200);
        $response->assertViewHas('alertes', function ($alertes) {
            return $alertes->count() === 1;
        });
    }
}
