<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modération - Secours Bénin</title>
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
        
        .alert-card { background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 1.5rem; border-left: 4px solid var(--accent-red); }
        .alert-photo { height: 250px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-size: 3rem; position: relative; }
        .alert-photo .badge-warning { position: absolute; top: 10px; right: 10px; background: var(--accent-red); color: white; padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 700; }
        .alert-body { padding: 1.5rem; }
        .meta-row { display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
        .meta-row:last-child { border-bottom: none; }
        .meta-label { color: #64748b; font-weight: 600; }
        .meta-value { font-weight: 700; color: var(--primary-blue); }
        
        .btn-block-user { background: var(--accent-red); color: white; border: none; min-height: 50px; border-radius: 12px; font-weight: 700; width: 100%; transition: all 0.2s; }
        .btn-block-user:hover { background: #ff3333; transform: translateY(-2px); }
        .btn-dismiss { background: var(--bg-light); color: var(--primary-blue); border: 2px solid #e2e8f0; min-height: 50px; border-radius: 12px; font-weight: 700; width: 100%; }
        
        .stats-card { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); text-align: center; }
        
@media (max-width: 991px) { 
    .sidebar { transform: translateX(-100%); } 
    .sidebar.show { transform: translateX(0); }
    .main-content { margin-left: 0; } 
}  
   </style>
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
            <a href="/admin/moderation" class="nav-link active"><i class="fa-solid fa-shield-halved"></i> Modération</a>
            <a href="/admin/rapports" class="nav-link"><i class="fa-solid fa-file-lines"></i> Rapports</a>
            <div class="mt-auto"><a href="/admin/login" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')"><i class="fa-solid fa-bars"></i></button>
                <h3 class="fw-bold mb-0">Modération des alertes</h3>
            </div>
            <div class="d-flex gap-2">
    <select class="form-select" style="width: auto;" onchange="window.location.href='/admin/moderation?commune=' + this.value">
        <option value="">Toutes les communes</option>
        @foreach($communes as $commune)
            <option value="{{ $commune }}" {{ $communeFiltre == $commune ? 'selected' : '' }}>
                {{ ucfirst($commune) }}
            </option>
        @endforeach
    </select>
</div>
        </div>

        <!-- Stats -->
        <div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stats-card">
            <h3 class="fw-bold mb-1" style="color: var(--accent-red);">{{ $totalMois }}</h3>
            <small class="text-muted">Alertes signalées (mois)</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3 class="fw-bold mb-1" style="color: #f59e0b;">{{ $enAttente }}</h3>
            <small class="text-muted">En attente de validation</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3 class="fw-bold mb-1" style="color: var(--success-green);">{{ $faussesMois }}</h3>
            <small class="text-muted">Fausses alertes confirmées</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stats-card">
            <h3 class="fw-bold mb-1">{{ $comptesBloques }}</h3>
            <small class="text-muted">Comptes bloqués</small>
        </div>
    </div>
        </div>

        @forelse($signalements as $signalement)
    <div class="alert-card" id="signalement-{{ $signalement->id }}" style="{{ $signalement->statut != 'en_attente' ? 'opacity: 0.6;' : '' }}">
        <div class="row g-0">
            <div class="col-md-4">
                <div class="alert-photo">
                    @if($signalement->alerte && $signalement->alerte->photo)
                        <img src="{{ asset('storage/' . $signalement->alerte->photo) }}" style="width:100%; height:100%; object-fit:cover;" alt="Photo">
                    @else
                        <i class="fa-solid fa-image"></i>
                    @endif
                    <span class="badge-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i> SIGNALÉE</span>
                </div>
            </div>
            <div class="col-md-8">
                <div class="alert-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Alerte #A-{{ $signalement->alerte_id }}</h5>
                            <small class="text-muted">
                                Signalée par {{ $signalement->regulateur->nom ?? 'régulateur' }} {{ $signalement->regulateur->prenom ?? '' }}
                                ({{ $signalement->regulateur->centre ?? '' }})
                            </small>
                        </div>
                        @if($signalement->statut == 'en_attente')
                            <span class="badge bg-danger bg-opacity-10 text-danger p-2">À TRAITER</span>
                        @elseif($signalement->statut == 'classe')
                            <span class="badge bg-success bg-opacity-10 text-success p-2">CLASSÉE</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary p-2">TRAITÉE</span>
                        @endif
                    </div>

                    <div class="meta-row">
                        <span class="meta-label"><i class="fa-solid fa-user me-2"></i>Citoyen</span>
                        <span class="meta-value">+229 {{ $signalement->alerte->citoyen->telephone ?? '—' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label"><i class="fa-solid fa-location-dot me-2"></i>Localisation</span>
                        <span class="meta-value">
                            {{ ucfirst($signalement->alerte->commune ?? '—') }}
                            @if($signalement->alerte->latitude)
                                ({{ $signalement->alerte->latitude }}° N, {{ $signalement->alerte->longitude }}° E)
                            @endif
                        </span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label"><i class="fa-regular fa-clock me-2"></i>Date & heure</span>
                        <span class="meta-value">{{ $signalement->created_at->locale('fr')->isoFormat('D MMMM YYYY [à] HH:mm') }}</span>
                    </div>
                    @if($signalement->alerte->description)
                        <div class="meta-row">
                            <span class="meta-label"><i class="fa-solid fa-comment me-2"></i>Description</span>
                            <span class="meta-value">"{{ $signalement->alerte->description }}"</span>
                        </div>
                    @endif
                    <div class="meta-row">
                        <span class="meta-label"><i class="fa-solid fa-flag me-2"></i>Motif du signalement</span>
                        <span class="meta-value text-danger fw-bold">{{ $signalement->motif }}</span>
                    </div>

                    @if($signalement->statut == 'en_attente')
                        <div class="row g-2 mt-3">
                            <div class="col-md-6">
                                <button class="btn btn-dismiss" onclick="classerSignalement({{ $signalement->id }})">
                                    <i class="fa-solid fa-check me-2"></i> Alerte légitime (classer)
                                </button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-block-user" onclick="bloquerCitoyen({{ $signalement->id }})">
                                    <i class="fa-solid fa-ban me-2"></i> Bloquer le compte citoyen
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fa-solid fa-shield-halved fa-3x text-success mb-3"></i>
        <h5 class="fw-bold">Aucun signalement</h5>
        <p class="text-muted">Toutes les alertes sont en règle.</p>
    </div>
@endforelse
    </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const csrfToken = '{{ csrf_token() }}';

    function classerSignalement(id) {
        if (!confirm("Classer cette alerte comme légitime ?")) return;

        fetch(`/signalement/${id}/traiter`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ statut: 'classe' })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }

    function bloquerCitoyen(id) {
        if (!confirm("Êtes-vous sûr de vouloir bloquer définitivement ce compte citoyen ? Cette action est irréversible.")) return;

        fetch(`/signalement/${id}/bloquer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("Compte bloqué avec succès. Le citoyen ne peut plus accéder à l'application.");
                location.reload();
            }
        });
    }

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