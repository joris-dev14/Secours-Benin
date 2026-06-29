<?php
namespace App\Http\Controllers;

use App\Models\Signalement;
use App\Models\Citoyen;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    public function index(Request $request)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $communeFiltre = $request->commune;

    $query = Signalement::with(['alerte.citoyen', 'regulateur']);

    if ($communeFiltre) {
        $query->whereHas('alerte', function($q) use ($communeFiltre) {
            $q->where('commune', $communeFiltre);
        });
    }

    $signalements = $query->latest()->get();

    // Stats
    $totalMois     = Signalement::whereMonth('created_at', now()->month)->count();
    $enAttente     = Signalement::where('statut', 'en_attente')->count();
    $faussesMois   = Signalement::where('statut', 'traite')->whereMonth('created_at', now()->month)->count();
    $comptesBloques = \App\Models\Citoyen::where('consentement', false)->count();

    // Liste des communes pour le filtre
    $communes = \App\Models\Alerte::distinct()->pluck('commune');

    return view('admin.moderation', compact(
        'signalements', 'totalMois', 'enAttente', 'faussesMois',
        'comptesBloques', 'communes', 'communeFiltre'
    ));
}

    public function traiter(Request $request, $id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }
    $signalement = Signalement::findOrFail($id);
    $signalement->update([
        'statut'      => $request->statut,
        'commentaire' => $request->commentaire,
    ]);
    return response()->json(['success' => true]);
}

public function bloquerCitoyen($id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }
    $signalement = Signalement::findOrFail($id);
    $citoyen = $signalement->alerte->citoyen;
    $citoyen->update(['consentement' => false]);
    $signalement->update(['statut' => 'traite']);
    return response()->json(['success' => true]);
}
}