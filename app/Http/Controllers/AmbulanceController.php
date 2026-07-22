<?php
namespace App\Http\Controllers;

use App\Models\Ambulance;
use App\Models\Ambulancier;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmbulanceController extends Controller
{
    public function index()
    {
        $ambulances = Ambulance::with('ambulancier')->get();
        return view('regulateur.gestion-flotte', compact('ambulances'));
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:disponible,en_mission,maintenance',
        ]);

        $ambulance = Ambulance::with('ambulancier')->findOrFail($id);
        $ambulancier = $ambulance->ambulancier;

        $activeMission = $ambulance->missions()
            ->whereIn('statut', ['assignee', 'en_route', 'sur_place'])
            ->exists();

        if ($activeMission && in_array($request->statut, ['disponible', 'maintenance'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de modifier le statut de cette ambulance tant qu\'une mission est active.',
            ], 400);
        }

        if ($request->statut === 'en_mission' && !$ambulancier) {
            return response()->json([
                'success' => false,
                'message' => 'Une ambulance en mission doit être rattachée à un ambulancier.',
            ], 400);
        }

        DB::transaction(function () use ($ambulance, $ambulancier, $request) {
            $ambulance->update(['statut' => $request->statut]);

            if ($ambulancier) {
                if ($request->statut === 'en_mission') {
                    $ambulancier->update(['statut' => 'en_mission']);
                } elseif ($request->statut === 'disponible') {
                    $ambulancier->update(['statut' => 'disponible']);
                } elseif ($request->statut === 'maintenance' && $ambulancier->statut !== 'inactif') {
                    $ambulancier->update(['statut' => 'disponible']);
                }
            }
        });

        return response()->json(['success' => true]);
    }

    public function updatePosition(Request $request, $id)
    {
        $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        if (!session('ambulancier_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé.',
            ], 401);
        }

        $ambulance = Ambulance::with('ambulancier')->findOrFail($id);
        $ambulancier = $ambulance->ambulancier;

        if (!$ambulancier || $ambulancier->id !== session('ambulancier_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas mettre à jour la position de cette ambulance.',
            ], 403);
        }

        $ambulance->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json(['success' => true]);
    }
}