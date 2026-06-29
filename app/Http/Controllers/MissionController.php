<?php
namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\Ambulance;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    public function index()
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }
        $ambulancier = \App\Models\Ambulancier::find(session('ambulancier_id'));
        $missionActive = Mission::with(['alerte'])
            ->whereIn('statut', ['assignee', 'en_route', 'sur_place'])
            ->whereHas('ambulance', function($q) use ($ambulancier) {
                $q->where('id', $ambulancier->ambulance_id);
            })->first();

        $missionsTerminees = Mission::with(['alerte'])
            ->where('statut', 'terminee')
            ->whereHas('ambulance', function($q) use ($ambulancier) {
                $q->where('id', $ambulancier->ambulance_id);
            })->latest()->get();

        return view('ambulancier.missions', compact('missionActive', 'missionsTerminees'));
    }

    public function active()
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }
        $ambulancier = \App\Models\Ambulancier::find(session('ambulancier_id'));
        $mission = Mission::with(['alerte'])
            ->whereIn('statut', ['assignee', 'en_route', 'sur_place'])
            ->whereHas('ambulance', function($q) use ($ambulancier) {
                $q->where('id', $ambulancier->ambulance_id);
            })->first();

        return view('ambulancier.mission-active', compact('mission'));
    }

    public function updateStatut(Request $request, $id)
    {
        if (!session('ambulancier_id')) {
            return redirect('/ambulancier/login');
        }

        $mission = Mission::findOrFail($id);

        switch ($request->statut) {
            case 'en_route':
                $mission->update(['statut' => 'en_route', 'depart_a' => now()]);
                break;
            case 'sur_place':
                $mission->update(['statut' => 'sur_place', 'arrive_a' => now()]);
                break;
            case 'terminee':
                $mission->update(['statut' => 'terminee', 'termine_a' => now()]);
                $mission->ambulance->update(['statut' => 'disponible']);
                $mission->alerte->update(['statut' => 'terminee']);
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
        $missions = Mission::with(['alerte'])
            ->where('statut', 'terminee')
            ->whereHas('ambulance', function($q) use ($ambulancier) {
                $q->where('id', $ambulancier->ambulance_id);
            })->latest()->get();

        return view('ambulancier.historique-chauffeur', compact('missions'));
    }
}