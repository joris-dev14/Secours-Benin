<?php
namespace App\Http\Controllers;

use App\Models\Regulateur;
use App\Models\Alerte;
use App\Models\Ambulance;
use App\Models\Signalement;
use App\Models\Commune;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Mission;

class RegulateurController extends Controller
{
    public function login()
    {
        return view('regulateur.login-regulateur');
    }
    

    public function authentifier(Request $request)
    {
        $request->validate([
            'matricule'    => 'required',
            'mot_de_passe' => 'required',
        ]);

        $regulateur = Regulateur::where('matricule', $request->matricule)
                                ->where('statut', 'actif')
                                ->first();

        if (!$regulateur || !Hash::check($request->mot_de_passe, $regulateur->mot_de_passe)) {
            return back()->withErrors(['message' => 'Identifiants incorrects']);
        }
 
        session([
            'regulateur_id'  => $regulateur->id,
            'regulateur_nom' => $regulateur->nom,
        ]);
 
        return redirect('/regulateur/dashboard');
    }
 
    private function resolveTerritory(Regulateur $regulateur)
    {
        $commune = Commune::where('nom', $regulateur->commune)
            ->orWhere('centre_samu', $regulateur->centre)
            ->orWhere('departement', $regulateur->centre)
            ->first();

        $communeName = $commune ? $commune->nom : $regulateur->commune;
        $centreSamu = $commune ? $commune->centre_samu : $regulateur->centre;
        $departement = $commune ? $commune->departement : null;
        $communesInDepartment = $commune ? Commune::where('departement', $commune->departement)->pluck('nom') : collect([$communeName]);

        return [
            'commune' => $communeName,
            'centre' => $centreSamu,
            'departement' => $departement,
            'communes' => $communesInDepartment,
        ];
    }

    private function calculateDistanceKm(?float $lat1, ?float $lon1, ?float $lat2, ?float $lon2): ?float
    {
        if ($lat1 === null || $lon1 === null || $lat2 === null || $lon2 === null) {
            return null;
        }

        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
    }
 
    public function dashboard()    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }

        $regulateur = Regulateur::find(session('regulateur_id'));
        $territoire = $this->resolveTerritory($regulateur);
        $communesLower = $territoire['communes']->map(fn($commune) => mb_strtolower($commune))->toArray();

        $alertesEnAttente  = Alerte::where('statut', 'en_attente')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->count();
 
        $alertesActives    = Alerte::whereIn('statut', ['prise_en_charge'])
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->count();

        $ambulancesDispos  = Ambulance::where('statut', 'disponible')
            ->whereRaw('LOWER(commune) = ?', [mb_strtolower($territoire['commune'])])
            ->count();
 
        $ambulancesTotal   = Ambulance::whereRaw('LOWER(commune) = ?', [mb_strtolower($territoire['commune'])])->count();
 
        $alertes = Alerte::with('citoyen')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->latest()
            ->take(10)
            ->get();

        return view('regulateur.dashboard', compact(
            'alertesEnAttente',
            'alertesActives',
            'ambulancesDispos',
            'ambulancesTotal',
            'alertes'
        ));
    }
public function exportPdf()
    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }

        $regulateur = Regulateur::find(session('regulateur_id'));
        $territoire = $this->resolveTerritory($regulateur);
        $communesLower = $territoire['communes']->map(fn($commune) => mb_strtolower($commune))->toArray();

        $totalAlertesMois = Alerte::whereMonth('created_at', now()->month)
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->count();

        $alertesTerminees = Alerte::whereMonth('created_at', now()->month)
            ->where('statut', 'terminee')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->count();

        $tauxTraitement   = $totalAlertesMois > 0 ? round(($alertesTerminees / $totalAlertesMois) * 100, 1) : 0;
        $faussesAlertes   = Signalement::whereMonth('created_at', now()->month)
            ->whereHas('alerte', fn($q) => $q->whereIn(DB::raw('LOWER(commune)'), $communesLower))
            ->count();

        $parCommune = Alerte::selectRaw('commune, COUNT(*) as total')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->groupBy('commune')
            ->get();

        $alertesRecentes = Alerte::with('citoyen')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->latest()
            ->take(10)
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('regulateur.rapport-pdf', compact(
            'totalAlertesMois', 'tauxTraitement', 'faussesAlertes',
            'parCommune', 'alertesRecentes', 'regulateur'
        ));

        return $pdf->download('rapport-secours-benin-' . now()->format('Y-m-d') . '.pdf');
    }
    public function dispatch()
    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }

        $regulateur = Regulateur::find(session('regulateur_id'));
        $territoire = $this->resolveTerritory($regulateur);
        $communesLower  = $territoire['communes']->map(fn($commune) => mb_strtolower($commune))->toArray();

        $alertes = Alerte::where('statut', 'en_attente')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->with('citoyen')
            ->get();

        $alerte = $alertes->first();
        $availableAmbulanciers = collect();

        if ($alerte) {
            $availableAmbulanciers = \App\Models\Ambulancier::with('ambulance')
                ->where('statut', 'disponible')
                ->whereRaw('LOWER(centre) = ?', [mb_strtolower($territoire['commune'])])
                ->whereNotNull('ambulance_id')
                ->whereHas('ambulance', function ($query) {
                    $query->where('statut', 'disponible');
                })
                ->get();

            $availableAmbulanciers->each(function ($ambulancier) use ($alerte) {
                $ambulance = $ambulancier->ambulance;
                $ambulancier->distance_km = $this->calculateDistanceKm(
                    (float) $alerte->latitude,
                    (float) $alerte->longitude,
                    (float) $ambulance->latitude,
                    (float) $ambulance->longitude
                );
                $ambulancier->distance_label = $ambulancier->distance_km === null ? 'Position non disponible' : $ambulancier->distance_km . ' km';
            });

            $availableAmbulanciers = $availableAmbulanciers->sortBy(fn ($ambulancier) => $ambulancier->distance_km ?? PHP_INT_MAX)->values();
        }

        return view('regulateur.vue-dispatch', compact('alertes', 'availableAmbulanciers'));
    }

    public function dispatcher(Request $request)
    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }

        $request->validate([
            'alerte_id'      => 'required|exists:alertes,id',
            'ambulancier_id' => 'required_without:ambulance_id|exists:ambulanciers,id',
            'ambulance_id'   => 'required_without:ambulancier_id|exists:ambulances,id',
        ]);

        $regulateur = Regulateur::find(session('regulateur_id'));
        $territoire = $this->resolveTerritory($regulateur);
        $communesLower  = $territoire['communes']->map(fn($commune) => mb_strtolower($commune))->toArray();

        $alerte = Alerte::where('id', $request->alerte_id)
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->firstOrFail();

        if ($request->filled('ambulancier_id')) {
            $ambulancier = \App\Models\Ambulancier::with('ambulance')
                ->where('id', $request->ambulancier_id)
                ->where('statut', 'disponible')
                ->whereRaw('LOWER(centre) = ?', [mb_strtolower($territoire['commune'])])
                ->whereNotNull('ambulance_id')
                ->firstOrFail();
            $ambulance = $ambulancier->ambulance;
        } else {
            $ambulance = Ambulance::with('ambulancier')
                ->where('id', $request->ambulance_id)
                ->where('statut', 'disponible')
                ->firstOrFail();
            $ambulancier = $ambulance->ambulancier;
            abort_if(!$ambulancier || $ambulancier->statut !== 'disponible', 404);
            abort_if(mb_strtolower($ambulancier->centre) !== mb_strtolower($territoire['commune']), 404);
        }

        abort_if(!$ambulance || $ambulance->statut !== 'disponible', 404);

        DB::transaction(function () use ($alerte, $ambulance, $ambulancier) {
            $mission = Mission::firstOrNew(['alerte_id' => $alerte->id]);
            $mission->fill([
                'ambulance_id' => $ambulance->id,
                'statut'       => 'assignee',
                'depart_a'     => $mission->depart_a,
            ])->save();

            $alerte->update(['statut' => 'prise_en_charge']);
            $ambulance->update(['statut' => 'en_mission', 'ambulancier_id' => $ambulancier->id]);
            $ambulancier->update(['statut' => 'en_mission', 'ambulance_id' => $ambulance->id]);
        });

        return redirect('/regulateur/dispatch')->with('success', 'Dispatch effectué avec succès !');
    }

    public function flotte(Request $request)
    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }

        $regulateur = Regulateur::find(session('regulateur_id'));
        $territoire = $this->resolveTerritory($regulateur);
        $statut = $request->statut;
        $search = $request->search;

        $query = Ambulance::with('ambulancier')
            ->whereRaw('LOWER(commune) = ?', [mb_strtolower($territoire['commune'])]);

        if ($statut && $statut != '') {
            $query->where('statut', $statut);
        }

        if ($search && $search != '') {
            $query->where(function($q) use ($search) {
                $q->where('matricule', 'like', "%$search%")
                  ->orWhere('centre', 'like', "%$search%")
                  ->orWhereHas('ambulancier', function($q2) use ($search) {
                      $q2->where('nom', 'like', "%$search%")
                         ->orWhere('prenom', 'like', "%$search%");
                  });
            });
        }

        $ambulances = $query->get();
        $availableAmbulances = Ambulance::whereRaw('LOWER(commune) = ?', [mb_strtolower($territoire['commune'])])
            ->where('statut', 'disponible')
            ->whereNull('ambulancier_id')
            ->orderBy('matricule')
            ->get();

        $ambulanciers = \App\Models\Ambulancier::with('ambulance')->whereRaw('LOWER(centre) = ?', [mb_strtolower($territoire['commune'])])->orderBy('nom')->get();
        // Ambualanciers disponibles pour assignation depuis la fiche ambulance (non assignés)
        $availableAmbulanciers = \App\Models\Ambulancier::whereRaw('LOWER(centre) = ?', [mb_strtolower($territoire['commune'])])->whereNull('ambulance_id')->orderBy('nom')->get();
        $communes = collect([$territoire['commune']])->filter()->unique()->values();

        return view('regulateur.gestion-flotte', compact('ambulances', 'availableAmbulances', 'statut', 'search', 'ambulanciers', 'communes', 'availableAmbulanciers'));
    }

    public function statistiques()
    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }

        $regulateur = Regulateur::find(session('regulateur_id'));
        $territoire = $this->resolveTerritory($regulateur);
        $communesLower  = $territoire['communes']->map(fn($commune) => mb_strtolower($commune))->toArray();

        // KPI
        $totalAlertesMois = Alerte::whereMonth('created_at', now()->month)
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->count();

        $alertesTerminees = Alerte::whereMonth('created_at', now()->month)
            ->where('statut', 'terminee')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->count();

        $tauxTraitement   = $totalAlertesMois > 0 ? round(($alertesTerminees / $totalAlertesMois) * 100, 1) : 0;
        $faussesAlertes   = Signalement::whereMonth('created_at', now()->month)
            ->whereHas('alerte', fn($q) => $q->whereIn(DB::raw('LOWER(commune)'), $communesLower))
            ->count();

        // Alertes par jour (30 derniers jours)
        $alertesParJour = Alerte::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(30))
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $alertesParJour->pluck('date')->map(fn($d) => Carbon::parse($d)->locale('fr')->isoFormat('D MMM'));
        $data   = $alertesParJour->pluck('total');

        // Répartition par commune
        $parCommune = Alerte::selectRaw('commune, COUNT(*) as total')
            ->whereIn(DB::raw('LOWER(commune)'), $communesLower)
            ->groupBy('commune')
            ->get();

        $communeLabels = $parCommune->pluck('commune');
        $communeData   = $parCommune->pluck('total');

        return view('regulateur.statistiques', compact(
            'totalAlertesMois', 'tauxTraitement', 'faussesAlertes',
            'labels', 'data', 'communeLabels', 'communeData'
        ));
    }
    
    public function parametres()
{
    if (!session('regulateur_id')) {
        return redirect('/regulateur/login');
    }
    $regulateur = Regulateur::find(session('regulateur_id'));
    return view('regulateur.parametres', compact('regulateur'));
}
public function updateAmbulance(Request $request, $id)
{
    if (!session('regulateur_id')) {
        return redirect('/regulateur/login');
    }
 
    $regulateur = Regulateur::find(session('regulateur_id'));
    $territoire = $this->resolveTerritory($regulateur);
 
    $request->validate([
        'matricule'      => 'required|string|max:255',
        'modele'         => 'nullable|string|max:255',
        'centre'         => ['required', 'string', Rule::in([$territoire['commune']])],
        'statut'         => 'required|in:disponible,en_mission,maintenance',
        'ambulancier_id' => 'nullable|exists:ambulanciers,id',
    ]);
 
    $ambulance = Ambulance::findOrFail($id);
    $ambulance->update([
        'matricule'      => $request->matricule,
        'modele'         => $request->modele,
        'centre'         => $territoire['commune'],
        'statut'         => $request->statut,
        'ambulancier_id' => $request->ambulancier_id ?: null,
    ]);
 
    return redirect('/regulateur/flotte')->with('success', 'Ambulance mise à jour avec succès !');
}

public function ajouterAmbulancier(Request $request)
{
    if (!session('regulateur_id')) {
        return redirect('/regulateur/login');
    }
 
    $regulateur = Regulateur::find(session('regulateur_id'));
    $territoire = $this->resolveTerritory($regulateur);
 
    $request->validate([
        'nom'          => 'required|string|max:255',
        'prenom'       => 'required|string|max:255',
        'matricule'    => 'required|string|max:255|unique:ambulanciers,matricule',
        'mot_de_passe' => 'required|string|min:6',
        'centre'       => ['required', 'string', Rule::in([$territoire['commune']])],
        'ambulance_id' => 'nullable|exists:ambulances,id',
    ]);
 
    $ambulancier = \App\Models\Ambulancier::create([
        'nom'          => $request->nom,
        'prenom'       => $request->prenom,
        'matricule'    => $request->matricule,
        'mot_de_passe' => \Illuminate\Support\Facades\Hash::make($request->mot_de_passe),
        'centre'       => $territoire['commune'],
        'ambulance_id' => $request->ambulance_id ?: null,
        'statut'       => 'disponible',
    ]);

    // Lier l'ambulance à cet ambulancier si assigné
    if ($request->ambulance_id) {
        \App\Models\Ambulance::find($request->ambulance_id)
            ->update(['ambulancier_id' => $ambulancier->id]);
    }

    return redirect('/regulateur/flotte')->with('success', 'Ambulancier créé avec succès !');
}

public function changerMotDePasse(Request $request)
{
    if (!session('regulateur_id')) {
        return redirect('/regulateur/login');
    }

    $request->validate([
        'ancien_mot_de_passe'  => 'required',
        'nouveau_mot_de_passe' => 'required|min:6',
        'confirmation'         => 'required|same:nouveau_mot_de_passe',
    ]);

    $regulateur = Regulateur::find(session('regulateur_id'));

    if (!Hash::check($request->ancien_mot_de_passe, $regulateur->mot_de_passe)) {
        return back()->withErrors(['message' => 'Ancien mot de passe incorrect']);
    }

    $regulateur->update([
        'mot_de_passe' => Hash::make($request->nouveau_mot_de_passe)
    ]);

    return redirect('/regulateur/parametres')->with('success', 'Mot de passe modifié avec succès !');
}

public function ajouterAmbulance(Request $request)
{
    if (!session('regulateur_id')) {
        return redirect('/regulateur/login');
    }

    $regulateur = Regulateur::find(session('regulateur_id'));
    $territoire = $this->resolveTerritory($regulateur);

    $request->validate([
        'matricule' => 'required|string|max:255|unique:ambulances,matricule',
        'modele'    => 'nullable|string|max:255',
    ]);

    $ambulance = Ambulance::create([
        'matricule' => $request->matricule,
        'modele'    => $request->modele,
        'centre'    => $territoire['commune'],
        'commune'   => $territoire['commune'],
        'statut'    => 'disponible',
    ]);

    return redirect('/regulateur/flotte')->with('success', 'Ambulance ajoutée avec succès !');
}

public function assignerAmbulance(Request $request)
{
    if (!session('regulateur_id')) {
        return redirect('/regulateur/login');
    }

    $request->validate([
        'ambulancier_id' => 'required|exists:ambulanciers,id',
        'ambulance_id'   => 'required|exists:ambulances,id',
    ]);

    $regulateur = Regulateur::find(session('regulateur_id'));
    $territoire = $this->resolveTerritory($regulateur);

    $ambulancier = \App\Models\Ambulancier::findOrFail($request->ambulancier_id);
    $ambulance = Ambulance::findOrFail($request->ambulance_id);

    // Vérifier qu'ils appartiennent au territoire du régulateur
    if (mb_strtolower($ambulancier->centre) !== mb_strtolower($territoire['commune']) || mb_strtolower($ambulance->commune) !== mb_strtolower($territoire['commune'])) {
        return redirect('/regulateur/flotte')->withErrors(['message' => 'L\'ambulancier ou l\'ambulance sélectionné(e) n\'appartient pas à votre commune.']);
    }

    // Assigner de manière atomique en évitant les doublons
    DB::transaction(function() use ($ambulance, $ambulancier) {
        // Si l'ambulancier avait déjà une ambulance, la libérer
        if ($ambulancier->ambulance_id && $ambulancier->ambulance_id != $ambulance->id) {
            $oldAmb = Ambulance::find($ambulancier->ambulance_id);
            if ($oldAmb) {
                $oldAmb->update(['ambulancier_id' => null, 'statut' => 'disponible']);
            }
        }

        // Si l'ambulance était assignée à un autre ambulancier, détacher cet ambulancier
        if ($ambulance->ambulancier_id && $ambulance->ambulancier_id != $ambulancier->id) {
            $other = \App\Models\Ambulancier::find($ambulance->ambulancier_id);
            if ($other) {
                $other->update(['ambulance_id' => null]);
            }
        }

        // Assigner la nouvelle ambulance
        $ambulance->update([
            'ambulancier_id' => $ambulancier->id,
            'statut' => 'disponible',
        ]);

        $ambulancier->update(['ambulance_id' => $ambulance->id]);
    });

    return redirect('/regulateur/flotte')->with('success', 'Ambulance assignée à l\'ambulancier avec succès !');
}

    public function detacherAmbulance(Request $request)
    {
        if (!session('regulateur_id')) {
            return redirect('/regulateur/login');
        }

        $request->validate([
            'ambulancier_id' => 'nullable|exists:ambulanciers,id',
            'ambulance_id'   => 'nullable|exists:ambulances,id',
        ]);

        $regulateur = Regulateur::find(session('regulateur_id'));
        $territoire = $this->resolveTerritory($regulateur);

        if ($request->ambulancier_id) {
            $ambulancier = \App\Models\Ambulancier::findOrFail($request->ambulancier_id);
            if (mb_strtolower($ambulancier->centre) !== mb_strtolower($territoire['commune'])) {
                return redirect('/regulateur/flotte')->withErrors(['message' => 'L\'ambulancier n\'appartient pas à votre commune.']);
            }
            $ambulance = Ambulance::find($ambulancier->ambulance_id);

            DB::transaction(function() use ($ambulancier, $ambulance) {
                if ($ambulance) {
                    $ambulance->update(['ambulancier_id' => null, 'statut' => 'disponible']);
                }
                $ambulancier->update(['ambulance_id' => null]);
            });

            return redirect('/regulateur/flotte')->with('success', 'Détachement effectué avec succès !');
        }

        if ($request->ambulance_id) {
            $ambulance = Ambulance::findOrFail($request->ambulance_id);
            if (mb_strtolower($ambulance->commune) !== mb_strtolower($territoire['commune'])) {
                return redirect('/regulateur/flotte')->withErrors(['message' => 'L\'ambulance n\'appartient pas à votre commune.']);
            }

            $other = \App\Models\Ambulancier::find($ambulance->ambulancier_id);

            DB::transaction(function() use ($ambulance, $other) {
                $ambulance->update(['ambulancier_id' => null, 'statut' => 'disponible']);
                if ($other) {
                    $other->update(['ambulance_id' => null]);
                }
            });

            return redirect('/regulateur/flotte')->with('success', 'Détachement effectué avec succès !');
        }

        return redirect('/regulateur/flotte')->withErrors(['message' => 'Paramètre manquant pour le détachement.']);
    }

    public function deconnexion() {
        session()->forget(['regulateur_id', 'regulateur_nom']);
        return redirect('/');
    }
}