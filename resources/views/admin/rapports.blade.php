<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; --success-green: #10B981; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .sidebar-header i.building { font-size: 1.8rem; } .sidebar-header i.plus { font-size: 1rem; color: var(--accent-red); margin-left: -12px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        .main-content { margin-left: 260px; padding: 2rem; }
        
        .report-card { background: var(--white); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 1.5rem; }
        .report-type { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; cursor: pointer; transition: all 0.2s; text-align: center; height: 100%; }
        .report-type:hover { border-color: var(--primary-blue); background: rgba(10, 37, 64, 0.02); transform: translateY(-3px); }
        .report-type.selected { border-color: var(--accent-red); background: rgba(255, 82, 82, 0.05); }
        .report-type i { font-size: 2.5rem; color: var(--primary-blue); margin-bottom: 1rem; }
        
        .btn-export { background: var(--accent-red); color: white; border: none; min-height: 60px; border-radius: 14px; font-weight: 700; font-size: 1.1rem; width: 100%; transition: all 0.2s; box-shadow: 0 4px 15px rgba(255, 82, 82, 0.3); }
        .btn-export:hover { background: #ff3333; transform: translateY(-2px); color: white; }
        
        .history-item { background: var(--white); border-radius: 12px; padding: 1rem 1.5rem; margin-bottom: 0.8rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: all 0.2s; }
        .history-item:hover { transform: translateX(5px); }
        
@media (max-width: 991px) { 
    .sidebar { transform: translateX(-100%); } 
    .sidebar.show { transform: translateX(0); }
    .main-content { margin-left: 0; } 
    
}      </style>
    @include('partials.pwa-head')
</head>
<body>
    <nav class="sidebar show" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-user-shield building"></i><i class="fa-solid fa-plus plus"></i>
            <div><h5 class="mb-0 fw-bold">Secours Bénin</h5><small class="text-white-50">Direction SAMU</small></div>
        </div>
        <div class="d-flex flex-column mt-3">
            <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-chart-pie"></i> Tableau de bord</a>
            <a href="/admin/utilisateurs" class="nav-link"><i class="fa-solid fa-users-gear"></i> Utilisateurs</a>
            <a href="/admin/territoire" class="nav-link"><i class="fa-solid fa-map-location-dot"></i> Territoire</a>
            <a href="/admin/moderation" class="nav-link"><i class="fa-solid fa-shield-halved"></i> Modération</a>
            <a href="/admin/rapports" class="nav-link active"><i class="fa-solid fa-file-lines"></i> Rapports</a>
            <div class="mt-auto"><a href="/admin/login" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')"><i class="fa-solid fa-bars"></i></button>
                <h3 class="fw-bold mb-0">Génération de rapports</h3>
            </div>
        </div>

        <!-- Configuration du rapport -->
        <div class="report-card">
    <h5 class="fw-bold mb-4"><i class="fa-solid fa-cogs me-2"></i>Configuration du rapport</h5>

    @if($errors->has('message'))
        <div class="alert alert-danger" style="border-radius: 10px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $errors->first('message') }}
        </div>
    @endif

    <form method="POST" action="/admin/rapports/generer" id="rapportForm">
        @csrf
        <input type="hidden" name="type" id="type_input" value="global">

        <h6 class="fw-semibold mb-3">1. Type de rapport</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="report-type selected" onclick="selectType(this, 'global')">
                    <i class="fa-solid fa-chart-line"></i>
                    <h6 class="fw-bold mb-1">Activité globale</h6>
                    <small class="text-muted">Alertes, interventions, temps de réponse</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="report-type" onclick="selectType(this, 'flotte')">
                    <i class="fa-solid fa-truck-medical"></i>
                    <h6 class="fw-bold mb-1">Performance flotte</h6>
                    <small class="text-muted">Utilisation des ambulances</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="report-type" onclick="selectType(this, 'chauffeurs')">
                    <i class="fa-solid fa-user-tie"></i>
                    <h6 class="fw-bold mb-1">Performance chauffeurs</h6>
                    <small class="text-muted">Individuel et comparatif</small>
                </div>
            </div>
            <div class="col-md-3">
                <div class="report-type" onclick="selectType(this, 'securite')">
                    <i class="fa-solid fa-shield-halved"></i>
                    <h6 class="fw-bold mb-1">Sécurité & Modération</h6>
                    <small class="text-muted">Fausses alertes, blocages</small>
                </div>
            </div>
        </div>

        <h6 class="fw-semibold mb-3">2. Période</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Date de début</label>
                <input type="date" name="date_debut" class="form-control" value="{{ now()->subMonth()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Date de fin</label>
                <input type="date" name="date_fin" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Période rapide</label>
                <select class="form-select" onchange="periodeRapide(this.value)">
                    <option value="">Personnalisée</option>
                    <option value="7">7 derniers jours</option>
                    <option value="30">30 derniers jours</option>
                    <option value="90">Ce trimestre</option>
                    <option value="365">Cette année</option>
                </select>
            </div>
        </div>

        <h6 class="fw-semibold mb-3">3. Filtres additionnels</h6>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Département</label>
                <select class="form-select" name="centre" id="departementSelect">
                    <option value="">Tous les départements</option>
                    @foreach($departements as $departement)
                        <option value="{{ $departement }}" {{ old('centre') == $departement ? 'selected' : '' }}>{{ $departement }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Commune</label>
                <select class="form-select" name="commune" id="communeSelect">
                    <option value="">Toutes les communes</option>
                    @foreach($communes as $communeOption)
                        <option value="{{ $communeOption->nom }}" data-departement="{{ $communeOption->departement }}" {{ old('commune') == $communeOption->nom ? 'selected' : '' }}>{{ $communeOption->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Format d'export</label>
                <select class="form-select" name="format">
                    <option value="pdf">PDF (recommandé)</option>
                    <option value="excel">Excel (.xlsx)</option>
                    <option value="csv">CSV (.csv)</option>
                </select>
            </div>
        </div>

        <div class="alert alert-info d-flex align-items-start gap-2" style="border-radius: 12px; border: none; background: rgba(10, 37, 64, 0.05); color: var(--primary-blue);">
            <i class="fa-solid fa-circle-info mt-1"></i>
            <div>
                <strong>Informations incluses :</strong> Le rapport contiendra les statistiques globales et la répartition par commune pour la période sélectionnée.
            </div>
        </div>

        <button type="submit" class="btn btn-export mt-4">
            <i class="fa-solid fa-file-pdf me-2 fa-lg"></i> GÉNÉRER ET TÉLÉCHARGER LE RAPPORT
        </button>
    </form>
</div>

        <!-- Historique -->
        <div class="report-card">
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-clock-rotate-left me-2"></i>Rapports générés récemment</h5>

    @forelse($historique as $rapport)
        <div class="history-item">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                    <i class="fa-solid fa-file-{{ $rapport->format == 'pdf' ? 'pdf' : ($rapport->format == 'excel' ? 'excel' : 'csv') }}"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">{{ $rapport->titre }}</h6>
                    <small class="text-muted">
                        <i class="fa-regular fa-clock me-1"></i> Généré le {{ $rapport->created_at->locale('fr')->isoFormat('D MMMM YYYY [à] HH:mm') }}
                        • {{ round($rapport->taille / 1024, 1) }} KB
                    </small>
                </div>
            </div>
            <a href="{{ asset('storage/' . $rapport->fichier) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                <i class="fa-solid fa-download me-1"></i> Télécharger
            </a>
        </div>
    @empty
        <p class="text-muted text-center py-3">Aucun rapport généré pour le moment.</p>
    @endforelse
</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function selectType(el, type) {
        document.querySelectorAll('.report-type').forEach(t => t.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('type_input').value = type;
    }

    function periodeRapide(jours) {
        if (!jours) return;
        const fin = new Date();
        const debut = new Date();
        debut.setDate(debut.getDate() - parseInt(jours));

        document.querySelector('input[name="date_debut"]').value = debut.toISOString().split('T')[0];
        document.querySelector('input[name="date_fin"]').value = fin.toISOString().split('T')[0];
    }

    const allCommunes = @json($communes->map(function($commune) { return ['nom' => $commune->nom, 'departement' => $commune->departement]; }));
    const departementSelect = document.getElementById('departementSelect');
    const communeSelect = document.getElementById('communeSelect');

    function refreshCommuneOptions() {
        const selectedDepartement = departementSelect.value;
        const currentCommune = communeSelect.value;

        communeSelect.innerHTML = '<option value="">Toutes les communes</option>';

        const filteredCommunes = selectedDepartement
            ? allCommunes.filter(c => c.departement === selectedDepartement)
            : allCommunes;

        filteredCommunes.forEach(c => {
            const option = document.createElement('option');
            option.value = c.nom;
            option.textContent = c.nom;
            option.dataset.departement = c.departement;
            if (c.nom === currentCommune) {
                option.selected = true;
            }
            communeSelect.appendChild(option);
        });
    }

    departementSelect.addEventListener('change', refreshCommuneOptions);
    refreshCommuneOptions();

     document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = e.target.closest('[onclick*="sidebar"]');
    if (sidebar && !sidebar.contains(e.target) && !toggleBtn) {
        sidebar.classList.remove('show');
    }
});

</script>
 @include('partials.pwa-register')
</body>
</html>