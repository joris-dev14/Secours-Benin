<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consentement - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1rem; }
        .consent-card { background: white; border-radius: 24px; box-shadow: 0 10px 40px rgba(10, 37, 64, 0.08); padding: 2.5rem 2rem; width: 100%; max-width: 450px; text-align: center; }
        .icon-shield { width: 80px; height: 80px; background: rgba(10, 37, 64, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; color: var(--primary-blue); }
        .btn-continue { background-color: var(--primary-blue); color: white; border: none; min-height: 52px; border-radius: 12px; font-weight: 600; width: 100%; transition: all 0.3s ease; opacity: 0.5; pointer-events: none; }
        .btn-continue.active { opacity: 1; pointer-events: all; }
        .btn-continue.active:hover { background-color: #0d3254; transform: translateY(-2px); }
        .form-check-input:checked { background-color: var(--primary-blue); border-color: var(--primary-blue); }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="consent-card">
        <div class="icon-shield">
            <i class="fa-solid fa-shield-halved fa-2x"></i>
        </div>
        <h3 class="fw-bold mb-3">Protection de vos données</h3>
        <p class="text-muted mb-4" style="line-height: 1.6;">
            Conformément au Code du Numérique du Bénin, vos données (localisation, photo) sont strictement réservées au personnel SAMU pour gérer votre urgence. Elles seront supprimées après l'intervention.
        </p>
        
        <div class="form-check text-start mb-4 p-3" style="background: var(--bg-light); border-radius: 12px;">
            <input class="form-check-input mt-1" type="checkbox" id="consentCheck" onchange="toggleButton()">
            <label class="form-check-label small fw-semibold" for="consentCheck">
                J'accepte les <a href="{{ url('/citoyen/mentions-legales') }}" class="text-decoration-none" style="color: var(--primary-blue);">Conditions Générales</a> et la <a href="{{ url('/citoyen/mentions-legales') }}" class="text-decoration-none" style="color: var(--primary-blue);">Politique de Confidentialité</a>.
            </label>
        </div>

        <button class="btn btn-continue" id="btnContinue" onclick="window.location.href='/citoyen/nouvelle-alerte'">
            Continuer vers l'alerte <i class="fa-solid fa-arrow-right ms-2"></i>
        </button>
    </div>

    <script>
        function toggleButton() {
            const checkbox = document.getElementById('consentCheck');
            const btn = document.getElementById('btnContinue');
            if (checkbox.checked) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        }
    </script>
    @include('partials.pwa-register')
</body>
</html>