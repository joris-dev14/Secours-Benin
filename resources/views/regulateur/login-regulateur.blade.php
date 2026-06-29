<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Régulateur - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --white: #FFFFFF; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--primary-blue); color: var(--white); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { background: var(--white); border-radius: 20px; padding: 2.5rem; width: 100%; max-width: 420px; color: var(--primary-blue); box-shadow: 0 20px 50px rgba(0,0,0,0.3); }
        .logo-container { text-align: center; margin-bottom: 2rem; }
        .logo-icon { font-size: 3rem; color: var(--primary-blue); }
        .logo-accent { color: var(--accent-red); }
        .btn-login { background-color: var(--primary-blue); color: var(--white); border: none; min-height: 52px; border-radius: 12px; font-weight: 600; width: 100%; transition: all 0.3s; }
        .btn-login:hover { background-color: #0d3254; transform: translateY(-2px); }
        .form-control { border-radius: 10px; border: 2px solid #e2e8f0; padding: 12px; min-height: 52px; }
        .form-control:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(10, 37, 64, 0.1); }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="login-card">
        <div class="logo-container">
            <i class="fa-solid fa-shield-halved logo-icon"></i>
            <i class="fa-solid fa-plus logo-icon logo-accent" style="font-size: 1.5rem; margin-left: -15px; vertical-align: top;"></i>
            <h3 class="fw-bold mt-2">Secours Bénin</h3>
            <p class="text-muted small">Portail Régulateur SAMU</p>
        </div>
                <form method="POST" action="/regulateur/login">
    @csrf

    @if($errors->has('message'))
        <div class="alert alert-danger mb-3" style="border-radius: 10px;">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            {{ $errors->first('message') }}
        </div>
    @endif

    <div class="mb-3">
        <label class="form-label fw-semibold">Identifiant</label>
        <input type="text" name="matricule" class="form-control" placeholder="ex: REG-001" required>
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold">Mot de passe</label>
        <input type="password" name="mot_de_passe" class="form-control" placeholder="••••••••" required>
    </div>
    <button type="submit" class="btn btn-login">
        <i class="fa-solid fa-right-to-bracket me-2"></i> Se connecter
    </button>
</form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.pwa-register')
</body>
</html>