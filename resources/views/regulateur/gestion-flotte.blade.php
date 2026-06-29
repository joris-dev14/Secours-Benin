<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de la Flotte - Secours Bénin</title>
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

        .table-custom { background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .table-custom thead { background: var(--primary-blue); color: var(--white); }
        .table-custom th { font-weight: 600; border: none; padding: 1rem; }
        .table-custom td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        .status-select { border-radius: 8px; border: 1px solid #cbd5e1; padding: 6px 12px; font-size: 0.9rem; font-weight: 600; }

        .action-circle { width:44px; height:44px; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; color:#fff; border:0; box-shadow: 0 6px 16px rgba(30,50,80,0.08); }
        .action-circle.assign { background: linear-gradient(180deg,#2b97ff,#1e6fe6); }
        .action-circle.edit   { background: linear-gradient(180deg,#ffb14a,#ff7a1a); }
        .action-circle.delete { background: linear-gradient(180deg,#ff6b82,#e8424e); }
        .action-item { width:64px; }
        .action-item .label { display:block; font-size:12px; color:#6c757d; margin-top:6px; }
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
            <a href="/regulateur/flotte" class="nav-link active"><i class="fa-solid fa-truck-medical"></i> Gestion de la flotte</a>
            <a href="/regulateur/statistiques" class="nav-link"><i class="fa-solid fa-chart-line"></i> Statistiques</a>
            <div class="mt-auto"><a href="/regulateur/parametres" class="nav-link"><i class="fa-solid fa-gear"></i> Paramètres</a>
    <a href="/regulateur/deconnexion" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></div>
        </div>
    </nav>

    <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h3 class="fw-bold mb-0">Gestion de la flotte</h3>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary" style="background: white; border: 1px solid #e2e8f0; color: var(--primary-blue);" 
                data-bs-toggle="modal" data-bs-target="#ajouterAmbulanceModal">
                <i class="fa-solid fa-plus me-2"></i> Ajouter une ambulance
            </button>
            <button class="btn btn-primary" style="background: var(--primary-blue); border: none;" 
                data-bs-toggle="modal" data-bs-target="#ajouterModal">
                <i class="fa-solid fa-plus me-2"></i> Ajouter un ambulancier
            </button>
        </div>
    </div>

    <form id="filtreForm" method="GET" action="/regulateur/flotte">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body d-flex gap-3 flex-wrap">
                <input type="text" name="search" class="form-control" style="max-width: 300px;" placeholder="Rechercher par matricule ou chauffeur..." value="{{ request('search') }}">
                <select class="form-select" style="max-width: 200px;" onchange="this.form.submit()" name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="disponible"  {{ $statut == 'disponible'  ? 'selected' : '' }}>Disponible</option>
                    <option value="en_mission"  {{ $statut == 'en_mission'  ? 'selected' : '' }}>En mission</option>
                    <option value="maintenance" {{ $statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
        </div>
    </form>

        <!-- Version Desktop : tableau -->
    <div class="table-responsive table-custom d-none d-lg-block">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Véhicule</th>
                <th>Chauffeur</th>
                <th>Centre</th>
                <th>Dernière position</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($ambulances as $ambulance)
            <tr>
                <td class="fw-bold">{{ $ambulance->matricule }}</td>
                <td>{{ $ambulance->modele ?? '—' }}</td>
                <td>
                    @if($ambulance->ambulancier)
                        {{ $ambulance->ambulancier->nom }} {{ $ambulance->ambulancier->prenom }}
                    @else
                        <span class="text-muted">Non assigné</span>
                    @endif
                </td>
                <td>{{ $ambulance->centre ?? '—' }}</td>
                <td>
                    @if($ambulance->commune)
                        <i class="fa-solid fa-location-dot text-danger me-1"></i> {{ $ambulance->commune }}
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>
                    @php
                        $statut = $ambulance->statut;
                        $class = match($statut) {
                            'disponible'  => 'bg-success bg-opacity-10 text-success border-success',
                            'en_mission'  => 'bg-warning bg-opacity-10 text-warning border-warning',
                            'maintenance' => 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                            default       => 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                        };
                    @endphp
                    <select class="status-select {{ $class }}" onchange="changerStatut({{ $ambulance->id }}, this.value)">
                        <option value="disponible"  {{ $statut == 'disponible'  ? 'selected' : '' }}>Disponible</option>
                        <option value="en_mission"  {{ $statut == 'en_mission'  ? 'selected' : '' }}>En mission</option>
                        <option value="maintenance" {{ $statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </td>
                <td>
                    <div class="d-flex align-items-center justify-content-end gap-3">
                        <div class="text-center action-item">
                            <button type="button" class="action-circle assign" onclick="ouvrirAssignToAmbulance({{ $ambulance->id }}, '{{ addslashes($ambulance->matricule) }}')">
                                <i class="fa-solid fa-truck-medical"></i>
                            </button>
                            <span class="label">Assigner</span>
                        </div>
                        <div class="text-center action-item">
                            <button type="button" class="action-circle edit" onclick="ouvrirModal({{ $ambulance->id }}, '{{ $ambulance->matricule }}', '{{ $ambulance->modele }}', '{{ $ambulance->centre }}', '{{ $ambulance->statut }}', {{ $ambulance->ambulancier_id ?? 'null' }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <span class="label">Éditer</span>
                        </div>
                        @if($ambulance->ambulancier_id)
                        <div class="text-center action-item">
                            <form method="POST" action="/regulateur/ambulancier/detacher">
                                @csrf
                                <input type="hidden" name="ambulance_id" value="{{ $ambulance->id }}">
                                <button type="submit" class="action-circle delete">
                                    <i class="fa-solid fa-unlink"></i>
                                </button>
                            </form>
                            <span class="label">Détacher</span>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">Aucune ambulance enregistrée</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    </div>

<!-- Version Mobile : cartes -->
        <div class="d-lg-none">
    @forelse($ambulances as $ambulance)
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 16px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <h6 class="fw-bold mb-1">{{ $ambulance->matricule }}</h6>
                    <small class="text-muted">{{ $ambulance->modele ?? '—' }}</small>
                </div>
                @php
                    $statut = $ambulance->statut;
                    $class = match($statut) {
                        'disponible'  => 'bg-success bg-opacity-10 text-success border-success',
                        'en_mission'  => 'bg-warning bg-opacity-10 text-warning border-warning',
                        'maintenance' => 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                        default       => 'bg-secondary bg-opacity-10 text-secondary border-secondary',
                    };
                @endphp
                <select class="status-select {{ $class }}" onchange="changerStatut({{ $ambulance->id }}, this.value)" style="font-size: 0.8rem;">
                    <option value="disponible"  {{ $statut == 'disponible'  ? 'selected' : '' }}>Disponible</option>
                    <option value="en_mission"  {{ $statut == 'en_mission'  ? 'selected' : '' }}>En mission</option>
                    <option value="maintenance" {{ $statut == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="mb-2">
                <small class="text-muted d-block">
                    <i class="fa-solid fa-user me-1"></i>
                    {{ $ambulance->ambulancier ? $ambulance->ambulancier->nom . ' ' . $ambulance->ambulancier->prenom : 'Non assigné' }}
                </small>
                <small class="text-muted d-block">
                    <i class="fa-solid fa-location-dot me-1 text-danger"></i>
                    {{ $ambulance->commune ?? '—' }} • {{ $ambulance->centre ?? '—' }}
                </small>
            </div>
            <div class="d-flex gap-2 mt-2">
                <button type="button" class="btn btn-sm btn-outline-primary flex-fill"
                    onclick="ouvrirAssignToAmbulance({{ $ambulance->id }}, '{{ addslashes($ambulance->matricule) }}')">
                    <i class="fa-solid fa-truck-medical me-1"></i> Assigner
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary flex-fill"
                    onclick="ouvrirModal({{ $ambulance->id }}, '{{ $ambulance->matricule }}', '{{ $ambulance->modele }}', '{{ $ambulance->centre }}', '{{ $ambulance->statut }}', {{ $ambulance->ambulancier_id ?? 'null' }})">
                    <i class="fa-solid fa-pen me-1"></i> Éditer
                </button>
                @if($ambulance->ambulancier_id)
                <form method="POST" action="/regulateur/ambulancier/detacher" class="flex-fill">
                    @csrf
                    <input type="hidden" name="ambulance_id" value="{{ $ambulance->id }}">
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                        <i class="fa-solid fa-unlink me-1"></i> Détacher
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
        <p class="text-center text-muted py-4">Aucune ambulance enregistrée</p>
    @endforelse
        </div>
            </table>
        </div>
    </div>

    <!-- Liste ambulanciers & assignation -->
    <div class="table-responsive table-custom mt-4">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom et Prénoms</th>
                    <th>Assigné</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ambulanciers as $amb)
                    <tr>
                        <td class="fw-bold">{{ $amb->matricule }}</td>
                        <td>{{ $amb->nom }} {{ $amb->prenom }}</td>
                        <td>
                            @if($amb->ambulance)
                                <span class="fw-semibold">{{ $amb->ambulance->matricule }}</span>
                            @else
                                <span class="text-muted">Aucune</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <div class="text-center action-item">
                                    <button class="action-circle assign" onclick="ouvrirAssignModal({{ $amb->id }}, '{{ addslashes($amb->nom . ' ' . $amb->prenom) }}')">
                                        <i class="fa-solid fa-truck-medical"></i>
                                    </button>
                                    <span class="label">Assigner</span>
                                </div>

                                @if($amb->ambulance)
                                    <div class="text-center action-item">
                                        <form method="POST" action="/regulateur/ambulancier/detacher">
                                            @csrf
                                            <input type="hidden" name="ambulancier_id" value="{{ $amb->id }}">
                                            <button type="submit" class="action-circle delete">
                                                <i class="fa-solid fa-unlink"></i>
                                            </button>
                                        </form>
                                        <span class="label">Détacher</span>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Aucun ambulancier enregistré</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Ajouter Ambulance -->
    <div class="modal fade" id="ajouterAmbulanceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Ajouter une ambulance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ajouterAmbulanceForm" method="POST" action="/regulateur/ambulance/ajouter">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Matricule</label>
                                <input type="text" name="matricule" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Modèle</label>
                                <input type="text" name="modele" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" style="background: var(--primary-blue); border: none;" onclick="document.getElementById('ajouterAmbulanceForm').submit()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Assigner Ambulance -->
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Assigner une ambulance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="assignForm" method="POST" action="/regulateur/ambulancier/assigner">
                        @csrf
                        <input type="hidden" name="ambulancier_id" id="assign_ambulancier_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ambulance disponible</label>
                            <select name="ambulance_id" id="assign_ambulance_select" class="form-select" required>
                                <option value="">Sélectionner</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" style="background: var(--primary-blue); border: none;" onclick="document.getElementById('assignForm').submit()">Assigner</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Assigner depuis ambulance -->
    <div class="modal fade" id="assignToAmbulanceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Assigner un ambulancier à l'ambulance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="assignFromAmbulanceForm" method="POST" action="/regulateur/ambulancier/assigner">
                        @csrf
                        <input type="hidden" name="ambulance_id" id="assign_from_ambulance_id">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ambulancier disponible</label>
                            <select name="ambulancier_id" id="assign_ambulancier_select" class="form-select" required>
                                <option value="">Sélectionner</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" style="background: var(--primary-blue); border: none;" onclick="document.getElementById('assignFromAmbulanceForm').submit()">Assigner</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Ajouter Ambulancier -->
    <div class="modal fade" id="ajouterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Ajouter un ambulancier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="ajouterForm" method="POST" action="/regulateur/ambulancier/ajouter">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nom</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Prénom</label>
                                <input type="text" name="prenom" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Matricule</label>
                                <input type="text" name="matricule" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Mot de passe</label>
                                <input type="password" name="mot_de_passe" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Centre</label>
                                <select name="centre" class="form-select">
                                    @foreach($communes as $commune)
                                        <option value="{{ $commune }}">{{ ucfirst($commune) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Assigner à une ambulance</label>
                                <select name="ambulance_id" class="form-select">
                                    <option value="">Non assigné</option>
                                    @foreach($availableAmbulances as $ambulance)
                                        <option value="{{ $ambulance->id }}">{{ $ambulance->matricule }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" style="background: var(--primary-blue); border: none;"
                        onclick="document.getElementById('ajouterForm').submit()">
                        <i class="fa-solid fa-save me-2"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modifier Ambulance -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0" style="border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Modifier l'ambulance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Matricule</label>
                                <input type="text" name="matricule" id="edit_matricule" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Modèle</label>
                                <input type="text" name="modele" id="edit_modele" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Centre</label>
                                <select name="centre" id="edit_centre" class="form-select">
        <option value="">Sélectionner</option>
        @foreach($communes as $commune)
            <option value="{{ $commune }}">{{ ucfirst($commune) }}</option>
        @endforeach
    </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Statut</label>
                                <select name="statut" id="edit_statut" class="form-select">
                                    <option value="disponible">Disponible</option>
                                    <option value="en_mission">En mission</option>
                                    <option value="maintenance">Maintenance</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Ambulancier assigné</label>
                                <select name="ambulancier_id" id="edit_ambulancier" class="form-select">
                                    <option value="">Non assigné</option>
                                    @foreach($ambulanciers as $amb)
                                        <option value="{{ $amb->id }}">{{ $amb->nom }} {{ $amb->prenom }} ({{ $amb->matricule }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="button" class="btn btn-primary" style="background: var(--primary-blue); border: none;" 
                        onclick="document.getElementById('editForm').submit()">
                        <i class="fa-solid fa-save me-2"></i> Enregistrer
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const availableAmbulances = @json($availableAmbulances);
        const availableAmbulanciers = @json($availableAmbulanciers);

        function ouvrirAssignModal(id, name) {
            document.getElementById('assign_ambulancier_id').value = id;
            const select = document.getElementById('assign_ambulance_select');
            select.innerHTML = '<option value="">Sélectionner</option>';
            availableAmbulances.forEach(a => {
                const option = document.createElement('option');
                option.value = a.id;
                option.textContent = a.matricule + ' (' + a.statut + ')';
                select.appendChild(option);
            });
            var myModal = new bootstrap.Modal(document.getElementById('assignModal'));
            myModal.show();
        }

        function ouvrirAssignToAmbulance(id, matricule) {
            document.getElementById('assign_from_ambulance_id').value = id;
            const select = document.getElementById('assign_ambulancier_select');
            select.innerHTML = '<option value="">Sélectionner</option>';
            availableAmbulanciers.forEach(a => {
                const option = document.createElement('option');
                option.value = a.id;
                option.textContent = a.nom + ' ' + a.prenom + (a.matricule ? ' (' + a.matricule + ')' : '');
                select.appendChild(option);
            });
            var myModal = new bootstrap.Modal(document.getElementById('assignToAmbulanceModal'));
            myModal.show();
        }

        function changerStatut(id, statut) {
            fetch(`/ambulance/${id}/statut`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ statut: statut })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    location.reload();
                }
            });
        }

        function ouvrirModal(id, matricule, modele, centre, statut, ambulancierId) {
            document.getElementById('edit_matricule').value = matricule || '';
            document.getElementById('edit_modele').value = modele || '';
            document.getElementById('edit_centre').value = centre || '';
            document.getElementById('edit_statut').value = statut || '';
            document.getElementById('edit_ambulancier').value = ambulancierId || '';
            document.getElementById('editForm').action = `/regulateur/flotte/${id}`;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
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