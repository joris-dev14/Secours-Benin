<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\Ambulance;
use App\Models\Ambulancier;
use App\Models\Citoyen;
use App\Models\Commune;
use App\Models\Mission;
use App\Models\Regulateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DispatchWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatch_creates_a_mission_and_updates_statuses_for_ambulance_and_citizen(): void
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

        $ambulancier = Ambulancier::create([
            'nom' => 'Jean',
            'prenom' => 'Paul',
            'matricule' => 'AMB-001',
            'mot_de_passe' => bcrypt('secret123'),
            'centre' => $commune->nom,
            'statut' => 'disponible',
        ]);

        $ambulance = Ambulance::create([
            'matricule' => 'AB-001',
            'centre' => $commune->nom,
            'commune' => $commune->nom,
            'statut' => 'disponible',
            'ambulancier_id' => $ambulancier->id,
            'latitude' => 6.3640,
            'longitude' => 2.4190,
        ]);

        $ambulancier->update(['ambulance_id' => $ambulance->id]);

        $regulateur = Regulateur::create([
            'nom' => 'Alice',
            'prenom' => 'Simeon',
            'matricule' => 'REG-001',
            'mot_de_passe' => bcrypt('secret123'),
            'centre' => $commune->centre_samu,
            'commune' => $commune->nom,
            'statut' => 'actif',
        ]);

        $response = $this->withSession(['regulateur_id' => $regulateur->id])
            ->post('/regulateur/dispatcher', [
                'alerte_id' => $alerte->id,
                'ambulance_id' => $ambulance->id,
            ]);

        $response->assertRedirect('/regulateur/dashboard');
        $this->assertDatabaseHas('missions', [
            'alerte_id' => $alerte->id,
            'ambulance_id' => $ambulance->id,
            'statut' => 'assignee',
        ]);
        $this->assertDatabaseHas('alertes', [
            'id' => $alerte->id,
            'statut' => 'prise_en_charge',
        ]);
        $this->assertDatabaseHas('ambulances', [
            'id' => $ambulance->id,
            'statut' => 'en_mission',
        ]);

        $mission = Mission::where('alerte_id', $alerte->id)->first();

        $response = $this->withSession(['ambulancier_id' => $ambulancier->id])
            ->post('/ambulancier/mission/' . $mission->id . '/statut', [
                'statut' => 'en_route',
            ]);

        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('missions', [
            'id' => $mission->id,
            'statut' => 'en_route',
        ]);
        $this->assertDatabaseHas('alertes', [
            'id' => $alerte->id,
            'statut' => 'en_route',
        ]);
    }
}
