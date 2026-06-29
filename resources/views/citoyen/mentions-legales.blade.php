<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --bg-light: #F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); padding: 1.5rem; line-height: 1.7; }
        .btn-back { width: 40px; height: 40px; border-radius: 50%; background: white; border: none; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); color: var(--primary-blue); margin-bottom: 2rem; text-decoration: none; }
        h3 { font-size: 1.5rem; font-weight: 700; margin-top: 2rem; margin-bottom: 1rem; }
        p { color: #475569; font-size: 0.95rem; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <a href="javascript:history.back()" class="btn-back"><i class="fa-solid fa-arrow-left"></i></a>
    
    <h2 class="fw-bold mb-4">Mentions Légales & Confidentialité</h2>
    
    <h3>1. Collecte des données</h3>
    <p>Conformément à la loi n°2017-20 du 20 avril 2018 portant code du numérique en République du Bénin, les données de localisation et les photographies sont collectées uniquement dans le but exclusif de gérer l'urgence médicale en cours.</p>
    
    <h3>2. Destinataires des données</h3>
    <p>Vos données sont strictement réservées au personnel du Centre de Régulation SAMU compétent et à l'équipe d'ambulanciers dépêchée sur les lieux. Aucune donnée n'est partagée avec des tiers à des fins commerciales.</p>
    
    <h3>3. Conservation et suppression</h3>
    <p>Les photographies et données de localisation précises sont automatiquement et définitivement supprimées de nos serveurs 30 jours après la clôture de l'intervention, conformément aux directives de l'Autorité de Protection des Données à caractère Personnel (APDP).</p>
    
    <h3>4. Droit d'accès et de rectification</h3>
    <p>Vous disposez d'un droit d'accès, de rectification et de suppression de vos données personnelles. Pour l'exercer, contactez le Délégué à la Protection des Données du projet Secours Bénin.</p>
    
    @include('partials.pwa-register')
</body>
</html>