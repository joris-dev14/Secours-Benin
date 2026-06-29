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
        return view('citoyen.suivi-alerte');
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