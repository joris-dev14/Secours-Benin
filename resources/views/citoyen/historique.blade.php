<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); padding: 1.5rem; }
        .header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; }
        .btn-back { width: 40px; height: 40px; border-radius: 50%; background: white; border: none; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); color: var(--primary-blue); }
        .history-card { background: white; border-radius: 16px; padding: 1.2rem; margin-bottom: 1rem; border-left: 4px solid var(--primary-blue); box-shadow: 0 2px 10px rgba(0,0,0,0.03); transition: transform 0.2s; }
        .history-card:active { transform: scale(0.98); }
        .badge-success { background: rgba(16, 185, 129, 0.1); color: #059669; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .fab-new { position: fixed; bottom: 2rem; right: 2rem; width: 60px; height: 60px; border-radius: 50%; background: var(--accent-red); color: white; border: none; box-shadow: 0 4px 15px rgba(255, 82, 82, 0.4); font-size: 1.5rem; display: flex; align-items: center; justify-content: center; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="header">
        <button class="btn-back" onclick="window.location.href='/citoyen/suivi-alerte'"><i class="fa-solid fa-arrow-left"></i></button>
        <h4 class="fw-bold mb-0">Mes alertes</h4>
    </div>

    <div class="history-card">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="fw-bold mb-1">Cotonou, Quartier Haie Vive</h6>
                <p class="small text-muted mb-0"><i class="fa-regular fa-calendar me-1"></i> 12 Juin 2026, 14:32</p>
            </div>
            <span class="badge-success">Intervention réussie</span>
        </div>
        <p class="small text-muted mb-0">Temps de réponse : 8 min</p>
    </div>

    <div class="history-card" style="border-left-color: #cbd5e1;">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <h6 class="fw-bold mb-1">Abomey-Calavi, Gbégamey</h6>
                <p class="small text-muted mb-0"><i class="fa-regular fa-calendar me-1"></i> 05 Mai 2026, 09:15</p>
            </div>
            <span class="badge bg-secondary bg-opacity-10 text-secondary" style="font-size: 0.8rem; font-weight: 600;">Annulée par l'utilisateur</span>
        </div>
    </div>

    <button class="fab-new" onclick="window.location.href='/citoyen/nouvelle-alerte'">
        <i class="fa-solid fa-plus"></i>
    </button>
    @include('partials.pwa-register')
</body>
</html>