<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Utilisateurs - Secours Bénin</title>
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
        
        .filter-card { background: var(--white); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .table-custom { background: var(--white); border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .table-custom thead { background: var(--primary-blue); color: var(--white); }
        .table-custom th { font-weight: 600; border: none; padding: 1rem; }
        .table-custom td { padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }
        
        .badge-role { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
        .role-citoyen { background: rgba(100, 116, 139, 0.1); color: #64748b; }
        .role-regulateur { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .role-ambulancier { background: rgba(16, 185, 129, 0.1); color: var(--success-green); }
        .role-admin { background: rgba(255, 82, 82, 0.1); color: var(--accent-red); }
        
        .badge-status { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
        .status-active { background: rgba(16, 185, 129, 0.1); color: var(--success-green); }
        .status-suspended { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .status-blocked { background: rgba(255, 82, 82, 0.1); color: var(--accent-red); }
        
        .btn-action { width: 36px; height: 36px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; border: none; transition: all 0.2s; }
        .btn-action:hover { transform: translateY(-2px); }
        .btn-edit { background: rgba(10, 37, 64, 0.1); color: var(--primary-blue); }
        .btn-suspend { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .btn-block { background: rgba(255, 82, 82, 0.1); color: var(--accent-red); }
        
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
            <a href="/admin/utilisateurs" class="nav-link active"><i class="fa-solid fa-users-gear"></i> Utilisateurs</a>
            <a href="/admin/territoire" class="nav-link"><i class="fa-solid fa-map-location-dot"></i> Territoire</a>
            <a href="/admin/territoire" class="nav-link"><i class="fa-solid fa-shield-halved"></i> Modération</a>
            <a href="/admin/rapports" class="nav-link"><i class="fa-solid fa-file-lines"></i> Rapports</a>
            <div class="mt-auto"><a href="/admin/login" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></div>
        </div>
    </nav>

    <div class="main-content">
        @if(session('success'))
    <div class="alert alert-success mb-4" style="border-radius: 10px;">
        <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')"><i class="fa-solid fa-bars"></i></button>
        <h3 class="fw-bold mb-0">Gestion des utilisateurs</h3>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn btn-danger" id="btnSupprimerSelection" style="display:none;" onclick="confirmerSuppression()">
    <i class="fa-solid fa-trash me-2"></i> Supprimer la sélection
</button>

        <button class="btn btn-primary" style="background: var(--primary-blue); border: none;" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fa-solid fa-user-plus me-2"></i> Nouvel utilisateur
        </button>
    </div>
</div>

        <!-- Filtres -->
       <form method="GET" action="/admin/utilisateurs" id="filtreForm">
    <div class="filter-card">
        <div class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Rechercher par nom, téléphone, matricule..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="role" onchange="this.form.submit()">
                    <option value="">Tous les rôles</option>
                    <option value="citoyen"     {{ $role == 'citoyen'     ? 'selected' : '' }}>Citoyen</option>
                    <option value="regulateur"  {{ $role == 'regulateur'  ? 'selected' : '' }}>Régulateur</option>
                    <option value="ambulancier" {{ $role == 'ambulancier' ? 'selected' : '' }}>Ambulancier</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="statut" onchange="this.form.submit()">
                    <option value="">Tous les statuts</option>
                    <option value="actif"      {{ $statut == 'actif'      ? 'selected' : '' }}>Actif</option>
                    <option value="inactif"    {{ $statut == 'inactif'    ? 'selected' : '' }}>Inactif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100" style="background: var(--primary-blue); border: none;">
                    <i class="fa-solid fa-search me-2"></i> Rechercher
                </button>
            </div>
            <div class="col-md-2">
                <a href="/admin/utilisateurs" class="btn btn-outline-secondary w-100">
                    <i class="fa-solid fa-rotate-right me-2"></i> Actualiser
                </a>
            </div>
        </div>
    </div>
</form>

        <!-- Résumé -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="filter-card mb-0 text-center">
            <h4 class="fw-bold mb-1">{{ $total }}</h4>
            <small class="text-muted">Total utilisateurs</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="filter-card mb-0 text-center">
            <h4 class="fw-bold mb-1" style="color: var(--success-green);">{{ $totalCitoyens }}</h4>
            <small class="text-muted">Citoyens</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="filter-card mb-0 text-center">
            <h4 class="fw-bold mb-1" style="color: #f59e0b;">{{ $totalRegulateurs }}</h4>
            <small class="text-muted">Régulateurs</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="filter-card mb-0 text-center">
            <h4 class="fw-bold mb-1" style="color: var(--accent-red);">{{ $totalAmbulanciers }}</h4>
            <small class="text-muted">Ambulanciers</small>
        </div>
    </div>
</div>

  <!-- Tableau -->
<form method="POST" action="/admin/utilisateurs/supprimer" id="suppressionForm">
@csrf

<!-- Version Desktop -->
<div class="table-responsive table-custom d-none d-lg-block">
    <table class="table mb-0">
        <thead>
            <tr>
                <th><input type="checkbox" class="form-check-input" id="checkAll" onchange="toggleAll(this)"></th>
                <th>Nom / Matricule</th>
                <th>Rôle</th>
                <th>Téléphone</th>
                <th>Commune / Centre</th>
                <th>Inscription</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($utilisateurs as $user)
            <tr>
                <td><input type="checkbox" class="form-check-input check-item" name="ids[]" value="{{ $user['role'] }}:{{ $user['id'] }}"></td>
                <td>
                    <strong>{{ $user['nom'] }}</strong><br>
                    <small class="text-muted">{{ $user['matricule'] ?? $user['telephone'] }}</small>
                </td>
                <td>
                    @if($user['role'] == 'citoyen')
                        <span class="badge-role role-citoyen">Citoyen</span>
                    @elseif($user['role'] == 'regulateur')
                        <span class="badge-role role-regulateur">Régulateur</span>
                    @elseif($user['role'] == 'ambulancier')
                        <span class="badge-role role-ambulancier">Ambulancier</span>
                    @endif
                </td>
                <td>{{ $user['telephone'] }}</td>
                <td>{{ $user['centre'] }}</td>
                <td>{{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y') }}</td>
                <td>
                    @php $display = $user['statut_display'] ?? $user['statut']; @endphp
                    @if($display == 'actif')
                        <span class="badge-status status-active">
                            {{ $user['role'] == 'ambulancier' ? ucfirst($user['statut']) : 'Actif' }}
                        </span>
                    @else
                        <span class="badge-status status-blocked">Inactif</span>
                    @endif
                </td>
                <td style="white-space: nowrap;">
                    @if($user['role'] == 'citoyen')
                        <button type="button" class="btn-action" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;" title="Avertir"
                            onclick="ouvrirAvertissement({{ $user['id'] }}, '{{ $user['telephone'] }}')">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </button>
                    @else
                        <button type="button" class="btn-action btn-edit" title="Modifier"
                            onclick="ouvrirModification('{{ $user['role'] }}', {{ $user['id'] }}, '{{ $user['nom'] }}', '{{ $user['matricule'] }}', '{{ $user['centre'] }}')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        @php $display = $user['statut_display'] ?? $user['statut']; @endphp
                        @if($display == 'actif')
                            <button type="button" class="btn-action btn-block" title="Bloquer"
                                onclick="bloquerUser('{{ $user['role'] }}', {{ $user['id'] }})">
                                <i class="fa-solid fa-ban"></i>
                            </button>
                        @else
                            <button type="button" class="btn-action btn-block" style="background: var(--success-green); color: white;" title="Débloquer"
                                onclick="debloquerUser('{{ $user['role'] }}', {{ $user['id'] }})">
                                <i class="fa-solid fa-unlock"></i>
                            </button>
                        @endif
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">Aucun utilisateur trouvé</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Version Mobile : cartes -->
<div class="d-lg-none">
    @forelse($utilisateurs as $user)
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 16px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <div>
                    <strong>{{ $user['nom'] }}</strong><br>
                    <small class="text-muted">{{ $user['matricule'] ?? $user['telephone'] }}</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($user['role'] == 'citoyen')
                        <span class="badge-role role-citoyen">Citoyen</span>
                    @elseif($user['role'] == 'regulateur')
                        <span class="badge-role role-regulateur">Régulateur</span>
                    @elseif($user['role'] == 'ambulancier')
                        <span class="badge-role role-ambulancier">Ambulancier</span>
                    @endif
                </div>
            </div>
            <div class="mb-2">
                @if($user['telephone'] != '—')
                <small class="text-muted d-block"><i class="fa-solid fa-phone me-1"></i> {{ $user['telephone'] }}</small>
                @endif
                @if($user['centre'] != '—')
                <small class="text-muted d-block"><i class="fa-solid fa-location-dot me-1"></i> {{ $user['centre'] }}</small>
                @endif
                <small class="text-muted d-block"><i class="fa-regular fa-calendar me-1"></i> {{ \Carbon\Carbon::parse($user['created_at'])->format('d M Y') }}</small>
            </div>
            <div class="d-flex justify-content-between align-items-center">
                @php $display = $user['statut_display'] ?? $user['statut']; @endphp
                @if($display == 'actif')
                    <span class="badge-status status-active">
                        {{ $user['role'] == 'ambulancier' ? ucfirst($user['statut']) : 'Actif' }}
                    </span>
                @else
                    <span class="badge-status status-blocked">Inactif</span>
                @endif
                <div class="d-flex gap-2">
                    <input type="checkbox" class="form-check-input check-item" name="ids[]" value="{{ $user['role'] }}:{{ $user['id'] }}">
                    @if($user['role'] == 'citoyen')
                        <button type="button" class="btn-action" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;" title="Avertir"
                            onclick="ouvrirAvertissement({{ $user['id'] }}, '{{ $user['telephone'] }}')">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </button>
                    @else
                        <button type="button" class="btn-action btn-edit" title="Modifier"
                            onclick="ouvrirModification('{{ $user['role'] }}', {{ $user['id'] }}, '{{ $user['nom'] }}', '{{ $user['matricule'] }}', '{{ $user['centre'] }}')">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        @if($display == 'actif')
                            <button type="button" class="btn-action btn-block" title="Bloquer"
                                onclick="bloquerUser('{{ $user['role'] }}', {{ $user['id'] }})">
                                <i class="fa-solid fa-ban"></i>
                            </button>
                        @else
                            <button type="button" class="btn-action btn-block" style="background: var(--success-green); color: white;" title="Débloquer"
                                onclick="debloquerUser('{{ $user['role'] }}', {{ $user['id'] }})">
                                <i class="fa-solid fa-unlock"></i>
                            </button>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
        <p class="text-center text-muted py-4">Aucun utilisateur trouvé</p>
    @endforelse
</div>
</form>
        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-3 px-2">
            <small class="text-muted">Affichage de 1 à 5 sur 12,458 utilisateurs</small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#"><i class="fa-solid fa-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link" href="#" style="background: var(--primary-blue); border-color: var(--primary-blue);">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">...</a></li>
                    <li class="page-item"><a class="page-link" href="#">2492</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="fa-solid fa-chevron-right"></i></a></li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- Modal Modifier Utilisateur -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Modifier l'utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form method="POST" id="editUserForm">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom complet</label>
                            <input type="text" name="nom" id="edit_nom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Matricule</label>
                            <input type="text" name="matricule" id="edit_matricule" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Centre</label>
                            <select name="centre" id="edit_centre" class="form-select">
                                @foreach($communes as $commune)
                                    <option value="{{ $commune->nom }}">
                                        {{ $commune->nom }}@if($commune->centre_samu) ({{ $commune->centre_samu }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" style="background: var(--primary-blue); border: none;" 
                    onclick="document.getElementById('editUserForm').submit()">
                    <i class="fa-solid fa-save me-2"></i> Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
    <!-- Modal Avertissement -->
<div class="modal fade" id="avertissementModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-triangle-exclamation text-warning me-2"></i>
                    Envoyer un avertissement
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small mb-3">
                    Citoyen : <strong id="avert_telephone"></strong>
                </p>
                <form method="POST" id="avertissementForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Message d'avertissement</label>
                        <textarea name="message" class="form-control" rows="4" 
                            placeholder="Ex: Votre compte a été signalé pour envoi de fausses alertes. Tout abus répété entraînera la suspension de votre accès." 
                            required></textarea>
                        <small class="text-muted">Maximum 500 caractères</small>
                    </div>
                    <!-- Messages prédéfinis -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Messages rapides :</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary" 
                                onclick="setMessage('Votre compte a été signalé pour envoi de fausses alertes. Merci de ne pas abuser du système.')">
                                Fausse alerte
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary"
                                onclick="setMessage('Attention : un comportement abusif a été détecté sur votre compte. Tout abus répété entraînera la suspension.')">
                                Comportement abusif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning fw-bold" onclick="document.getElementById('avertissementForm').submit()">
                    <i class="fa-solid fa-paper-plane me-2"></i> Envoyer l'avertissement
                </button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Ajout/Édition -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0" style="border-radius: 20px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Nouvel utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                @if($errors->any())
    <div class="alert alert-danger" style="border-radius: 10px;">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
                @endif

                <form method="POST" action="/admin/utilisateurs" id="formNouvelUtilisateur">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom</label>
                            <input type="text" name="nom" class="form-control" placeholder="ex: KOFFI" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prénom</label>
                            <input type="text" name="prenom" class="form-control" placeholder="ex: Jean" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Rôle</label>
                            <select class="form-select" name="role" required>
                                <option value="">Sélectionner un rôle</option>
                                <option value="regulateur">Régulateur</option>
                                <option value="ambulancier">Ambulancier</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Matricule</label>
                            <input type="text" name="matricule" class="form-control" placeholder="ex: REG-002 ou AMB-105" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Commune / Centre</label>
                            <select class="form-select" name="centre" required>
                                <option value="">Sélectionner</option>
                                @foreach($communes as $commune)
                                    <option value="{{ $commune->nom }}">
                                        {{ $commune->nom }}@if($commune->centre_samu) ({{ $commune->centre_samu }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mot de passe temporaire</label>
                            <input type="password" name="mot_de_passe" class="form-control" placeholder="••••••••" required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
            <button type="button" class="btn btn-primary" style="background: var(--primary-blue); border: none;" onclick="document.getElementById('formNouvelUtilisateur').submit()">
        <i class="fa-solid fa-save me-2"></i> Enregistrer
    </button>
</div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function ouvrirModification(role, id, nom, matricule, centre) {
    document.getElementById('edit_nom').value = nom;
    document.getElementById('edit_matricule').value = matricule;
    document.getElementById('edit_centre').value = centre;
    document.getElementById('editUserForm').action = `/admin/utilisateurs/${role}/${id}/modifier`;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
function ouvrirAvertissement(id, telephone) {
    document.getElementById('avert_telephone').textContent = telephone;
    document.getElementById('avertissementForm').action = `/admin/utilisateurs/citoyen/${id}/avertir`;
    document.querySelector('#avertissementForm textarea').value = '';
    new bootstrap.Modal(document.getElementById('avertissementModal')).show();
}
function bloquerUser(role, id) {
    if (confirm('Bloquer cet utilisateur ?')) {
        fetch(`/admin/utilisateurs/${role}/${id}/bloquer`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(() => location.reload());
    }
}

function debloquerUser(role, id) {
    if (confirm('Débloquer cet utilisateur ?')) {
        fetch(`/admin/utilisateurs/${role}/${id}/debloquer`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' }
        }).then(() => location.reload());
    }
}
function setMessage(msg) {
    document.querySelector('#avertissementForm textarea').value = msg;
}

function toggleAll(source) {
    document.querySelectorAll('.check-item').forEach(cb => cb.checked = source.checked);
    updateBtnSupprimer();
}

document.addEventListener('change', function(e) {
    if (e.target.classList.contains('check-item')) {
        updateBtnSupprimer();
    }
});

function updateBtnSupprimer() {
    const checked = document.querySelectorAll('.check-item:checked').length;
    document.getElementById('btnSupprimerSelection').style.display = checked > 0 ? 'inline-block' : 'none';
}

function confirmerSuppression() {
    const checked = document.querySelectorAll('.check-item:checked').length;
    if (checked === 0) { alert('Veuillez sélectionner au moins un utilisateur.'); return; }
    if (confirm(`Confirmer la suppression de ${checked} utilisateur(s) ? Cette action est irréversible.`)) {
        document.getElementById('suppressionForm').submit();
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