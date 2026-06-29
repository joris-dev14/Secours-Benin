<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Territoire - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
        
        .config-card { background: var(--white); border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 1.5rem; }
        .map-container { height: 500px; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        
        .commune-item { border: 2px solid #e2e8f0; border-radius: 12px; padding: 1rem; margin-bottom: 1rem; transition: all 0.2s; cursor: pointer; }
        .commune-item:hover { border-color: var(--primary-blue); background: rgba(10, 37, 64, 0.02); }
        .commune-item.selected { border-color: var(--accent-red); background: rgba(255, 82, 82, 0.05); }
        
        .hospital-badge { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; background: rgba(16, 185, 129, 0.1); color: var(--success-green); margin: 2px; }
        
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
            <a href="/admin/territoire" class="nav-link active"><i class="fa-solid fa-map-location-dot"></i> Territoire</a>
            <a href="/admin/moderation" class="nav-link"><i class="fa-solid fa-shield-halved"></i> Modération</a>
            <a href="/admin/rapports" class="nav-link"><i class="fa-solid fa-file-lines"></i> Rapports</a>
            <div class="mt-auto"><a href="/admin/login" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')"><i class="fa-solid fa-bars"></i></button>
                <h3 class="fw-bold mb-0">Configuration du territoire</h3>
            </div>
            <a href="/admin/dashboard" class="btn btn-outline-secondary"><i class="fa-solid fa-arrow-left me-2"></i> Retour</a>
        </div>

       <div class="row g-4">
    <!-- Colonne Gauche : Liste des communes -->
<div class="col-lg-5">
    <div class="config-card">
        <h6 class="fw-bold mb-3"><i class="fa-solid fa-city me-2"></i>Communes & Centres SAMU</h6>

        <div class="row g-2 mb-3">
            <div class="col-7">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" id="searchCommune" class="form-control" placeholder="Rechercher une commune..." oninput="filtrerEtAfficher()">
                </div>
            </div>
            <div class="col-5">
                <select class="form-select" id="filtreStatut" onchange="filtrerEtAfficher()">
                    <option value="">Toutes les communes</option>
                    <option value="active">Couvertes (Active)</option>
                    <option value="inactive">Non couvertes</option>
                </select>
            </div>
        </div>

        <div id="communeListContainer" style="max-height: 650px; overflow-y: auto;">
            <!-- Communes injectées ici en JavaScript -->
        </div>
    </div>
</div>
    <!-- Colonne Droite : Carte + Édition -->
    <div class="col-lg-7">
        <div class="config-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-map me-2"></i>Carte de couverture</h6>
                <button class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-expand"></i> Plein écran</button>
            </div>
            <div id="map" class="map-container"></div>
        </div>

        <div class="config-card">
            <h6 class="fw-bold mb-3" id="configTitle"><i class="fa-solid fa-gear me-2"></i>Configuration rapide</h6>

            @if(session('success'))
                <div class="alert alert-success" style="border-radius: 10px;">
                    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            <form method="POST" id="configForm" action="/admin/territoire">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Centre SAMU de rattachement</label>
                        <input type="text" name="centre_samu" id="config_centre" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Numéro vert local</label>
                        <input type="text" name="numero_vert" id="config_numero" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Coordonnées centre (GPS)</label>
                        <input type="text" id="config_gps" class="form-control" disabled>
                        <input type="hidden" name="latitude" id="config_lat">
                        <input type="hidden" name="longitude" id="config_lng">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Rayon de couverture (km)</label>
                        <input type="number" name="rayon_couverture" id="config_rayon" class="form-control">
                    </div>
                    <div class="col-12">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="redirection_auto" id="config_redirection" value="1">
                            <label class="form-check-label fw-semibold" for="config_redirection">
                                Redirection automatique vers centre voisin si saturation
                            </label>
                        </div>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary" style="background: var(--primary-blue); border: none;">
                            <i class="fa-solid fa-save me-2"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </form>
            <div class="mt-3 pt-3" style="border-top: 1px solid #f1f5f9;">
                <label class="form-label fw-semibold">Hôpitaux partenaires</label>
                <div id="hopitauxListe" class="mb-2"></div>
                <div class="input-group">
                    <input type="text" id="nouvelHopitalInput" class="form-control" placeholder="Nom de l'hôpital...">
                    <button type="button" class="btn btn-outline-primary" onclick="ajouterHopital()">
                        <i class="fa-solid fa-plus"></i> Ajouter
                    </button>
                </div>
                </div>
        </div>
    </div>
</div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toutesLesCommunes = {!! json_encode($communes) !!};

    const map = L.map('map').setView([6.4, 2.4], 8);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OSM' }).addTo(map);

    toutesLesCommunes.filter(c => c.statut === 'active' && c.latitude && c.longitude)
        .forEach(c => {
            L.marker([c.latitude, c.longitude]).addTo(map)
                .bindPopup(`<strong>${c.centre_samu}</strong>`);
        });

    function creerCarteCommune(commune) {
        const div = document.createElement('div');
        div.className = 'commune-item';
        div.dataset.id = commune.id;

        const hopitauxHtml = commune.hopitaux.length > 0
        ? commune.hopitaux.map(h => `<span class="hospital-badge"><i class="fa-solid fa-hospital"></i> ${h.nom}</span>`).join(' ')
        : '<span class="text-muted small">Aucun</span>';

        div.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="fw-bold mb-1">${commune.nom}</h6>
                    <small class="text-muted">Département ${commune.departement}</small>
                </div>
                <span class="badge ${commune.statut === 'active' ? 'bg-primary bg-opacity-10 text-primary' : 'bg-secondary bg-opacity-10 text-secondary'}">
                    ${commune.statut === 'active' ? 'Active' : 'Non couverte'}
                </span>
            </div>
            <div class="mb-2">
                <small class="text-muted fw-semibold">Centre SAMU rattaché :</small>
                <div class="fw-bold" style="color: var(--primary-blue);">${commune.centre_samu ?? 'Aucun centre assigné'}</div>
            </div>
            <div>
                <small class="text-muted fw-semibold d-block mb-1">Hôpitaux partenaires :</small>
                ${hopitauxHtml}
            </div>
            <div class="mt-2 pt-2" style="border-top: 1px solid #f1f5f9;">
                <small class="text-muted">
                    <i class="fa-solid fa-truck-medical me-1"></i> ${commune.total_ambulances} ambulances •
                    <i class="fa-solid fa-users me-1"></i> ${commune.total_citoyens} citoyens
                </small>
            </div>
        `;

        div.addEventListener('click', () => selectCommune(div, commune));
        return div;
    }

    function filtrerEtAfficher() {
        const recherche = document.getElementById('searchCommune').value.toLowerCase();
        const statut = document.getElementById('filtreStatut').value;

        const resultats = toutesLesCommunes.filter(c => {
            const matchNom = c.nom.toLowerCase().includes(recherche);
            const matchStatut = statut === '' || c.statut === statut;
            return matchNom && matchStatut;
        });

        const container = document.getElementById('communeListContainer');
        container.innerHTML = '';

        if (resultats.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-3">Aucune commune trouvée</p>';
            return;
        }

        resultats.forEach((commune, index) => {
            const carte = creerCarteCommune(commune);
            if (index === 0) carte.classList.add('selected');
            container.appendChild(carte);
        });

        selectCommune(container.firstChild, resultats[0]);
    }

    function selectCommune(el, commune) {
        document.querySelectorAll('.commune-item').forEach(c => c.classList.remove('selected'));
        el.classList.add('selected');

        document.getElementById('configTitle').innerHTML = `<i class="fa-solid fa-gear me-2"></i>Configuration rapide - ${commune.nom}`;
        document.getElementById('config_centre').value = commune.centre_samu ?? '';
        document.getElementById('config_numero').value = commune.numero_vert ?? '';
        document.getElementById('config_gps').value = commune.latitude && commune.longitude ? `${commune.latitude}, ${commune.longitude}` : '';
        document.getElementById('config_lat').value = commune.latitude ?? '';
        document.getElementById('config_lng').value = commune.longitude ?? '';
        document.getElementById('config_rayon').value = commune.rayon_couverture;
        document.getElementById('config_redirection').checked = commune.redirection_auto;
        document.getElementById('configForm').action = `/admin/territoire/${commune.id}`;
        document.getElementById('hopitauxListe').innerHTML = commune.hopitaux.length > 0
        ? commune.hopitaux.map(h => `<span class="hospital-badge">${h.nom} <i class="fa-solid fa-xmark ms-1" style="cursor:pointer;" onclick="supprimerHopital(${h.id})"></i></span>`).join(' ')
        : '<span class="text-muted small">Aucun hôpital</span>';    
    }
    function ajouterHopital() {
        const nom = document.getElementById('nouvelHopitalInput').value.trim();
        if (!nom) return;
        const communeId = document.getElementById('configForm').action.split('/').pop();
        fetch(`/admin/territoire/${communeId}/hopital`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nom })
        }).then(() => location.reload());
    }

    function supprimerHopital(id) {
        if (!confirm('Supprimer cet hôpital ?')) return;
        fetch(`/admin/hopital/${id}/supprimer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    }
    filtrerEtAfficher();

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