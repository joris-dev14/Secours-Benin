<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); }
        .sidebar { width: 260px; background: var(--primary-blue); color: var(--white); min-height: 100vh; position: fixed; left: 0; top: 0; z-index: 1000; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.1); display: flex; align-items: center; gap: 10px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 12px 1.5rem; transition: all 0.2s; font-weight: 500; }
        .nav-link:hover, .nav-link.active { color: var(--white); background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-red); }
        .nav-link i { width: 25px; }
        .main-content { margin-left: 260px; padding: 2rem; }
        .param-card { background: var(--white); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 15px rgba(0,0,0,0.03); margin-bottom: 1.5rem; }
        .form-control { border-radius: 10px; border: 2px solid #e2e8f0; padding: 12px; min-height: 48px; }
        .form-control:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(10,37,64,0.1); }
        .btn-save { background: var(--primary-blue); color: white; border: none; min-height: 48px; border-radius: 10px; font-weight: 600; padding: 0 2rem; }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <i class="fa-solid fa-truck-medical fa-2x"></i>
            <div>
                <h5 class="mb-0 fw-bold">Secours Bénin</h5>
                <small class="text-white-50">Espace Ambulancier</small>
            </div>
        </div>
        <div class="d-flex flex-column mt-3">
            <a href="/ambulancier/missions" class="nav-link"><i class="fa-solid fa-clipboard-list"></i> Tableau de bord</a>
            <a href="/ambulancier/mission-active" class="nav-link"><i class="fa-solid fa-route"></i> Mission en cours</a>
            <a href="/ambulancier/historique" class="nav-link"><i class="fa-solid fa-clock-rotate-left"></i> Historique</a>
            <div class="mt-auto">
                <a href="/ambulancier/parametres" class="nav-link active"><i class="fa-solid fa-gear"></i> Paramètres</a>
                <a href="/ambulancier/deconnexion" class="nav-link text-danger"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a>
            </div>
        </div>
    </nav>

    <div class="main-content">
        <div class="d-flex align-items-center gap-3 mb-4">
            <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fa-solid fa-bars"></i>
            </button>
            <h3 class="fw-bold mb-0">Paramètres du compte</h3>
        </div>

        <div class="param-card">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-user me-2"></i> Informations du compte</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nom</label>
                    <input type="text" class="form-control" value="{{ $ambulancier->nom }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Prénom</label>
                    <input type="text" class="form-control" value="{{ $ambulancier->prenom }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Matricule</label>
                    <input type="text" class="form-control" value="{{ $ambulancier->matricule }}" disabled>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Centre</label>
                    <input type="text" class="form-control" value="{{ $ambulancier->centre }}" disabled>
                </div>
            </div>
        </div>

        <div class="param-card">
            <h5 class="fw-bold mb-3"><i class="fa-solid fa-lock me-2"></i> Changer le mot de passe</h5>

            @if(session('success'))
                <div class="alert alert-success" style="border-radius: 10px;">
                    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->has('message'))
                <div class="alert alert-danger" style="border-radius: 10px;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $errors->first('message') }}
                </div>
            @endif

            <form method="POST" action="/ambulancier/parametres">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Ancien mot de passe</label>
                        <input type="password" name="ancien_mot_de_passe" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nouveau mot de passe</label>
                        <input type="password" name="nouveau_mot_de_passe" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Confirmer</label>
                        <input type="password" name="confirmation" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-save">
                            <i class="fa-solid fa-save me-2"></i> Enregistrer le nouveau mot de passe
                        </button>
                    </div>
                </div>
            </form>
        </div>
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