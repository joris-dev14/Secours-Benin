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
    // Position réelle du citoyen (fallback sur Cotonou si l'alerte n'a pas de GPS)
    const citoyenLat = {{ $alerte->latitude ?? 6.3654 }};
    const citoyenLng = {{ $alerte->longitude ?? 2.4183 }};

    const map = L.map('map', { zoomControl: false }).setView([citoyenLat, citoyenLng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    const citizenMarker = L.marker([citoyenLat, citoyenLng]).addTo(map).bindPopup("Votre position").openPopup();
    const ambulanceIcon = L.divIcon({
        html: '🚑',
        iconSize: [30, 30],
        className: ''
    });
    let ambulanceMarker = null;

    @if ($mission && $mission->ambulance && $mission->ambulance->latitude && $mission->ambulance->longitude)
        ambulanceMarker = L.marker(
            [{{ $mission->ambulance->latitude }}, {{ $mission->ambulance->longitude }}],
            { icon: ambulanceIcon }
        ).addTo(map).bindPopup("Ambulance {{ $mission->ambulance->matricule }}");

        const bounds = L.latLngBounds([
            [citoyenLat, citoyenLng],
            [{{ $mission->ambulance->latitude }}, {{ $mission->ambulance->longitude }}]
        ]);
        map.fitBounds(bounds, { padding: [50, 50] });
    @endif

    function setTimelineState(stepElement, active, completed) {
        stepElement.classList.remove('active', 'completed');
        if (completed) {
            stepElement.classList.add('completed');
        } else if (active) {
            stepElement.classList.add('active');
        }
    }

    function updateTimeline(alerte, mission) {
        const stepEnRoute = document.getElementById('step-en-route');
        const stepArrive = document.getElementById('step-arrive');

        if (!alerte) {
            setTimelineState(stepEnRoute, false, false);
            setTimelineState(stepArrive, false, false);
            stepEnRoute.querySelector('h6').textContent = 'En attente d’assignation';
            stepEnRoute.querySelector('p').textContent = 'Aucune alerte n’a encore été assignée.';
            stepArrive.querySelector('h6').textContent = 'En attente...';
            stepArrive.querySelector('p').textContent = 'En attente de la progression de la mission.';
            return;
        }

        if (!mission) {
            setTimelineState(stepEnRoute, true, false);
            setTimelineState(stepArrive, false, false);
            stepEnRoute.querySelector('h6').textContent = 'En attente d’assignation';
            stepEnRoute.querySelector('p').textContent = 'Aucune ambulance assignée pour le moment.';
            stepArrive.querySelector('h6').textContent = 'En attente...';
            stepArrive.querySelector('p').textContent = 'En attente de la progression de la mission.';
            return;
        }

        const statut = mission.statut;
        const hasArrived = ['sur_place', 'terminee'].includes(statut);
        const isEnRoute = statut === 'en_route';

        setTimelineState(stepEnRoute, statut !== 'assignee', true);
        setTimelineState(stepArrive, isEnRoute, hasArrived);

        if (isEnRoute) {
            stepEnRoute.querySelector('h6').textContent = 'Ambulance dispatchée';
            stepEnRoute.querySelector('p').textContent = mission.ambulance ? `Véhicule ${mission.ambulance.matricule} • EN ROUTE.` : 'Véhicule en route.';
            stepArrive.querySelector('h6').textContent = 'Ambulance en route';
            stepArrive.querySelector('p').textContent = 'L’ambulance est en route vers votre position.';
        } else if (hasArrived) {
            stepEnRoute.querySelector('h6').textContent = 'Ambulance dispatchée';
            stepEnRoute.querySelector('p').textContent = mission.ambulance ? `Véhicule ${mission.ambulance.matricule} • ${statut.toUpperCase()}.` : 'Véhicule assigné.';
            stepArrive.querySelector('h6').textContent = 'Arrivée sur les lieux';
            stepArrive.querySelector('p').textContent = 'Intervention en cours ou terminée.';
        } else {
            stepEnRoute.querySelector('h6').textContent = 'Ambulance dispatchée';
            stepEnRoute.querySelector('p').textContent = mission.ambulance ? `Véhicule ${mission.ambulance.matricule} • ${statut.toUpperCase()}.` : 'Véhicule assigné.';
            stepArrive.querySelector('h6').textContent = 'En attente...';
            stepArrive.querySelector('p').textContent = 'En attente de la progression de la mission.';
        }
    }

    async function refreshSuivi() {
        try {
            const response = await fetch('/citoyen/suivi-alerte/data', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await response.json();
            if (!data.success) {
                return;
            }

            updateTimeline(data.alerte, data.mission);

            if (data.mission && data.mission.ambulance && data.mission.ambulance.latitude && data.mission.ambulance.longitude) {
                const lat = parseFloat(data.mission.ambulance.latitude);
                const lng = parseFloat(data.mission.ambulance.longitude);
                if (!ambulanceMarker) {
                    ambulanceMarker = L.marker([lat, lng], { icon: ambulanceIcon })
                        .addTo(map)
                        .bindPopup(`Ambulance ${data.mission.ambulance.matricule}`);
                } else {
                    ambulanceMarker.setLatLng([lat, lng]);
                    ambulanceMarker.setPopupContent(`Ambulance ${data.mission.ambulance.matricule}`);
                }

                const bounds = L.latLngBounds([
                    [citoyenLat, citoyenLng],
                    [lat, lng]
                ]);
                map.fitBounds(bounds, { padding: [50, 50] });
            } else if (ambulanceMarker) {
                map.removeLayer(ambulanceMarker);
                ambulanceMarker = null;
                map.setView([citoyenLat, citoyenLng], 14);
            }
        } catch (error) {
            console.error('Erreur de suivi en temps réel :', error);
        }
    }

    setInterval(refreshSuivi, 10000);
</script>


@include('partials.pwa-register')
</body>
</html>