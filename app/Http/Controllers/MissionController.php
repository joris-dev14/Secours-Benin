<?php
namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\Ambulance;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    private function resolveAmbulanceId($ambulancier)
    {
        return $ambulancier->ambulance_id ?: optional($ambulancier->ambulance)->id;
    }

    public function index()
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }
        $ambulancier = \App\Models\Ambulancier::find(session('ambulancier_id'));
        $ambulanceId = $this->resolveAmbulanceId($ambulancier);
        $missionActive = Mission::with(['alerte'])
            ->whereIn('statut', ['assignee', 'en_route', 'sur_place'])
            ->whereHas('ambulance', function($q) use ($ambulanceId) {
                $q->where('id', $ambulanceId);
            })->first();

        $missionsTerminees = Mission::with(['alerte'])
            ->where('statut', 'terminee')
            ->whereHas('ambulance', function($q) use ($ambulanceId) {
                $q->where('id', $ambulanceId);
            })->latest()->get();

        return view('ambulancier.missions', compact('missionActive', 'missionsTerminees'));
    }

    public function active()
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }
        $ambulancier = \App\Models\Ambulancier::find(session('ambulancier_id'));
        $ambulanceId = $this->resolveAmbulanceId($ambulancier);
        $mission = Mission::with(['alerte'])
            ->whereIn('statut', ['assignee', 'en_route', 'sur_place'])
            ->whereHas('ambulance', function($q) use ($ambulanceId) {
                $q->where('id', $ambulanceId);
            })->first();

        return view('ambulancier.mission-active', compact('mission'));
    }

    public function updateStatut(Request $request, $id)
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }

        $request->validate([
            'statut' => 'required|in:assignee,en_route,sur_place,terminee',
        ]);

        $mission = Mission::findOrFail($id);
        $alert = $mission->alerte;
        $ambulance = $mission->ambulance;

        switch ($request->statut) {
            case 'en_route':
                $mission->update(['statut' => 'en_route', 'depart_a' => $mission->depart_a ?? now()]);
                $alert?->update(['statut' => 'en_route']);
                $ambulance?->update(['statut' => 'en_mission']);
                $ambulance?->ambulancier?->update(['statut' => 'en_mission']);
                break;
            case 'sur_place':
                $mission->update(['statut' => 'sur_place', 'arrive_a' => $mission->arrive_a ?? now()]);
                $alert?->update(['statut' => 'sur_place']);
                $ambulance?->ambulancier?->update(['statut' => 'en_mission']);
                break;
            case 'terminee':
                $mission->update(['statut' => 'terminee', 'termine_a' => now()]);
                $ambulance?->update(['statut' => 'disponible']);
                $ambulance?->ambulancier?->update(['statut' => 'disponible']);
                $alert?->update(['statut' => 'terminee']);
                break;
            default:
                $mission->update(['statut' => $request->statut]);
                $alert?->update(['statut' => $request->statut === 'assignee' ? 'prise_en_charge' : $request->statut]);
                break;
        }

        return response()->json(['success' => true]);
    }

    public function historique()
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }
        $ambulancier = \App\Models\Ambulancier::find(session('ambulancier_id'));
        $ambulanceId = $this->resolveAmbulanceId($ambulancier);
        $missions = Mission::with(['alerte'])
            ->where('statut', 'terminee')
            ->whereHas('ambulance', function($q) use ($ambulanceId) {
                $q->where('id', $ambulanceId);
            })->latest()->get();

        return view('ambulancier.historique-chauffeur', compact('missions'));
    }
}