<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        
        /* Sidebar Identique */
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; transition: all 0.3s; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .sidebar-header i.shield { font-size: 1.8rem; }
        .sidebar-header i.plus { font-size: 1rem; color: var(--accent-red); margin-left: -12px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; border-radius: 0; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        
        /* Main Content */
        .main-content { margin-left: 260px; padding: 2rem; transition: all 0.3s; }
        
        /* KPI Cards */
        .kpi-card { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 4px solid var(--primary-blue); }
        .kpi-card.danger { border-left-color: var(--accent-red); }
        .kpi-value { font-size: 2rem; font-weight: 700; color: var(--primary-blue); }
        
        /* Alert List & Animation */
        .alert-item { background: var(--white); border-radius: 12px; padding: 1rem; margin-bottom: 1rem; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: all 0.3s; cursor: pointer; border: 1px solid transparent; }
        .alert-item:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .alert-item.new-alert { border-color: var(--accent-red); animation: pulse-red 1.5s infinite; }
        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(255, 82, 82, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 82, 82, 0); } }
        
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .badge-urgent { background: rgba(255, 82, 82, 0.1); color: var(--accent-red); }
        
        /* Mobile Responsive */
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
            <i class="fa-solid fa-shield-halved shield"></i>
            <i class="fa-solid fa-plus plus"></i>
            <div>
                <h5 class="mb-0 fw-bold">Secours Bénin</h5>
                <small class="text-white-50">Régulation SAMU</small>
            </div>
        </div>
        <div class="d-flex flex-column mt-3">
            <a href="/regulateur/dashboard" class="nav-link active"><i class="fa-solid fa-gauge-high"></i> Tableau de bord</a>
            <a href="/regulateur/dispatch" class="nav-link"><i class="fa-solid fa-map-location-dot"></i> Dispatch & Carte</a>
            <a href="/regulateur/flotte" class="nav-link"><i class="fa-solid fa-truck-medical"></i> Gestion de la flotte</a>
            <a href="/regulateur/statistiques" class="nav-link"><i class="fa-solid fa-chart-line"></i> Statistiques</a>
            <div class="mt-auto">
             <a href="/regulateur/parametres" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
            <a href="/regulateur/deconnexion" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>            </div>
        </div is="nav">
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h3 class="fw-bold mb-0">Tableau de bord temps réel</h3>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success p-2"><i class="fa-solid fa-circle fa-xs me-1"></i> Système opérationnel</span>
            </div>
        </div>

        <!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="kpi-card danger">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Alertes en attente</p>
                    <div class="kpi-value">{{ $alertesEnAttente }}</div>
                </div>
                <i class="fa-solid fa-bell fa-2x text-danger opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Alertes en cours</p>
                    <div class="kpi-value">{{ $alertesActives }}</div>
                </div>
                <i class="fa-solid fa-stopwatch fa-2x text-primary opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Ambulances disponibles</p>
                    <div class="kpi-value">{{ $ambulancesDispos }} <span class="fs-6 text-muted">/ {{ $ambulancesTotal }}</span></div>
                </div>
                <i class="fa-solid fa-truck-medical fa-2x text-success opacity-25"></i>
            </div>
        </div>
    </div>
</div>

       <!-- Alert List -->
<h5 class="fw-bold mb-3">Alertes entrantes</h5>
<div id="alert-list">
    @forelse($alertes as $alerte)
        <div class="alert-item" onclick="window.location.href='/regulateur/dispatch'">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1">{{ ucfirst($alerte->commune) }}</h6>
                    <p class="text-muted small mb-0">
                        <i class="fa-regular fa-clock me-1"></i> {{ $alerte->created_at->locale('fr')->diffForHumans() }}
                        @if($alerte->photo) • Photo jointe @endif
                        @if($alerte->latitude) • GPS: {{ $alerte->latitude }}° N, {{ $alerte->longitude }}° E @endif
                    </p>
                </div>
            </div>
            <span class="badge-status badge-urgent">{{ strtoupper(str_replace('_', ' ', $alerte->statut)) }}</span>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="fa-solid fa-check-circle fa-3x text-success mb-3"></i>
            <h5 class="fw-bold">Aucune alerte</h5>
            <p class="text-muted">Toutes les alertes ont été traitées.</p>
        </div>
    @endforelse
</div>

    <!-- Toast Notification -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast align-items-center text-white bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fw-bold">
                    <i class="fa-solid fa-bell me-2"></i> NOUVELLE ALERTE REÇUE !
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fermer sidebar en cliquant dehors
    document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = e.target.closest('[onclick*="sidebar"]');
    
    if (sidebar && !sidebar.contains(e.target) && !toggleBtn) {
        sidebar.classList.remove('show');
    }
});</script>
@include('partials.pwa-register')
</body>
</html>