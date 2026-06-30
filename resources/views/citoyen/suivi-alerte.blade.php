<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi de l'alerte - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); height: 100vh; display: flex; flex-direction: column; }
        .map-container { flex: 1; min-height: 40vh; border-radius: 0 0 24px 24px; overflow: hidden; position: relative; z-index: 1; }
        .tracking-card { background: white; border-radius: 24px 24px 0 0; margin-top: -20px; position: relative; z-index: 2; padding: 1.5rem; flex: 1; box-shadow: 0 -5px 20px rgba(0,0,0,0.05); }
        
        /* Timeline CSS */
        .timeline { position: relative; padding-left: 30px; margin-top: 1rem; }
        .timeline::before { content: ''; position: absolute; left: 11px; top: 5px; bottom: 5px; width: 2px; background: #e2e8f0; }
        .timeline-item { position: relative; margin-bottom: 1.5rem; }
        .timeline-dot { position: absolute; left: -30px; top: 0; width: 24px; height: 24px; border-radius: 50%; background: white; border: 3px solid #e2e8f0; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; color: white; transition: all 0.5s; }
        .timeline-item.active .timeline-dot { border-color: var(--accent-red); background: var(--accent-red); box-shadow: 0 0 0 4px rgba(255, 82, 82, 0.2); animation: pulse-red 1.5s infinite; }
        .timeline-item.completed .timeline-dot { border-color: #10B981; background: #10B981; }
        .timeline-item.completed .timeline-dot::after { content: '\f00c'; font-family: 'Font Awesome 6 Free'; font-weight: 900; }
        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(255, 82, 82, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(255, 82, 82, 0); } 100% { box-shadow: 0 0 0 0 rgba(255, 82, 82, 0); } }
        
        .btn-history { background: var(--primary-blue); color: white; border: none; min-height: 52px; border-radius: 12px; font-weight: 600; width: 100%; margin-top: auto; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="map-container" id="map"></div>
    
    <div class="tracking-card">
        <h5 class="fw-bold mb-1">{{ $alerte ? 'Suivi de votre alerte' : 'Aucune alerte en cours' }}</h5>
        <p class="text-muted small mb-3">
            @if($alerte)
                @if($mission)
                    Statut : <span class="fw-bold text-danger">{{ strtoupper(str_replace('_', ' ', $mission->statut)) }}</span>
                @else
                    Votre demande est enregistrée et en attente de traitement.
                @endif
            @else
                Aucune alerte n'a encore été enregistrée.
            @endif
        </p>
        
        @php
            $hasMission = (bool) $mission;
            $isOnSite = $mission && in_array($mission->statut, ['sur_place', 'terminee']);
            $isEnRoute = $mission && $mission->statut === 'en_route';
        @endphp

        <div class="timeline">
            <div class="timeline-item completed">
                <div class="timeline-dot"></div>
                <h6 class="fw-bold mb-1">Alerte reçue</h6>
                <p class="small text-muted mb-0">{{ $alerte ? $alerte->created_at->locale('fr')->isoFormat('DD MMM YYYY • HH:mm') : 'Aucune alerte' }}</p>
            </div>
            <div class="timeline-item {{ $hasMission ? 'completed' : ($alerte ? 'active' : '') }}" id="step-en-route">
                <div class="timeline-dot"></div>
                <h6 class="fw-bold mb-1">{{ $hasMission ? 'Ambulance dispatchée' : 'En attente d’assignation' }}</h6>
                <p class="small text-muted mb-0">
                    @if($mission && $mission->ambulance)
                        Véhicule {{ $mission->ambulance->matricule }} • {{ strtoupper(str_replace('_', ' ', $mission->statut)) }}.
                    @else
                        Aucune ambulance assignée pour le moment.
                    @endif
                </p>
            </div>
            <div class="timeline-item {{ $isOnSite ? 'completed' : ($isEnRoute ? 'active' : '') }}" id="step-arrive">
                <div class="timeline-dot"></div>
                <h6 class="fw-bold mb-1 {{ $isOnSite ? '' : 'text-muted' }}">{{ $isEnRoute ? 'Ambulance en route' : ($isOnSite ? 'Arrivée sur les lieux' : 'En attente...') }}</h6>
                <p class="small text-muted mb-0">
                    @if($isOnSite)
                        Intervention en cours ou terminée.
                    @elseif($isEnRoute)
                        L’ambulance est en route vers votre position.
                    @else
                        En attente de la progression de la mission.
                    @endif
                </p>
            </div>
        </div>

        <button class="btn btn-history" onclick="window.location.href='/citoyen/historique'">
            <i class="fa-solid fa-clock-rotate-left me-2"></i> Voir l'historique
        </button>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialisation de la carte (Cotonou)
        const map = L.map('map', { zoomControl: false }).setView([6.3654, 2.4183], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marqueur utilisateur
        L.marker([6.3654, 2.4183]).addTo(map).bindPopup("Votre position").openPopup();
    </script>

@include('partials.pwa-register')
</body>
</html>