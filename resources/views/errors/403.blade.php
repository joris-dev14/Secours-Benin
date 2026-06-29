<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès refusé - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; }
        body { font-family: 'Poppins', sans-serif; background: #F8F9FA; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .error-card { background: white; border-radius: 24px; padding: 3rem 2rem; text-align: center; max-width: 500px; box-shadow: 0 10px 40px rgba(10,37,64,0.08); }
        .error-code { font-size: 6rem; font-weight: 800; color: #f59e0b; line-height: 1; }
        .btn-home { background: var(--primary-blue); color: white; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 600; text-decoration: none; display: inline-block; transition: all 0.3s; }
        .btn-home:hover { background: #0d3254; color: white; transform: translateY(-2px); }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-code">403</div>
        <i class="fa-solid fa-ban fa-3x text-warning my-3"></i>
        <h3 class="fw-bold mb-2">Accès refusé</h3>
        <p class="text-muted mb-4">Vous n'avez pas les droits nécessaires pour accéder à cette page.</p>
        <a href="/" class="btn-home"><i class="fa-solid fa-house me-2"></i> Retour à l'accueil</a>
    </div>
</body>
</html>