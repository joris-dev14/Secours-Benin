<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; --success-green: #10B981; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .sidebar-header i.building { font-size: 1.8rem; }
        .sidebar-header i.plus { font-size: 1rem; color: var(--accent-red); margin-left: -12px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        
        .main-content { margin-left: 260px; padding: 2rem; }
        
        .kpi-card { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 4px solid var(--primary-blue); transition: transform 0.2s; }
        .kpi-card:hover { transform: translateY(-3px); }
        .kpi-card.danger { border-left-color: var(--accent-red); }
        .kpi-card.success { border-left-color: var(--success-green); }
        .kpi-value { font-size: 2.2rem; font-weight: 700; color: var(--primary-blue); }
        .kpi-trend { font-size: 0.85rem; font-weight: 600; }
        
        .chart-card { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); height: 100%; }
        .chart-container { position: relative; height: 300px; }
        
        .table-custom { background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .table-custom thead { background: var(--primary-blue); color: var(--white); }
        .table-custom th { font-weight: 600; border: none; padding: 1rem; }
        .table-custom td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        
        .performance-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
        .perf-excellent { background: rgba(16, 185, 129, 0.1); color: var(--success-green); }
        .perf-good { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .perf-average { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <!-- Sidebar Identique (Admin) -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-user-shield building"></i>
            <i class="fa-solid fa-plus plus"></i>
            <div>
                <h5 class="mb-0 fw-bold">Secours Bénin</h5>
                <small class="text-white-50">Direction SAMU</small>
            </div>
        </div>
        <div class="d-flex flex-column mt-3">
            <a href="/admin/dashboard" class="nav-link active"><i class="fa-solid fa-chart-pie"></i> Tableau de bord</a>
            <a href="/admin/utilisateurs" class="nav-link"><i class="fa-solid fa-users-gear"></i> Utilisateurs</a>
            <a href="/admin/territoire" class="nav-link"><i class="fa-solid fa-map-location-dot"></i> Territoire</a>
            <a href="/admin/moderation" class="nav-link"><i class="fa-solid fa-shield-halved"></i> Modération</a>
            <a href="/admin/rapports" class="nav-link"><i class="fa-solid fa-file-lines"></i> Rapports</a>
            <div class="mt-auto">
                <a href="/admin/login" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h3 class="fw-bold mb-0">Tableau de bord général</h3>
                    <small class="text-muted">Vue d'ensemble nationale • Mise à jour en temps réel</small>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#exportModal">
                    <i class="fa-solid fa-download me-2"></i> Exporter
                </button>
                <button class="btn btn-danger" style="background: var(--accent-red); border: none;" onclick="window.location.href='/admin/export-pdf'">
                    <i class="fa-solid fa-file-pdf me-2"></i> Rapport PDF
                </button>
            </div>
        </div>
        <!-- KPI Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="kpi-card danger">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Alertes ce mois</p>
                    <div class="kpi-value">{{ $totalAlertesMois }}</div>
                </div>
                <i class="fa-solid fa-bell fa-2x text-danger opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card success">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Taux de traitement</p>
                    <div class="kpi-value">{{ $tauxTraitement }}%</div>
                </div>
                <i class="fa-solid fa-check-double fa-2x text-success opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Temps moyen</p>
                    <div class="kpi-value">{{ $tempsMoyen }} <span class="fs-6 text-muted">min</span></div>
                </div>
                <i class="fa-solid fa-stopwatch fa-2x text-primary opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="kpi-card">
            <div class="d-flex justify-content-between">
                <div>
                    <p class="text-muted mb-1 small fw-semibold text-uppercase">Flotte active</p>
                    <div class="kpi-value">{{ $ambulancesDispo }} <span class="fs-6 text-muted">/ {{ $ambulancesTotal }}</span></div>
                    <span class="kpi-trend text-muted">{{ $ambulancesMaint }} en maintenance</span>
                </div>
                <i class="fa-solid fa-truck-medical fa-2x text-primary opacity-25"></i>
            </div>
        </div>
    </div>
</div>
        <!-- Graphiques -->
        <div class="row g-4 mb-4">
            <div class="col-lg-8">
                <div class="chart-card">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0">Évolution des alertes (12 derniers mois)</h6>
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>2025-2026</option>
                            <option>2024-2025</option>
                        </select>
                    </div>
                    <div class="chart-container">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="chart-card">
                    <h6 class="fw-bold mb-3">Répartition par commune</h6>
                    <div class="chart-container">
                        <canvas id="doughnutChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="row g-4">
            <div class="col-lg-7">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">Performance des centres de régulation</h6>
        <div class="table-responsive">
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Centre</th>
                        <th>Alertes</th>
                        <th>Temps moyen</th>
                        <th>Taux</th>
                        <th>Performance</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($performanceCentres as $centre)
                    <tr>
                        <td class="fw-bold">{{ $centre->centre ?? 'Non défini' }}</td>
                        <td>{{ $centre->total_alertes }}</td>
                        <td>{{ round($centre->temps_moyen, 1) }} min</td>
                        <td>{{ round($centre->taux, 1) }}%</td>
                        <td>
                            @if($centre->taux >= 95)
                                <span class="performance-badge perf-excellent">Excellent</span>
                            @elseif($centre->taux >= 85)
                                <span class="performance-badge perf-good">Bon</span>
                            @else
                                <span class="performance-badge perf-average">Moyen</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">Aucune donnée disponible</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
           <div class="col-lg-5">
    <div class="chart-card">
        <h6 class="fw-bold mb-3">Top 5 Ambulanciers du mois</h6>
        <div class="d-flex flex-column gap-2">
          @forelse($topAmbulanciers as $index => $top)
<div class="d-flex justify-content-between align-items-center p-2 rounded" style="background: var(--bg-light);">
    <div class="d-flex align-items-center gap-2">
        <span class="badge {{ $index == 0 ? 'bg-warning text-dark' : ($index == 1 ? 'bg-secondary' : 'bg-light text-dark') }}">
            {{ $index + 1 }}
        </span>
        <strong>{{ $top['nom'] }} ({{ $top['matricule'] }})</strong>
    </div>
    <span class="text-success fw-bold">{{ $top['total_missions'] }} missions</span>
</div>
@empty
<p class="text-muted text-center py-3">Aucune mission ce mois</p>
@endforelse
        </div>
    </div>
</div>
        </div>
    </div>

    <!-- Modal Export -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Exporter les données</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex flex-column gap-3">
                <a href="/admin/export-csv" class="btn btn-outline-success fw-bold" style="min-height: 50px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fa-solid fa-file-csv fa-lg"></i> Exporter en CSV
                </a>
                <a href="/admin/export-excel" class="btn btn-outline-primary fw-bold" style="min-height: 50px; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="fa-solid fa-file-excel fa-lg"></i> Exporter en Excel
                </a>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary w-100" data-bs-dismiss="modal">Annuler</button>
            </div>
        </div>
    </div>
</div>

   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Graphique Linéaire — données réelles
    const moisLabels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'];
    const alertesData = Array(12).fill(0);
    @foreach($alertesParMois as $item)
        alertesData[{{ $item->mois - 1 }}] = {{ $item->total }};
    @endforeach

    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: moisLabels,
            datasets: [{
                label: 'Alertes',
                data: alertesData,
                borderColor: '#0A2540',
                backgroundColor: 'rgba(10, 37, 64, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    // Graphique Camembert — données réelles
    new Chart(document.getElementById('doughnutChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($parCommune->pluck('commune')) !!},
            datasets: [{
                data: {!! json_encode($parCommune->pluck('total')) !!},
                backgroundColor: ['#0A2540', '#FF5252', '#10B981', '#CBD5E1', '#f59e0b'],
                borderWidth: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, cutout: '65%' }
    });
</script>

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