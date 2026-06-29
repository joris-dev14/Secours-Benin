<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mission Active - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
        
        .info-card { background: var(--white); border-radius: 16px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .address-text { font-size: 1.3rem; font-weight: 700; line-height: 1.4; color: var(--primary-blue); }
        
        .btn-itinerary { background: var(--primary-blue); color: white; border: none; min-height: 60px; border-radius: 14px; font-weight: 700; font-size: 1.1rem; width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; text-decoration: none; transition: all 0.2s; }
        .btn-itinerary:hover { background: #0d3254; color: white; transform: translateY(-2px); }
        
        .photo-container { position: relative; border-radius: 12px; overflow: hidden; height: 220px; background: #e2e8f0; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .photo-container i { font-size: 3rem; color: #94a3b8; }
        .photo-overlay { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(10, 37, 64, 0.8); color: white; padding: 10px; text-align: center; font-weight: 600; font-size: 0.9rem; }
        
        /* Boutons d'action géants */
        .action-section { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .btn-action { min-height: 70px; border-radius: 14px; font-weight: 800; font-size: 1.2rem; width: 100%; border: none; transition: all 0.3s; display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 0.8rem; }
        .btn-action:active:not(:disabled) { transform: scale(0.98); }
        
        .btn-depart { background: var(--primary-blue); color: white; }
        .btn-depart:hover:not(:disabled) { background: #0d3254; color: white; }
        .btn-arrive { background: var(--accent-red); color: white; }
        .btn-arrive:hover:not(:disabled) { background: #ff3333; color: white; }
        .btn-finish { background: var(--success-green); color: white; }
        .btn-finish:hover:not(:disabled) { background: #059669; color: white; }
        .btn-disabled { background: #e2e8f0 !important; color: #94a3b8 !important; cursor: not-allowed; }
        
        .step-indicator { display: flex; justify-content: space-between; margin-bottom: 1.5rem; padding: 0 10px; }
        .step { font-size: 0.85rem; font-weight: 700; color: #cbd5e1; text-transform: uppercase; text-align: center; flex: 1; position: relative; }
        .step.active { color: var(--primary-blue); }
        .step.completed { color: var(--success-green); }
        .step:not(:last-child)::after { content: ''; position: absolute; top: 50%; right: -50%; width: 100%; height: 2px; background: #e2e8f0; z-index: -1; }
        .step.completed:not(:last-child)::after { background: var(--success-green); }
        
        .map-container { height: 300px; border-radius: 12px; overflow: hidden; margin-bottom: 1rem; }
        
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
            <a href="/ambulancier/mission-active" class="nav-link active"><i class="fa-solid fa-route"></i> Mission en cours</a>
            <a href="/ambulancier/historique" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i> Historique</a>
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
                <h3 class="fw-bold mb-0">Mission en cours</h3>
            </div>
            <span class="badge bg-danger bg-opacity-10 text-danger p-2 fw-bold">
                <i class="fa-solid fa-bolt me-1"></i> URGENCE
            </span>
        </div>

        @if($mission)
        <div class="row g-4">
            <!-- Colonne Gauche : Carte + Actions -->
            <div class="col-lg-7">
                <div class="info-card">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">
                        <i class="fa-solid fa-location-dot me-1 text-danger"></i> Destination
                    </h6>
                    <p class="address-text mb-3">
                        {{ ucfirst($mission->alerte->commune) }}
                        @if($mission->alerte->latitude)
                            <br><small class="text-muted fw-normal">GPS: {{ $mission->alerte->latitude }}° N, {{ $mission->alerte->longitude }}° E</small>
                        @endif
                    </p>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $mission->alerte->latitude }},{{ $mission->alerte->longitude }}" target="_blank" class="btn-itinerary">
                        <i class="fa-solid fa-diamond-turn-right fa-lg"></i> Ouvrir l'itinéraire GPS
                    </a>
                </div>

                <div class="info-card">
                    <h6 class="text-muted fw-bold text-uppercase small mb-2">
                        <i class="fa-solid fa-map me-1"></i> Carte de localisation
                    </h6>
                    <div id="map" class="map-container"></div>
                </div>

                <!-- Actions -->
                <div class="action-section">
                    <h6 class="fw-bold mb-3">Actions de la mission</h6>
                    <div class="step-indicator">
                        <span class="step {{ in_array($mission->statut, ['en_route', 'sur_place', 'terminee']) ? 'completed' : 'active' }}" id="step1-text">
                            <i class="fa-solid fa-play d-block mb-1"></i> Départ
                        </span>
                        <span class="step {{ $mission->statut == 'sur_place' ? 'active' : ($mission->statut == 'terminee' ? 'completed' : '') }}" id="step2-text">
                            <i class="fa-solid fa-location-crosshairs d-block mb-1"></i> Sur place
                        </span>
                        <span class="step {{ $mission->statut == 'terminee' ? 'completed' : '' }}" id="step3-text">
                            <i class="fa-solid fa-check-circle d-block mb-1"></i> Terminé
                        </span>
                    </div>

                    <button class="btn btn-action {{ $mission->statut == 'assignee' ? 'btn-depart' : 'btn-disabled' }}"
                        id="btnDepart"
                        onclick="nextStep(1, {{ $mission->id }})"
                        {{ $mission->statut != 'assignee' ? 'disabled' : '' }}>
                        <i class="fa-solid fa-play fa-lg"></i>
                        {{ $mission->statut == 'assignee' ? 'DÉPART DU CENTRE' : 'EN ROUTE' }}
                    </button>

                    <button class="btn btn-action {{ $mission->statut == 'en_route' ? 'btn-arrive' : 'btn-disabled' }}"
                        id="btnArrive"
                        onclick="nextStep(2, {{ $mission->id }})"
                        {{ $mission->statut != 'en_route' ? 'disabled' : '' }}>
                        <i class="fa-solid fa-location-crosshairs fa-lg"></i> ARRIVÉ SUR LES LIEUX
                    </button>

                    <button class="btn btn-action {{ $mission->statut == 'sur_place' ? 'btn-finish' : 'btn-disabled' }}"
                        id="btnFinish"
                        onclick="nextStep(3, {{ $mission->id }})"
                        {{ $mission->statut != 'sur_place' ? 'disabled' : '' }}>
                        <i class="fa-solid fa-check-circle fa-lg"></i> MISSION TERMINÉE
                    </button>
                </div>
            </div>

            <!-- Colonne Droite : Détails -->
            <div class="col-lg-5">
                <div class="info-card">
                    <span class="badge bg-danger bg-opacity-10 text-danger mb-2 fw-bold">{{ strtoupper($mission->alerte->commune) }}</span>
                    <h6 class="fw-bold mb-2">Détails de l'intervention</h6>
                    <p class="small text-muted mb-2"><i class="fa-regular fa-clock me-1"></i> Reçue {{ $mission->created_at->diffForHumans() }}</p>
                    @if($mission->alerte->latitude)
                        <p class="small text-muted mb-2"><i class="fa-solid fa-location-dot me-1"></i> GPS: {{ $mission->alerte->latitude }}° N, {{ $mission->alerte->longitude }}° E</p>
                    @endif
                    @if($mission->alerte->citoyen)
                        <p class="small text-muted mb-0"><i class="fa-solid fa-user me-1"></i> Signalé par: +229 {{ $mission->alerte->citoyen->telephone }}</p>
                    @endif
                </div>

                <div class="info-card">
                    <h6 class="fw-bold mb-2"><i class="fa-solid fa-camera me-1"></i> Photo de la scène</h6>
                    <div class="photo-container" data-bs-toggle="modal" data-bs-target="#photoModal">
                        @if($mission->alerte->photo)
                            <img src="{{ asset('storage/' . $mission->alerte->photo) }}" class="img-fluid" alt="Photo scène">
                        @else
                            <i class="fa-solid fa-image"></i>
                        @endif
                        <div class="photo-overlay"><i class="fa-solid fa-magnifying-glass-plus me-2"></i>Appuyer pour agrandir</div>
                    </div>
                    @if($mission->alerte->description)
                        <p class="mt-2 small text-muted fw-semibold mb-0">
                            <i class="fa-solid fa-circle-info me-1"></i> "{{ $mission->alerte->description }}"
                        </p>
                    @endif
                </div>

                <div class="info-card">
                    <h6 class="fw-bold mb-2"><i class="fa-solid fa-circle-info me-1"></i> Instructions</h6>
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-1">Respectez le code de la route</li>
                        <li class="mb-1">Contactez le régulateur en cas de problème</li>
                        <li>Mettez à jour votre statut à chaque étape</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Modal Photo -->
        <div class="modal fade" id="photoModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-dark border-0">
                    <div class="modal-body p-0 text-center">
                        @if($mission->alerte->photo)
                            <img src="{{ asset('storage/' . $mission->alerte->photo) }}" class="img-fluid" alt="Photo scène">
                        @else
                            <div style="height: 70vh; display: flex; align-items: center; justify-content: center; color: white;">
                                <i class="fa-solid fa-image fa-5x opacity-50"></i>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer border-0 justify-content-center">
                        <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        @else
        <div class="text-center py-5">
            <i class="fa-solid fa-check-circle fa-4x text-success mb-3"></i>
            <h4 class="fw-bold">Aucune mission en cours</h4>
            <p class="text-muted">Vous n'avez pas de mission active pour le moment.</p>
            <a href="/ambulancier/missions" class="btn btn-outline-secondary">Retour au tableau de bord</a>
        </div>
        @endif
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        @if($mission && $mission->alerte->latitude)
        const map = L.map('map').setView([{{ $mission->alerte->latitude }}, {{ $mission->alerte->longitude }}], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(map);
        L.marker([{{ $mission->alerte->latitude }}, {{ $mission->alerte->longitude }}])
            .addTo(map).bindPopup("Lieu de l'incident").openPopup();
        @endif

        const csrfToken = '{{ csrf_token() }}';

        async function nextStep(step, missionId) {
            const statuts = { 1: 'en_route', 2: 'sur_place', 3: 'terminee' };

            if (step === 3) {
                if (!confirm("Confirmer la fin de la mission ? L'horodatage sera enregistré.")) return;
            }

            const response = await fetch(`/ambulancier/mission/${missionId}/statut`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ statut: statuts[step] })
            });

            const data = await response.json();

            if (data.success) {
                if (step === 3) {
                    window.location.href = '/ambulancier/missions';
                } else {
                    window.location.reload();
                }
            }
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