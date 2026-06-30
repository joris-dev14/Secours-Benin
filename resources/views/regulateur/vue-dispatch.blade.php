<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispatch - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .sidebar-header i.shield { font-size: 1.8rem; }
        .sidebar-header i.plus { font-size: 1rem; color: var(--accent-red); margin-left: -12px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        .main-content { margin-left: 260px; padding: 2rem; }
        @media (max-width: 991px) { 
        .sidebar { transform: translateX(-100%); } 
        .sidebar.show { transform: translateX(0); }
        .main-content { margin-left: 0; } 
        }        
        .map-container { height: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .ambulance-card { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; cursor: pointer; transition: all 0.2s; }
        .ambulance-card:hover { border-color: var(--primary-blue); background: rgba(10, 37, 64, 0.02); }
        .ambulance-card.selected { border-color: var(--accent-red); background: rgba(255, 82, 82, 0.05); }
        .btn-dispatch { background-color: var(--accent-red); color: white; border: none; min-height: 56px; border-radius: 12px; font-weight: 700; font-size: 1.1rem; width: 100%; transition: all 0.3s; }
        .btn-dispatch:hover { background-color: #ff3333; transform: translateY(-2px); }
        .scene-photo { width: 100%; height: 260px; border-radius: 12px; background: #e2e8f0; overflow: hidden; position: relative; }
        .scene-photo img.alert-photo { width: 100%; height: 100%; object-fit: contain; max-height: 100%; display: block; }
        .alerte-card { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; cursor: pointer; background: #fff; transition: all 0.2s; }
        .alerte-card:hover { border-color: var(--primary-blue); background: rgba(10, 37, 64, 0.04); }
        .alerte-card.selected { border-color: var(--accent-red); background: rgba(255, 82, 82, 0.08); }
        .alert-detail { display: none; }
        .alert-detail.active { display: block; }
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
            <a href="/regulateur/dispatch" class="nav-link active"><i class="fa-solid fa-map-location-dot"></i> Dispatch & Carte</a>
            <a href="/regulateur/flotte" class="nav-link"><i class="fa-solid fa-truck-medical"></i> Gestion de la flotte</a>
            <a href="/regulateur/statistiques" class="nav-link"><i class="fa-solid fa-chart-line"></i> Statistiques</a>
            <div class="mt-auto"><a href="/regulateur/parametres" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
    <a href="/regulateur/deconnexion" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')"><i class="fa-solid fa-bars"></i></button>            <h3 class="fw-bold mb-0">Détail de l'alerte & Dispatch</h3>
            <a href="/regulateur/dashboard" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i> Retour</a>
        </div>

        <div class="row g-4">
            <!-- Colonne Carte -->
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-0">
                        <div id="map" class="map-container"></div>
                    </div>
                </div>
            </div>

            <!-- Colonne Détails -->
            <div class="col-lg-5">
                @if($alertes->count() > 0)
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm rounded-4 mb-4">
                            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Alertes en attente</h6>
                        <div class="d-flex flex-column gap-3">
                            @foreach($alertes as $alerteItem)
                                <div class="alerte-card {{ $loop->first ? 'selected' : '' }}" onclick="selectAlerte(this, {{ $alerteItem->id }})" data-alert-id="{{ $alerteItem->id }}">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold mb-1">Alerte #{{ $alerteItem->id }}</h6>
                                            <p class="small text-muted mb-1">{{ $alerteItem->description ?? 'Urgence signalée' }}</p>
                                            <p class="small text-muted mb-0">
                                                <i class="fa-solid fa-location-dot me-1 text-danger"></i>
                                                {{ ucfirst($alerteItem->commune) }}
                                                @if($alerteItem->latitude) ({{ $alerteItem->latitude }}° N, {{ $alerteItem->longitude }}° E) @endif
                                            </p>
                                        </div>
                                        <span class="badge bg-danger bg-opacity-10 text-danger">EN ATTENTE</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <form method="POST" action="/regulateur/dispatcher">
                        @csrf
                        <input type="hidden" id="alerte_id" name="alerte_id" value="{{ $alertes->first()->id }}">
                        <input type="hidden" id="ambulancier_id" name="ambulancier_id" value="{{ $availableAmbulanciers->first()->id ?? '' }}">

                        @foreach($alertes as $alerte)
                            <div class="card border-0 shadow-sm mb-4 alert-detail {{ $loop->first ? 'active' : '' }}" data-alert-id="{{ $alerte->id }}">
                                <div class="card-body">
                                    <span class="badge bg-danger bg-opacity-10 text-danger mb-2">URGENCE - NON ASSIGNÉE</span>
                                    <h4 class="fw-bold">{{ $alerte->description ?? 'Urgence signalée' }}</h4>
                                    <p class="text-muted mb-3">
                                        <i class="fa-solid fa-location-dot me-2 text-danger"></i>
                                        {{ ucfirst($alerte->commune) }}
                                        @if($alerte->latitude) ({{ $alerte->latitude }}° N, {{ $alerte->longitude }}° E) @endif
                                    </p>

                                    <h6 class="fw-semibold mb-2">Photo de la scène</h6>
                                    <div class="scene-photo mb-3 d-flex align-items-center justify-content-center text-muted">
                                        @if($alerte->photo)
                                            <img src="{{ asset('storage/' . $alerte->photo) }}" class="alert-photo rounded-3" alt="Photo scène">
                                        @else
                                            <i class="fa-solid fa-image fa-3x"></i>
                                        @endif
                                    </div>

                                    <p class="small text-muted mb-4">
                                        <i class="fa-regular fa-clock me-1"></i> Reçu {{ $alerte->created_at->diffForHumans() }}
                                    </p>

                                    <div class="d-grid gap-2 mb-4">
                                        @if($availableAmbulanciers->count() > 0)
                                            <button type="button" class="btn btn-dispatch" onclick="toggleAmbulanciers()">
                                                <i class="fa-solid fa-paper-plane me-2"></i> DISPATCHER UN AMBULANCIER
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-secondary" disabled>
                                                <i class="fa-solid fa-user-slash me-2"></i> Aucun ambulancier disponible
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div id="ambulancierList" class="d-none">
                            <h6 class="fw-bold mb-3">Ambulanciers disponibles à proximité</h6>
                            <div class="d-flex flex-column gap-2 mb-4">
                                @forelse($availableAmbulanciers as $ambulancier)
                                    <div class="ambulance-card {{ $loop->first ? 'selected' : '' }}" onclick="selectAmbulancier(this, {{ $ambulancier->id }})">
                                        <input type="radio" name="ambulancier_radio" value="{{ $ambulancier->id }}" class="d-none" {{ $loop->first ? 'checked' : '' }}>
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <h6 class="fw-bold mb-1">{{ $ambulancier->nom }} {{ $ambulancier->prenom }}</h6>
                                                <p class="small text-muted mb-1">
                                                    <i class="fa-solid fa-ambulance me-1"></i> {{ $ambulancier->ambulance->matricule ?? 'Ambulance non assignée' }}
                                                </p>
                                                <p class="small text-muted mb-0">
                                                    <i class="fa-solid fa-location-arrow me-1"></i> {{ $ambulancier->ambulance->centre ?? $ambulancier->centre }} • {{ $ambulancier->distance_label ?? 'Position non disponible' }}
                                                </p>
                                            </div>
                                            <span class="badge bg-success bg-opacity-10 text-success">DISPONIBLE</span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-muted">Aucun ambulancier disponible pour le moment.</p>
                                @endforelse
                            </div>

                            @if($availableAmbulanciers->count() > 0)
                                <button type="submit" class="btn btn-dispatch" onclick="syncSelectedAmbulancier()">
                                    <i class="fa-solid fa-paper-plane me-2"></i> DISPATCHER L'AMBULANCIER
                                </button>
                            @endif
                        </div>
                    </form>
                @else
                    @if(session('success'))
                        <div class="alert alert-success shadow-sm rounded-4 mb-4">
                            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fa-solid fa-check-circle fa-3x text-success mb-3"></i>
                            <h5 class="fw-bold">Aucune alerte en attente</h5>
                            <p class="text-muted">Toutes les alertes ont été prises en charge.</p>
                            <a href="/regulateur/dashboard" class="btn btn-outline-secondary">Retour au tableau de bord</a>
                        </div>
                    </div>
                @endif
            </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Fermer sidebar en cliquant dehors
document.addEventListener('click', function(e) {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = e.target.closest('[onclick*="sidebar"]');
    
    if (sidebar && !sidebar.contains(e.target) && !toggleBtn) {
        sidebar.classList.remove('show');
    }
});
    const map = L.map('map').setView([6.3654, 2.4183], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(map);

    @foreach($alertes as $alerte)
        L.marker([{{ $alerte->latitude ?? 6.3654 }}, {{ $alerte->longitude ?? 2.4183 }}])
            .addTo(map).bindPopup("Alerte #{{ $alerte->id }}").openPopup();
    @endforeach

    @foreach($availableAmbulanciers as $ambulancier)
        @if($ambulancier->ambulance && $ambulancier->ambulance->latitude && $ambulancier->ambulance->longitude)
            L.marker([{{ $ambulancier->ambulance->latitude }}, {{ $ambulancier->ambulance->longitude }}], {
                icon: L.divIcon({className: 'custom-div-icon', html: "<div style='background-color:#0A2540; width:15px; height:15px; border-radius:50%; border:2px solid white;'></div>"})
            }).addTo(map).bindPopup("{{ $ambulancier->ambulance->matricule }} - {{ addslashes($ambulancier->nom . ' ' . $ambulancier->prenom) }}");
        @endif
    @endforeach

    function toggleAmbulanciers() {
        const list = document.getElementById('ambulancierList');
        list.classList.toggle('d-none');
        if (!list.classList.contains('d-none')) {
            list.scrollIntoView({ behavior: 'smooth' });
        }
    }

    function selectAmbulancier(el, id) {
        document.querySelectorAll('.ambulance-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        el.querySelector('input[type=radio]').checked = true;
        document.getElementById('ambulancier_id').value = id;
    }

    function selectAlerte(el, id) {
        document.querySelectorAll('.alerte-card').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('alerte_id').value = id;
        document.querySelectorAll('.alert-detail').forEach(detail => {
            detail.classList.toggle('active', detail.dataset.alertId == id);
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function syncSelectedAmbulancier() {
        const checked = document.querySelector('input[name="ambulancier_radio"]:checked');
        if (checked) {
            document.getElementById('ambulancier_id').value = checked.value;
        }
    }
</script>
@include('partials.pwa-register')
</body>
</html>