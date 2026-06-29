<?php
namespace App\Http\Controllers;

use App\Models\Ambulancier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AmbulancierController extends Controller
{
    public function login()
    {
        return view('ambulancier.login-ambulancier');
    }

  public function authentifier(Request $request)
{
    $request->validate([
        'matricule'    => 'required',
        'mot_de_passe' => 'required',
    ]);

    $ambulancier = Ambulancier::where('matricule', $request->matricule)
                              ->where('statut', '!=', 'inactif')
                              ->first();

    if (!$ambulancier || !Hash::check($request->mot_de_passe, $ambulancier->mot_de_passe)) {
        return back()->withErrors(['message' => 'Identifiants incorrects']);
    }

    session([
        'ambulancier_id'  => $ambulancier->id,
        'ambulancier_nom' => $ambulancier->nom,
    ]);

    return redirect('/ambulancier/missions');
}
    public function parametres()
{
    if (!session('ambulancier_id')) {
        return redirect('/ambulancier/login');
    }
    $ambulancier = Ambulancier::find(session('ambulancier_id'));
    return view('ambulancier.parametres', compact('ambulancier'));
}

public function changerMotDePasse(Request $request)
{
    if (!session('ambulancier_id')) {
        return redirect('/ambulancier/login');
    }

    $request->validate([
        'ancien_mot_de_passe'  => 'required',
        'nouveau_mot_de_passe' => 'required|min:6',
        'confirmation'         => 'required|same:nouveau_mot_de_passe',
    ]);

    $ambulancier = Ambulancier::find(session('ambulancier_id'));

    if (!Hash::check($request->ancien_mot_de_passe, $ambulancier->mot_de_passe)) {
        return back()->withErrors(['message' => 'Ancien mot de passe incorrect']);
    }

    $ambulancier->update([
        'mot_de_passe' => Hash::make($request->nouveau_mot_de_passe)
    ]);

    return redirect('/ambulancier/parametres')->with('success', 'Mot de passe modifié avec succès !');
}
    public function deconnexion()
    {
        session()->forget(['ambulancier_id', 'ambulancier_nom']);
        return redirect('/');
    }
}