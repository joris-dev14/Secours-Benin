<?php
namespace App\Http\Controllers;

use App\Models\Alerte;
use App\Models\Citoyen;
use App\Models\Commune;
use Illuminate\Http\Request;

class AlerteController extends Controller
{
    public function nouvelle()
    {
        if (!session('citoyen_id')) {
            return redirect('/citoyen/auth');
        }

        $communes = Commune::orderBy('departement')->orderBy('nom')->get(['nom', 'departement']);
        $departements = $communes->groupBy('departement')->map(function ($items) {
            return $items->pluck('nom');
        });

        return view('citoyen.nouvelle-alerte', compact('departements'));
    }

    public function envoyer(Request $request)
    {
        if (!session('citoyen_id')) {
            return redirect('/citoyen/auth');
        }

        $request->validate([
            'departement' => 'required|string|exists:communes,departement',
            'commune'     => 'required|string|exists:communes,nom',
            'photo'       => 'required|image|max:5120',
            'description' => 'nullable|string|max:500',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
        ]);

        $photoPath = $request->file('photo')->store('alertes', 'public');

        Alerte::create([
            'citoyen_id'  => session('citoyen_id'),
            'commune'     => $request->commune,
            'latitude'    => $request->latitude,
            'longitude'   => $request->longitude,
            'photo'       => $photoPath,
            'description' => $request->description,
            'statut'      => 'en_attente',
        ]);

        return redirect('/citoyen/suivi-alerte');
    }

    public function suivi()
    {
        if (!session('citoyen_id')) {
            return redirect('/citoyen/auth');
        }

        $citoyen = Citoyen::find(session('citoyen_id'));
        $alerte = $citoyen
            ? $citoyen->alertes()->latest()->first()
            : null;
        $mission = $alerte
            ? $alerte->mission()->with('ambulance')->first()
            : null;

        return view('citoyen.suivi-alerte', compact('alerte', 'mission'));
    }

    public function suiviData()
    {
        if (!session('citoyen_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé.',
            ], 401);
        }

        $citoyen = Citoyen::find(session('citoyen_id'));
        $alerte = $citoyen ? $citoyen->alertes()->latest()->first() : null;
        $mission = $alerte ? $alerte->mission()->with('ambulance')->first() : null;

        return response()->json([
            'success' => true,
            'alerte' => $alerte ? $alerte->only(['id', 'commune', 'latitude', 'longitude', 'statut', 'created_at']) : null,
            'mission' => $mission ? [
                'id' => $mission->id,
                'statut' => $mission->statut,
                'depart_a' => $mission->depart_a?->toDateTimeString(),
                'arrive_a' => $mission->arrive_a?->toDateTimeString(),
                'termine_a' => $mission->termine_a?->toDateTimeString(),
                'ambulance' => $mission->ambulance ? $mission->ambulance->only(['id', 'matricule', 'latitude', 'longitude']) : null,
            ] : null,
        ]);
    }

    public function historique()
    {
        if (!session('citoyen_id')) {
            return redirect('/citoyen/auth');
        }
        $citoyen = Citoyen::find(session('citoyen_id'));
        $alertes = $citoyen ? $citoyen->alertes()->latest()->get() : [];
        return view('citoyen.historique', compact('alertes'));
    }
}