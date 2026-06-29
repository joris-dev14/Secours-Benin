<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; --success-green: #10B981; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        
        /* Sidebar Identique */
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; transition: all 0.3s; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .sidebar-header i.truck { font-size: 1.8rem; }
        .sidebar-header i.plus { font-size: 1rem; color: var(--accent-red); margin-left: -12px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; border-radius: 0; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        
        .main-content { margin-left: 260px; padding: 2rem; transition: all 0.3s; }
        
        .history-card { background: var(--white); border-radius: 12px; padding: 1.2rem; margin-bottom: 1rem; border-left: 4px solid var(--success-green); box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: all 0.3s; }
        .history-card:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .history-card.cancelled { border-left-color: #cbd5e1; opacity: 0.7; }
        
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .badge-done { background: rgba(16, 185, 129, 0.1); color: var(--success-green); }
        .badge-cancel { background: rgba(100, 116, 139, 0.1); color: #64748b; }
        
        .filter-card { background: var(--white); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        
        .stat-mini { text-align: center; padding: 1rem; background: var(--bg-light); border-radius: 12px; }
        .stat-mini .value { font-size: 1.5rem; font-weight: 700; color: var(--primary-blue); }
        .stat-mini .label { font-size: 0.8rem; color: #64748b; font-weight: 600; text-transform: uppercase; }
        
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <!-- Sidebar Identique -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-truck-medical truck"></i>
            <i class="fa-solid fa-plus plus"></i>
            <div>
                <h5 class="mb-0 fw-bold">Secours Bénin</h5>
                <small class="text-white-50">Espace Ambulancier</small>
            </div>
        </div>
        <div class="d-flex flex-column mt-3">
            <a href="/ambulancier/missions" class="nav-link"><i class="fa-solid fa-clipboard-list"></i> Tableau de bord</a>
            <a href="/ambulancier/mission-active" class="nav-link"><i class="fa-solid fa-route"></i> Mission en cours</a>
            <a href="/ambulancier/historique" class="nav-link active"><i class="fa-solid fa-clock-rotate-left"></i> Historique</a>
            <div class="mt-auto">
               <a href="/ambulancier/parametres" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
                <a href="/ambulancier/deconnexion" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h3 class="fw-bold mb-0">Historique des missions</h3>
            </div>
        </div>

        <!-- Mini Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-mini">
            <div class="value">{{ $missions->count() }}</div>
            <div class="label">Total missions</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-mini">
            <div class="value" style="color: var(--success-green);">{{ $missions->count() }}</div>
            <div class="label">Réussies</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-mini">
            <div class="value">
                @php
                    $moyenne = $missions->avg(function($m) {
                        return $m->depart_a && $m->termine_a
                            ? \Carbon\Carbon::parse($m->depart_a)->diffInMinutes($m->termine_a)
                            : 0;
                    });
                @endphp
                {{ $moyenne ? round($moyenne) . ' min' : '-- min' }}
            </div>
            <div class="label">Temps moyen</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-mini">
            <div class="value" style="color: var(--accent-red);">0</div>
            <div class="label">Annulées</div>
        </div>
    </div>
</div>

        <!-- Filtres -->
        <div class="filter-card">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <input type="text" class="form-control" placeholder="Rechercher une mission...">
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>Toutes les périodes</option>
                        <option>Aujourd'hui</option>
                        <option>Cette semaine</option>
                        <option>Ce mois</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select">
                        <option>Tous les statuts</option>
                        <option>Terminée</option>
                        <option>Annulée</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary w-100" style="background: var(--primary-blue); border: none; min-height: 48px;">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Liste Historique -->
@forelse($missions as $mission)
    <div class="history-card">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="fw-bold mb-1">{{ $mission->alerte->description ?? 'Urgence signalée' }}</h6>
                <p class="text-muted small mb-1">
                    <i class="fa-solid fa-location-dot me-1"></i> {{ ucfirst($mission->alerte->commune) }}
                </p>
            </div>
            <span class="badge-status badge-done"><i class="fa-solid fa-check me-1"></i> Terminée</span>
        </div>
        <div class="d-flex justify-content-between align-items-center mt-3 pt-2" style="border-top: 1px solid #f1f5f9;">
            <small class="text-muted">
                <i class="fa-regular fa-calendar me-1"></i> {{ $mission->created_at->format('d M Y • H:i') }}
            </small>
            <small class="fw-bold">
                <i class="fa-regular fa-clock me-1"></i>
                Durée: 
                @if($mission->depart_a && $mission->termine_a)
                    {{ \Carbon\Carbon::parse($mission->depart_a)->diffInMinutes($mission->termine_a) }} min
                @else
                    -- min
                @endif
            </small>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fa-solid fa-clock-rotate-left fa-3x text-muted mb-3"></i>
        <h5 class="fw-bold">Aucune mission terminée</h5>
        <p class="text-muted">Vos missions terminées apparaîtront ici.</p>
    </div>
@endforelse
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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