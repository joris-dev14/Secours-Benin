<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .sidebar-header i.shield { font-size: 1.8rem; } .sidebar-header i.plus { font-size: 1rem; color: var(--accent-red); margin-left: -12px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        .main-content { margin-left: 260px; padding: 2rem; }
    @media (max-width: 991px) { 
    .sidebar { transform: translateX(-100%); } 
    .sidebar.show { transform: translateX(0); }
    .main-content { margin-left: 0; } 
}        
        .stat-card { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); text-align: center; }
        .stat-value { font-size: 2.2rem; font-weight: 700; color: var(--primary-blue); }
        .chart-container { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); height: 400px; position: relative; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-shield-halved shield"></i><i class="fa-solid fa-plus plus"></i>
            <div><h5 class="mb-0 fw-bold">Secours Bénin</h5><small class="text-white-50">Régulation SAMU</small></div>
        </div>
        <div class="d-flex flex-column mt-3">
            <a href="/regulateur/dashboard" class="nav-link"><i class="fa-solid fa-gauge-high"></i> Tableau de bord</a>
            <a href="/regulateur/dispatch" class="nav-link"><i class="fa-solid fa-map-location-dot"></i> Dispatch & Carte</a>
            <a href="/regulateur/flotte" class="nav-link"><i class="fa-solid fa-truck-medical"></i> Gestion de la flotte</a>
            <a href="/regulateur/statistiques" class="nav-link active"><i class="fa-solid fa-chart-line"></i> Statistiques</a>
            <div class="mt-auto"><a href="/regulateur/parametres" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
    <a href="/regulateur/deconnexion" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h3 class="fw-bold mb-0">Statistiques & Performance</h3>
    </div>
    <a href="/regulateur/export-pdf" class="btn btn-outline-danger">
        <i class="fa-solid fa-file-pdf me-2"></i> Exporter le rapport PDF
    </a>
</div>

       <!-- Résumé KPI -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <p class="text-muted small fw-semibold text-uppercase mb-2">Total Alertes (Mois)</p>
            <div class="stat-value">{{ $totalAlertesMois }}</div>
            <small class="text-muted">Ce mois-ci</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p class="text-muted small fw-semibold text-uppercase mb-2">Taux de traitement</p>
            <div class="stat-value">{{ $tauxTraitement }}%</div>
            <small class="{{ $tauxTraitement >= 90 ? 'text-success' : 'text-danger' }}">
                <i class="fa-solid fa-{{ $tauxTraitement >= 90 ? 'check' : 'triangle-exclamation' }}"></i>
                {{ $tauxTraitement >= 90 ? 'Objectif atteint' : 'Sous objectif' }}
            </small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p class="text-muted small fw-semibold text-uppercase mb-2">Temps moyen d'intervention</p>
            <div class="stat-value">— <span class="fs-6 text-muted">min</span></div>
            <small class="text-muted">Données insuffisantes</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <p class="text-muted small fw-semibold text-uppercase mb-2">Fausses alertes signalées</p>
            <div class="stat-value text-danger">{{ $faussesAlertes }}</div>
            <small class="text-muted">Ce mois-ci</small>
        </div>
    </div>
</div>
        <!-- Graphiques -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="chart-container">
                    <h6 class="fw-bold mb-3">Évolution des alertes par jour (30 derniers jours)</h6>
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-container">
                    <h6 class="fw-bold mb-3">Répartition par commune</h6>
                    <canvas id="doughnutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique lignes - alertes par jour
    const ctxLine = document.getElementById('lineChart').getContext('2d');
    new Chart(ctxLine, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: "Nombre d'alertes",
                data: {!! json_encode($data) !!},
                borderColor: '#0A2540',
                backgroundColor: 'rgba(10, 37, 64, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });

    // Graphique donut - répartition par commune
    const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($communeLabels) !!},
            datasets: [{
                data: {!! json_encode($communeData) !!},
                backgroundColor: ['#0A2540', '#FF5252', '#10B981', '#CBD5E1'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%'
        }
    });
</script>
<script>
        // Fermer sidebar en cliquant dehors
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