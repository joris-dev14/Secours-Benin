<?php
namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Alerte;
use App\Models\Ambulance;
use App\Models\Ambulancier;
use App\Models\Commune;
use App\Models\Regulateur;
use App\Models\Rapport;
use App\Exports\ReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelType;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login-admin');
    }

   public function authentifier(Request $request)
{
    $request->validate([
        'matricule'    => 'required',
        'mot_de_passe' => 'required',
    ]);

    $admin = Admin::where('matricule', $request->matricule)
    ->where('statut', 'actif')
    ->first();

    if (!$admin || !Hash::check($request->mot_de_passe, $admin->mot_de_passe)) {
        return back()->withErrors(['message' => 'Identifiants incorrects']);
    }

    session(['admin_id' => $admin->id, 'admin_nom' => $admin->nom]);

    return redirect('/admin/dashboard');
}
   public function dashboard()
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    // KPIs principaux
    $totalAlertesMois = Alerte::whereMonth('created_at', now()->month)->count();
    $alertesTerminees = Alerte::whereMonth('created_at', now()->month)->where('statut', 'terminee')->count();
    $tauxTraitement   = $totalAlertesMois > 0 ? round(($alertesTerminees / $totalAlertesMois) * 100, 1) : 0;
   $tempsMoyen = round(\App\Models\Mission::whereNotNull('termine_a')
    ->where('statut', 'terminee')
    ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, termine_a)) as moy')
    ->value('moy') ?? 0, 1);
    $ambulancesDispo  = Ambulance::where('statut', 'disponible')->count();
    $ambulancesTotal  = Ambulance::count();
    $ambulancesMaint  = Ambulance::where('statut', 'maintenance')->count();

    // Alertes par commune
    $parCommune = Alerte::selectRaw('commune, COUNT(*) as total')
        ->groupBy('commune')->orderByDesc('total')->get();

    // Alertes par mois (12 derniers mois)
    $alertesParMois = Alerte::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->whereYear('created_at', now()->year)
        ->groupBy('mois')->orderBy('mois')->get();

    // Performance par centre
$performanceCentres = \App\Models\Mission::selectRaw('
        ambulances.centre as centre,
        COUNT(*) as total_alertes,
        AVG(TIMESTAMPDIFF(MINUTE, missions.created_at, missions.termine_a)) as temps_moyen,
        SUM(CASE WHEN missions.statut = "terminee" THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as taux
    ')
    ->leftJoin('ambulances', 'missions.ambulance_id', '=', 'ambulances.id')
    ->groupBy('ambulances.centre')
    ->get();

// Top ambulanciers
$topAmbulanciers = \App\Models\Mission::selectRaw('ambulances.ambulancier_id, COUNT(*) as total_missions')
    ->join('ambulances', 'missions.ambulance_id', '=', 'ambulances.id')
    ->where('missions.statut', 'terminee')
    ->whereMonth('missions.created_at', now()->month)
    ->groupBy('ambulances.ambulancier_id')
    ->orderByDesc('total_missions')
    ->take(5)
    ->get()
    ->map(function($item) {
        $ambulancier = \App\Models\Ambulancier::find($item->ambulancier_id);
        return [
            'nom'            => $ambulancier ? $ambulancier->nom . ' ' . $ambulancier->prenom : '—',
            'matricule'      => $ambulancier ? $ambulancier->matricule : '—',
            'total_missions' => $item->total_missions,
        ];
    });
    return view('admin.admin-dashboard', compact(
        'totalAlertesMois', 'tauxTraitement', 'tempsMoyen',
        'ambulancesDispo', 'ambulancesTotal', 'ambulancesMaint',
        'parCommune', 'alertesParMois', 'performanceCentres', 'topAmbulanciers'
    ));
}

    public function utilisateurs(Request $request)
{
        if (!session('admin_id')) {
         return redirect('/admin/login');
        }

        $search = $request->search;
        $role   = $request->role;
        $statut = $request->statut;
    $communes = Commune::orderBy('departement')->orderBy('nom')->get();

     // Citoyens
         $citoyens = \App\Models\Citoyen::query();
        if ($search) $citoyens->where('telephone', 'like', "%$search%");
        $citoyens = $citoyens->get()->map(function($c) {
          return [
            'id'         => $c->id,
            'nom'        => $c->telephone,
            'matricule'  => null,
            'role'       => 'citoyen',
            'telephone'  => $c->telephone,
            'centre'     => '—',
            'statut'     => 'actif',
            'created_at' => $c->created_at,
        ];
        });

    // Régulateurs
    $regulateurs = \App\Models\Regulateur::query();
    if ($search) $regulateurs->where(function($q) use ($search) {
        $q->where('nom', 'like', "%$search%")
          ->orWhere('matricule', 'like', "%$search%");
    });
    if ($statut) $regulateurs->where('statut', $statut);
    $regulateurs = $regulateurs->get()->map(function($r) {
        return [
            'id'         => $r->id,
            'nom'        => $r->nom . ' ' . $r->prenom,
            'matricule'  => $r->matricule,
            'role'       => 'regulateur',
            'telephone'  => '—',
            'centre'     => $r->centre,
            'statut'     => $r->statut,
            'created_at' => $r->created_at,
        ];
    });

    // Ambulanciers
    $ambulanciers = \App\Models\Ambulancier::query();
    if ($search) $ambulanciers->where(function($q) use ($search) {
    $q->where('nom', 'like', "%$search%")
      ->orWhere('matricule', 'like', "%$search%");
    });
    if ($statut) $ambulanciers->where('statut', $statut);
    $ambulanciers = $ambulanciers->get()->map(function($a) {
    return [
        'id'             => $a->id,
        'nom'            => $a->nom . ' ' . $a->prenom,
        'matricule'      => $a->matricule,
        'role'           => 'ambulancier',
        'telephone'      => '—',
        'centre'         => $a->centre,
        'statut'         => $a->statut,
        'statut_display' => in_array($a->statut, ['disponible', 'en_mission']) ? 'actif' : 'inactif',
        'created_at'     => $a->created_at,
    ];
    });
    // Fusionner tous les utilisateurs
    $utilisateurs = collect()
        ->merge($role == 'citoyen' || !$role ? $citoyens : [])
        ->merge($role == 'regulateur' || !$role ? $regulateurs : [])
        ->merge($role == 'ambulancier' || !$role ? $ambulanciers : [])
        ->sortByDesc('created_at');

    // Stats
    $totalCitoyens    = \App\Models\Citoyen::count();
    $totalRegulateurs = \App\Models\Regulateur::count();
    $totalAmbulanciers = \App\Models\Ambulancier::count();
    $total = $totalCitoyens + $totalRegulateurs + $totalAmbulanciers;

    return view('admin.gestion-utilisateurs', compact(
        'utilisateurs', 'total', 'totalCitoyens',
        'totalRegulateurs', 'totalAmbulanciers',
        'search', 'role', 'statut', 'communes'
    ));
}
public function territoire()
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $ambulancesParCommune = Ambulance::selectRaw('commune, COUNT(*) as total')
        ->groupBy('commune')
        ->pluck('total', 'commune');

    $citoyensParCommune = Alerte::selectRaw('commune, COUNT(DISTINCT citoyen_id) as total')
        ->groupBy('commune')
        ->pluck('total', 'commune');

    $communes = \App\Models\Commune::with('hopitaux')
        ->orderBy('nom')
        ->get()
        ->map(function($commune) use ($ambulancesParCommune, $citoyensParCommune) {
            $nomLower = strtolower($commune->nom);

            return [
                'id'               => $commune->id,
                'nom'              => $commune->nom,
                'departement'      => $commune->departement,
                'centre_samu'      => $commune->centre_samu,
                'numero_vert'      => $commune->numero_vert,
                'latitude'         => $commune->latitude,
                'longitude'        => $commune->longitude,
                'rayon_couverture' => $commune->rayon_couverture,
                'redirection_auto' => $commune->redirection_auto,
                'statut'           => $commune->statut,
                'hopitaux' => $commune->hopitaux->map(fn($h) => ['id' => $h->id, 'nom' => $h->nom]),
                'total_ambulances' => $ambulancesParCommune[$commune->nom] ?? 0,
                'total_citoyens'   => $citoyensParCommune[$nomLower] ?? 0,
            ];
        });

    return view('admin.gestion-territoire', compact('communes'));
}

public function sauvegarderTerritoire(Request $request, $id = null)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    if (!$id) {
        return redirect('/admin/territoire')->with('success', 'Aucune commune sélectionnée.');
    }

    $commune = \App\Models\Commune::findOrFail($id);

    $commune->update([
        'centre_samu'      => $request->centre_samu,
        'numero_vert'      => $request->numero_vert,
        'latitude'         => $request->latitude ?: null,
        'longitude'        => $request->longitude ?: null,
        'rayon_couverture' => $request->rayon_couverture,
        'redirection_auto' => $request->has('redirection_auto') ? 1 : 0,
    ]);

    return redirect('/admin/territoire')->with('success', 'Commune "' . $commune->nom . '" mise à jour avec succès !');
}

public function ajouterHopital(Request $request, $id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $request->validate(['nom' => 'required|string|max:255']);

    \App\Models\Hopital::create([
        'commune_id' => $id,
        'nom'        => $request->nom,
    ]);

    return redirect('/admin/territoire')->with('success', 'Hôpital ajouté avec succès !');
}

public function supprimerHopital($id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    \App\Models\Hopital::findOrFail($id)->delete();

    return redirect('/admin/territoire')->with('success', 'Hôpital supprimé avec succès !');
}
    public function updateCommune(Request $request, $id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $request->validate([
        'centre_samu'      => 'required|string',
        'numero_vert'      => 'nullable|string',
        'rayon_couverture' => 'required|integer',
    ]);

    \App\Models\Commune::find($id)->update([
        'centre_samu'      => $request->centre_samu,
        'numero_vert'      => $request->numero_vert,
        'rayon_couverture' => $request->rayon_couverture,
        'redirection_auto' => $request->has('redirection_auto'),
    ]);

    return redirect('/admin/territoire')->with('success', 'Configuration mise à jour avec succès !');
}
    public function rapports()
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }
    $historique = \App\Models\Rapport::where('admin_id', session('admin_id'))->latest()->get();
$departements = Commune::distinct()->orderBy('departement')->pluck('departement');
$communes = Commune::orderBy('nom')->get(['nom', 'departement']);
return view('admin.rapports', compact('historique', 'departements', 'communes'));
}
    public function exportCsv()
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $alertes = Alerte::with('citoyen')->latest()->get();

    $filename = 'alertes-secours-benin-' . now()->format('Y-m-d') . '.csv';

    $headers = [
        'Content-Type'        => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($alertes) {
        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM UTF-8

        // En-têtes
        fputcsv($file, ['ID', 'Commune', 'Statut', 'Latitude', 'Longitude', 'Description', 'Citoyen', 'Date'], ';');

        foreach ($alertes as $alerte) {
            fputcsv($file, [
                $alerte->id,
                $alerte->commune,
                $alerte->statut,
                $alerte->latitude ?? '—',
                $alerte->longitude ?? '—',
                $alerte->description ?? '—',
                $alerte->citoyen->telephone ?? '—',
                $alerte->created_at->format('d/m/Y H:i'),
            ], ';');
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function exportExcel()
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\AlertesExport(),
        'alertes-secours-benin-' . now()->format('Y-m-d') . '.xlsx'
    );
}
    public function exportPdf()
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $totalAlertesMois = Alerte::whereMonth('created_at', now()->month)->count();
    $alertesTerminees = Alerte::whereMonth('created_at', now()->month)->where('statut', 'terminee')->count();
    $tauxTraitement   = $totalAlertesMois > 0 ? round(($alertesTerminees / $totalAlertesMois) * 100, 1) : 0;

    $parCommune = Alerte::selectRaw('commune, COUNT(*) as total')
        ->groupBy('commune')->orderByDesc('total')->get();

    $performanceCentres = \App\Models\Mission::selectRaw('
            ambulances.centre as centre,
            COUNT(*) as total_alertes,
            AVG(TIMESTAMPDIFF(MINUTE, missions.created_at, missions.termine_a)) as temps_moyen,
            SUM(CASE WHEN missions.statut = "terminee" THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as taux
        ')
        ->leftJoin('ambulances', 'missions.ambulance_id', '=', 'ambulances.id')
        ->groupBy('ambulances.centre')
        ->get();

    $admin = Admin::find(session('admin_id'));

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.rapport-pdf', compact(
        'totalAlertesMois', 'tauxTraitement',
        'parCommune', 'performanceCentres', 'admin'
    ));

    return $pdf->download('rapport-admin-secours-benin-' . now()->format('Y-m-d') . '.pdf');
}

   public function genererRapport(Request $request)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }
 
    $request->validate([
        'type'       => 'required|in:global,flotte,chauffeurs,securite',
        'date_debut' => 'required|date',
        'date_fin'   => 'required|date|after_or_equal:date_debut',
        'centre'     => 'nullable|exists:communes,departement',
        'commune'    => 'nullable|exists:communes,nom',
        'format'     => 'required|in:pdf,excel,csv',
    ]);
 
    $dateDebut = \Carbon\Carbon::parse($request->date_debut)->startOfDay();
    $dateFin   = \Carbon\Carbon::parse($request->date_fin)->endOfDay();
    $admin     = Admin::find(session('admin_id'));
    $departement = $request->centre;
    $commune = $request->commune;
    $communesInDepartement = null;
 
    if ($departement && !$commune) {
        $communesInDepartement = Commune::where('departement', $departement)->pluck('nom');
    }
 
    switch ($request->type) {

        case 'global':
            $query = Alerte::whereBetween('created_at', [$dateDebut, $dateFin]);
            if ($commune) {
                $query->whereRaw('LOWER(commune) = ?', [mb_strtolower($commune)]);
            } elseif ($communesInDepartement && $communesInDepartement->isNotEmpty()) {
                $query->whereIn('commune', $communesInDepartement);
            }

            $totalAlertes     = $query->count();
            $alertesTerminees = (clone $query)->where('statut', 'terminee')->count();
            $tauxTraitement   = $totalAlertes > 0 ? round(($alertesTerminees / $totalAlertes) * 100, 1) : 0;

            $tempsMoyenQuery = \App\Models\Mission::whereNotNull('termine_a')
                ->where('missions.statut', 'terminee')
                ->whereBetween('missions.created_at', [$dateDebut, $dateFin]);

            if ($commune) {
                $tempsMoyenQuery->join('ambulances', 'missions.ambulance_id', '=', 'ambulances.id')
                    ->whereRaw('LOWER(ambulances.commune) = ?', [mb_strtolower($commune)]);
            } elseif ($communesInDepartement && $communesInDepartement->isNotEmpty()) {
                $tempsMoyenQuery->join('ambulances', 'missions.ambulance_id', '=', 'ambulances.id')
                    ->join('communes', 'ambulances.commune', '=', 'communes.nom')
                    ->where('communes.departement', $departement);
            }
            $tempsMoyen = round($tempsMoyenQuery->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, missions.created_at, missions.termine_a)) as moy')->value('moy') ?? 0, 1);

            $parCommune = (clone $query)->selectRaw('commune, COUNT(*) as total')
                ->groupBy('commune')->orderByDesc('total')->get();

            $vue   = 'admin.rapport-genere-pdf';
            $data  = compact('totalAlertes', 'tauxTraitement', 'tempsMoyen', 'parCommune', 'admin', 'dateDebut', 'dateFin');
            $titre = 'Rapport activité globale';
            if ($commune) {
                $titre .= ' - Commune ' . ucfirst($commune);
            } elseif ($departement) {
                $titre .= ' - Département ' . $departement;
            }
 
            $exportHeadings = ['Statistique', 'Valeur'];
            $exportRows = [
                ['Type de rapport', 'Activité globale'],
                ['Période', $dateDebut->format('d/m/Y') . ' - ' . $dateFin->format('d/m/Y')],
                ['Total alertes', $totalAlertes],
                ['Alertes terminées', $alertesTerminees],
                ['Taux de traitement (%)', $tauxTraitement],
                ['Temps moyen (min)', $tempsMoyen],
                [],
                ['Commune', 'Total alertes'],
            ];
            foreach ($parCommune as $ligne) {
                $exportRows[] = [ucfirst($ligne->commune), $ligne->total];
            }
            break;

        case 'flotte':
            $flotteQuery = \App\Models\Mission::whereBetween('missions.created_at', [$dateDebut, $dateFin])
                ->join('ambulances', 'missions.ambulance_id', '=', 'ambulances.id');

            if ($commune) {
                $flotteQuery->whereRaw('LOWER(ambulances.commune) = ?', [mb_strtolower($commune)]);
            } elseif ($communesInDepartement && $communesInDepartement->isNotEmpty()) {
                $flotteQuery->join('communes', 'ambulances.commune', '=', 'communes.nom')
                    ->where('communes.departement', $departement);
            }

            $performanceFlotte = (clone $flotteQuery)
                ->selectRaw('ambulances.matricule, ambulances.centre,
                    COUNT(*) as total_missions,
                    AVG(TIMESTAMPDIFF(MINUTE, missions.created_at, missions.termine_a)) as temps_moyen,
                    SUM(CASE WHEN missions.statut = "terminee" THEN 1 ELSE 0 END) * 100.0 / COUNT(*) as taux')
                ->groupBy('ambulances.id', 'ambulances.matricule', 'ambulances.centre')
                ->orderByDesc('total_missions')
                ->get();

            $totalAmbulances   = Ambulance::when($commune, fn($q) => $q->whereRaw('LOWER(commune) = ?', [mb_strtolower($commune)]))
                ->when(!$commune && $communesInDepartement && $communesInDepartement->isNotEmpty(), fn($q) => $q->whereIn('commune', $communesInDepartement))->count();
            $ambulancesActives = (clone $flotteQuery)->distinct('ambulances.id')->count('ambulances.id');

            $vue   = 'admin.rapport-flotte-pdf';
            $data  = compact('performanceFlotte', 'totalAmbulances', 'ambulancesActives', 'admin', 'dateDebut', 'dateFin');
            $titre = 'Rapport performance flotte';
            if ($commune) {
                $titre .= ' - Commune ' . ucfirst($commune);
            } elseif ($departement) {
                $titre .= ' - Département ' . $departement;
            }
 
            $exportHeadings = ['Matricule', 'Centre', 'Total missions', 'Temps moyen (min)', 'Taux de réussite (%)'];
            $exportRows = [];
            foreach ($performanceFlotte as $ligne) {
                $exportRows[] = [
                    $ligne->matricule,
                    $ligne->centre,
                    $ligne->total_missions,
                    round($ligne->temps_moyen ?? 0, 1),
                    round($ligne->taux ?? 0, 1),
                ];
            }
            break;

        case 'chauffeurs':
            $chauffeursQuery = \App\Models\Mission::whereBetween('missions.created_at', [$dateDebut, $dateFin])
                ->join('ambulances', 'missions.ambulance_id', '=', 'ambulances.id')
                ->join('ambulanciers', 'ambulances.id', '=', 'ambulanciers.ambulance_id')
                ->where('missions.statut', 'terminee');

            if ($commune) {
                $chauffeursQuery->whereRaw('LOWER(ambulances.commune) = ?', [mb_strtolower($commune)]);
            } elseif ($communesInDepartement && $communesInDepartement->isNotEmpty()) {
                $chauffeursQuery->join('communes', 'ambulances.commune', '=', 'communes.nom')
                    ->where('communes.departement', $departement);
            }

            $performanceChauffeurs = (clone $chauffeursQuery)
                ->selectRaw('ambulanciers.nom, ambulanciers.prenom, ambulanciers.matricule,
                    COUNT(*) as total_missions,
                    AVG(TIMESTAMPDIFF(MINUTE, missions.created_at, missions.termine_a)) as temps_moyen')
                ->groupBy('ambulanciers.id', 'ambulanciers.nom', 'ambulanciers.prenom', 'ambulanciers.matricule')
                ->orderByDesc('total_missions')
                ->get();

            $vue   = 'admin.rapport-chauffeurs-pdf';
            $data  = compact('performanceChauffeurs', 'admin', 'dateDebut', 'dateFin');
            $titre = 'Rapport performance chauffeurs';
            if ($commune) {
                $titre .= ' - Commune ' . ucfirst($commune);
            } elseif ($departement) {
                $titre .= ' - Département ' . $departement;
            }
 
            $exportHeadings = ['Nom', 'Prénom', 'Matricule', 'Total missions', 'Temps moyen (min)'];
            $exportRows = [];
            foreach ($performanceChauffeurs as $ligne) {
                $exportRows[] = [
                    $ligne->nom,
                    $ligne->prenom,
                    $ligne->matricule,
                    $ligne->total_missions,
                    round($ligne->temps_moyen ?? 0, 1),
                ];
            }
            break;

        case 'securite':
            $signalementsQuery = \App\Models\Signalement::whereBetween('created_at', [$dateDebut, $dateFin]);
            if ($commune) {
                $signalementsQuery->whereHas('alerte', fn($q) => $q->whereRaw('LOWER(commune) = ?', [mb_strtolower($commune)]));
            } elseif ($communesInDepartement && $communesInDepartement->isNotEmpty()) {
                $signalementsQuery->whereHas('alerte', fn($q) => $q->whereIn('commune', $communesInDepartement));
            }

            $totalSignalements = (clone $signalementsQuery)->count();
            $fausses           = (clone $signalementsQuery)->where('statut', 'traite')->count();
            $classees          = (clone $signalementsQuery)->where('statut', 'classe')->count();
            $enAttente         = (clone $signalementsQuery)->where('statut', 'en_attente')->count();
            $comptesBloques    = \App\Models\Citoyen::where('consentement', false)->count();

            $listeSignalements = (clone $signalementsQuery)->with(['alerte', 'regulateur'])->latest()->get();

            $vue   = 'admin.rapport-securite-pdf';
            $data  = compact('totalSignalements', 'fausses', 'classees', 'enAttente', 'comptesBloques', 'listeSignalements', 'admin', 'dateDebut', 'dateFin');
            $titre = 'Rapport sécurité et modération';
            if ($commune) {
                $titre .= ' - Commune ' . ucfirst($commune);
            } elseif ($departement) {
                $titre .= ' - Département ' . $departement;
            }
 
            $exportHeadings = ['Type de signalement', 'Valeur'];
            $exportRows = [
                ['Total signalements', $totalSignalements],
                ['Fausse alertes', $fausses],
                ['Classées', $classees],
                ['En attente', $enAttente],
                ['Comptes bloqués', $comptesBloques],
                [],
                ['Alerte ID', 'Commune', 'Statut', 'Créé le'],
            ];
            foreach ($listeSignalements as $signalement) {
                $exportRows[] = [
                    $signalement->id,
                    $signalement->alerte?->commune ?? '—',
                    $signalement->statut,
                    $signalement->created_at?->format('d/m/Y H:i') ?? '—',
                ];
            }
            break;
    }

    if ($request->format === 'pdf') {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($vue, $data);
 
        $nomFichier = 'rapport-' . $request->type . '-' . now()->format('Ymd-His') . '.pdf';
        if (!file_exists(storage_path('app/public/rapports'))) {
            mkdir(storage_path('app/public/rapports'), 0755, true);
        }
        $cheminComplet = storage_path('app/public/rapports/' . $nomFichier);
        $pdf->save($cheminComplet);
        $taille = filesize($cheminComplet);
    } else {
        $ext = $request->format === 'excel' ? 'xlsx' : 'csv';
        $nomFichier = 'rapport-' . $request->type . '-' . now()->format('Ymd-His') . '.' . $ext;
        $cheminRelatif = 'rapports/' . $nomFichier;
 
        if (!Storage::disk('public')->exists('rapports')) {
            Storage::disk('public')->makeDirectory('rapports');
        }
 
        Excel::store(new ReportExport($exportHeadings, $exportRows), $cheminRelatif, 'public', $request->format === 'excel' ? ExcelType::XLSX : ExcelType::CSV);
 
        $cheminComplet = storage_path('app/public/' . $cheminRelatif);
        $taille = Storage::disk('public')->size($cheminRelatif);
    }
 
    Rapport::create([
        'admin_id'   => session('admin_id'),
        'titre'      => $titre . ' - ' . $dateDebut->format('d/m/Y') . ' au ' . $dateFin->format('d/m/Y'),
        'type'       => $request->type,
        'date_debut' => $request->date_debut,
        'date_fin'   => $request->date_fin,
        'centre'     => $departement,
        'commune'    => $commune,
        'format'     => $request->format,
        'fichier'    => 'rapports/' . $nomFichier,
        'taille'     => $taille,
    ]);
 
    return response()->download($cheminComplet)->deleteFileAfterSend(false);
}

    public function creerUtilisateur(Request $request)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $request->validate([
        'nom'          => 'required|string',
        'prenom'       => 'required|string',
        'role'         => 'required|in:regulateur,ambulancier',
        'matricule'    => 'required|string',
        'centre'       => 'required|string',
        'mot_de_passe' => 'required|min:6',
    ]);

    if ($request->role === 'regulateur') {
        Regulateur::create([
            'nom'          => $request->nom,
            'prenom'       => $request->prenom,
            'matricule'    => $request->matricule,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'centre'       => $request->centre,
            'commune'      => $request->centre,
            'statut'       => 'actif',
        ]);
    } elseif ($request->role === 'ambulancier') {
        Ambulancier::create([
            'nom'          => $request->nom,
            'prenom'       => $request->prenom,
            'matricule'    => $request->matricule,
            'mot_de_passe' => Hash::make($request->mot_de_passe),
            'centre'       => $request->centre,
            'statut'       => 'disponible',
        ]);
    }

    return redirect('/admin/utilisateurs')->with('success', 'Utilisateur créé avec succès !');
}
public function bloquer($role, $id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    if ($role == 'regulateur') {
        Regulateur::find($id)->update(['statut' => 'inactif']);
    } elseif ($role == 'ambulancier') {
        Ambulancier::find($id)->update(['statut' => 'inactif']);
    }

    return redirect('/admin/utilisateurs')->with('success', 'Utilisateur bloqué avec succès !');
}

public function debloquer($role, $id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    if ($role == 'regulateur') {
        Regulateur::find($id)->update(['statut' => 'actif']);
    } elseif ($role == 'ambulancier') {
        Ambulancier::find($id)->update(['statut' => 'disponible']);
    }

    return redirect('/admin/utilisateurs')->with('success', 'Utilisateur débloqué avec succès !');
}
public function avertir(Request $request, $id)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $request->validate([
        'message' => 'required|string|max:500',
    ]);

    \App\Models\Avertissement::create([
        'citoyen_id' => $id,
        'admin_id'   => session('admin_id'),
        'message'    => $request->message,
        'statut'     => 'envoye',
    ]);

    return redirect('/admin/utilisateurs')->with('success', 'Avertissement envoyé avec succès !');
}
    public function supprimerSelection(Request $request)
{
    if (!session('admin_id')) {
        return redirect('/admin/login');
    }

    $ids = $request->ids ?? [];

    foreach ($ids as $item) {
        [$role, $id] = explode(':', $item);

        if ($role == 'citoyen') {
            \App\Models\Citoyen::find($id)->delete();
        } elseif ($role == 'regulateur') {
            Regulateur::find($id)->delete();
        } elseif ($role == 'ambulancier') {
            Ambulancier::find($id)->delete();
        }
    }

    return redirect('/admin/utilisateurs')->with('success', count($ids) . ' utilisateur(s) supprimé(s) avec succès !');
}
    public function modifier(Request $request, $role, $id)
{    if (!session('admin_id')) {
        return redirect('/admin/login');
    }
    $request->validate([
        'nom'       => 'required|string',
        'matricule' => 'required|string',
        'centre'    => 'required|string',
    ]);
    if ($role == 'regulateur') {
        Regulateur::find($id)->update([
            'nom'       => $request->nom,
            'matricule' => $request->matricule,
            'centre'    => $request->centre,
        ]);
    } elseif ($role == 'ambulancier') {
        Ambulancier::find($id)->update([
            'nom'       => $request->nom,
            'matricule' => $request->matricule,
            'centre'    => $request->centre,
        ]);
    }
    return redirect('/admin/utilisateurs')->with('success', 'Utilisateur modifié avec succès !');
}

    public function deconnexion(){
        session()->forget(['admin_id', 'admin_nom']);
        return redirect('/');
    }
}
    


