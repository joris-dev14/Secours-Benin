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
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; --success-green: #10B981; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        
        /* Sidebar Identique au Régulateur */
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; transition: all 0.3s; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .sidebar-header i.truck { font-size: 1.8rem; }
        .sidebar-header i.plus { font-size: 1rem; color: var(--accent-red); margin-left: -12px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; border-radius: 0; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        
        .main-content { margin-left: 260px; padding: 2rem; transition: all 0.3s; }
        
        /* KPI Cards */
        .kpi-card { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 4px solid var(--primary-blue); }
        .kpi-card.success { border-left-color: var(--success-green); }
        .kpi-card.danger { border-left-color: var(--accent-red); }
        .kpi-value { font-size: 2rem; font-weight: 700; color: var(--primary-blue); }
        
        /* Mission Cards */
        .mission-card { background: var(--white); border-radius: 12px; padding: 1.2rem; margin-bottom: 1rem; box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: all 0.3s; cursor: pointer; border: 2px solid transparent; }
        .mission-card:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .mission-card.active-mission { border-color: var(--accent-red); box-shadow: 0 5px 20px rgba(255, 82, 82, 0.2); animation: pulse-red 2s infinite; }
        @keyframes pulse-red { 0% { box-shadow: 0 5px 20px rgba(255, 82, 82, 0.2); } 50% { box-shadow: 0 5px 25px rgba(255, 82, 82, 0.4); } 100% { box-shadow: 0 5px 20px rgba(255, 82, 82, 0.2); } }
        
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .badge-active { background: rgba(255, 82, 82, 0.1); color: var(--accent-red); }
        .badge-done { background: rgba(16, 185, 129, 0.1); color: var(--success-green); }
        
        .status-indicator { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; }
        .status-available { background: rgba(16, 185, 129, 0.1); color: var(--success-green); }
        .status-available::before { content: ''; width: 8px; height: 8px; background: var(--success-green); border-radius: 50%; animation: blink 1.5s infinite; }
        @keyframes blink { 50% { opacity: 0.5; } }
        
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <!-- Sidebar Identique (avec icône Ambulance) -->
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
            <a href="/ambulancier/missions" class="nav-link active"><i class="fa-solid fa-clipboard-list"></i> Tableau de bord</a>
            <a href="/ambulancier/mission-active" class="nav-link"><i class="fa-solid fa-route"></i> Mission en cours</a>
            <a href="/ambulancier/historique" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i> Historique</a>
            <div class="mt-auto">
               <a href="/ambulancier/parametres" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
    <a href="/ambulancier/deconnexion" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>            </div>
        </div>
    </nav>

    <!-- Main Content -->
<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div>
                <h3 class="fw-bold mb-0">Bonjour, {{ session('ambulancier_nom') }} !</h3>
                @php $ambulancier = \App\Models\Ambulancier::find(session('ambulancier_id')); @endphp
                <small class="text-muted">
    {{ $ambulancier->matricule }} • 
    Véhicule : {{ $ambulancier->ambulance->matricule ?? 'Non assigné' }} • 
    {{ $ambulancier->centre ?? '' }}
                    </small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="status-indicator status-available">
                {{ $missionActive ? 'EN MISSION' : 'DISPONIBLE' }}
            </span>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="kpi-card danger">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold text-uppercase">Mission en cours</p>
                        <div class="kpi-value">{{ $missionActive ? 1 : 0 }}</div>
                    </div>
                    <i class="fa-solid fa-route fa-2x text-danger opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card success">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold text-uppercase">Missions aujourd'hui</p>
                        <div class="kpi-value">{{ $missionsTerminees->count() }}</div>
                    </div>
                    <i class="fa-solid fa-check-double fa-2x text-success opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1 small fw-semibold text-uppercase">Temps moyen</p>
                        <div class="kpi-value">
                            @php
                                $moyenne = $missionsTerminees->avg(function($m) {
                                    return $m->depart_a && $m->termine_a
                                        ? \Carbon\Carbon::parse($m->depart_a)->diffInMinutes($m->termine_a)
                                        : 0;
                                });
                            @endphp
                            {{ $moyenne ? round($moyenne) : '--' }}
                            <span class="fs-6 text-muted">min</span>
                        </div>
                    </div>
                    <i class="fa-solid fa-stopwatch fa-2x text-primary opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Mission Active -->
    <h5 class="fw-bold mb-3"><i class="fa-solid fa-bolt text-danger me-2"></i>Mission en cours</h5>
    @if($missionActive)
        <div class="mission-card active-mission" onclick="window.location.href='/ambulancier/mission-active'">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ ucfirst($missionActive->alerte->commune) }}</h5>
                    <p class="text-muted small mb-0">
                        <i class="fa-solid fa-location-dot me-1 text-danger"></i>
                        @if($missionActive->alerte->latitude)
                            GPS: {{ $missionActive->alerte->latitude }}° N, {{ $missionActive->alerte->longitude }}° E
                        @endif
                    </p>
                </div>
                <span class="badge-status badge-active">
                    <i class="fa-solid fa-bolt me-1"></i>
                    {{ strtoupper(str_replace('_', ' ', $missionActive->statut)) }}
                </span>
            </div>
            <div class="row text-center g-2">
                <div class="col-6">
                    <div class="p-2 rounded" style="background: var(--bg-light);">
                        <small class="text-muted d-block">Reçue il y a</small>
                        <strong>{{ $missionActive->created_at->locale('fr')->diffForHumans() }}</strong>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 rounded" style="background: var(--bg-light);">
                        <small class="text-muted d-block">Statut</small>
                        <strong>{{ strtoupper(str_replace('_', ' ', $missionActive->statut)) }}</strong>
                    </div>
                </div>
            </div>
            <button class="btn btn-danger w-100 mt-3 fw-bold" style="min-height: 50px;">
                <i class="fa-solid fa-location-arrow me-2"></i> OUVRIR LA MISSION
            </button>
        </div>
    @else
        <div class="mission-card text-center py-4">
            <i class="fa-solid fa-check-circle fa-3x text-success mb-3"></i>
            <h5 class="fw-bold">Aucune mission en cours</h5>
            <p class="text-muted">Vous êtes disponible pour une nouvelle mission.</p>
        </div>
    @endif

    <!-- Missions terminées -->
    <h5 class="fw-bold mb-3 mt-4">Missions terminées</h5>
    @forelse($missionsTerminees as $mission)
        <div class="mission-card" style="opacity: 0.85;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="fw-bold mb-1">{{ ucfirst($mission->alerte->commune) }}</h6>
                    <p class="text-muted small mb-0">
                        <i class="fa-regular fa-clock me-1"></i>
                        {{ $mission->created_at->locale('fr')->isoFormat('D MMM YYYY • HH:mm') }}
                        @if($mission->depart_a && $mission->termine_a)
                            • Durée: {{ \Carbon\Carbon::parse($mission->depart_a)->diffInMinutes($mission->termine_a) }} min
                        @endif
                    </p>
                </div>
                <span class="badge-status badge-done"><i class="fa-solid fa-check me-1"></i> TERMINÉE</span>
            </div>
        </div>
    @empty
        <div class="mission-card text-center py-4">
            <p class="text-muted">Aucune mission terminée pour le moment.</p>
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