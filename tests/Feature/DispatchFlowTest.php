<?php

namespace Tests\Feature;

use App\Models\Alerte;
use App\Models\Ambulance;
use App\Models\Ambulancier;
use App\Models\Citoyen;
use App\Models\Mission;
use App\Models\Regulateur;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class DispatchFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_dispatch_creates_mission_and_updates_related_entities(): void
    {
        $regulateur = Regulateur::create([
            'nom' => 'Jean',
            'prenom' => 'Dupont',
            'matricule' => 'REG-001',
            'mot_de_passe' => Hash::make('secret123'),
            'centre' => 'Cotonou',
            'commune' => 'Cotonou',
            'statut' => 'actif',
        ]);

        $citoyen = Citoyen::create([
            'telephone' => '22990000001',
            'consentement' => true,
        ]);

        $alerte = Alerte::create([
            'citoyen_id' => $citoyen->id,
            'commune' => 'Cotonou',
            'latitude' => 6.3654,
            'longitude' => 2.4183,
            'description' => 'Accident de la circulation',
            'statut' => 'en_attente',
        ]);

        $ambulancier = Ambulancier::create([
            'nom' => 'Kouassi',
            'prenom' => 'Paul',
            'matricule' => 'AMB-001',
            'mot_de_passe' => Hash::make('secret123'),
            'centre' => 'Cotonou',
            'statut' => 'disponible',
        ]);

        $ambulance = Ambulance::create([
            'matricule' => 'AB-001',
            'modele' => 'Mercedes',
            'centre' => 'Cotonou',
            'commune' => 'Cotonou',
            'statut' => 'disponible',
            'latitude' => 6.3700,
            'longitude' => 2.4200,
            'ambulancier_id' => $ambulancier->id,
        ]);

        $ambulancier->update(['ambulance_id' => $ambulance->id]);

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

        $this->assertSame('prise_en_charge', $alerte->fresh()->statut);
        $this->assertSame('en_mission', $ambulance->fresh()->statut);
        $this->assertSame('en_mission', $ambulancier->fresh()->statut);
    }

    public function test_ambulancier_status_updates_propagate_to_alert_and_ambulance(): void
    {
        $citoyen = Citoyen::create([
            'telephone' => '22990000002',
            'consentement' => true,
        ]);

        $alerte = Alerte::create([
            'citoyen_id' => $citoyen->id,
            'commune' => 'Cotonou',
            'latitude' => 6.3654,
            'longitude' => 2.4183,
            'description' => 'Brûlure mineure',
            'statut' => 'prise_en_charge',
        ]);

        $ambulancier = Ambulancier::create([
            'nom' => 'Sossa',
            'prenom' => 'Mina',
            'matricule' => 'AMB-002',
            'mot_de_passe' => Hash::make('secret123'),
            'centre' => 'Cotonou',
            'statut' => 'en_mission',
        ]);

        $ambulance = Ambulance::create([
            'matricule' => 'AB-002',
            'modele' => 'Toyota',
            'centre' => 'Cotonou',
            'commune' => 'Cotonou',
            'statut' => 'en_mission',
            'latitude' => 6.3700,
            'longitude' => 2.4200,
            'ambulancier_id' => $ambulancier->id,
        ]);

        $ambulancier->update(['ambulance_id' => $ambulance->id]);

        $mission = Mission::create([
            'alerte_id' => $alerte->id,
            'ambulance_id' => $ambulance->id,
            'statut' => 'assignee',
        ]);

        $response = $this->withSession(['ambulancier_id' => $ambulancier->id])
            ->postJson('/ambulancier/mission/' . $mission->id . '/statut', ['statut' => 'en_route']);

        $response->assertStatus(200);
        $this->assertSame('en_route', $mission->fresh()->statut);
        $this->assertSame('en_route', $alerte->fresh()->statut);
        $this->assertSame('en_mission', $ambulance->fresh()->statut);

        $this->withSession(['ambulancier_id' => $ambulancier->id])
            ->postJson('/ambulancier/mission/' . $mission->id . '/statut', ['statut' => 'sur_place']);

        $this->assertSame('sur_place', $mission->fresh()->statut);
        $this->assertSame('sur_place', $alerte->fresh()->statut);

        $this->withSession(['ambulancier_id' => $ambulancier->id])
            ->postJson('/ambulancier/mission/' . $mission->id . '/statut', ['statut' => 'terminee']);

        $this->assertSame('terminee', $mission->fresh()->statut);
        $this->assertSame('terminee', $alerte->fresh()->statut);
        $this->assertSame('disponible', $ambulance->fresh()->statut);
    }
}
