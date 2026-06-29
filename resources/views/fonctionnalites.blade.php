<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fonctionnalités - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; --white: #FFFFFF; --success-green: #10B981; }
        * { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; color: var(--primary-blue); overflow-x: hidden; }
        
        .navbar-custom { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 2px 20px rgba(0,0,0,0.05); padding: 1rem 0; }
        .logo-text { font-weight: 800; font-size: 1.5rem; color: var(--primary-blue); }
        .logo-accent { color: var(--accent-red); }
        .nav-link { font-weight: 600; color: var(--primary-blue) !important; margin: 0 0.5rem; position: relative; transition: all 0.3s; }
        .nav-link::after { content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: var(--accent-red); transition: all 0.3s; transform: translateX(-50%); }
        .nav-link:hover::after, .nav-link.active::after { width: 80%; }
        .btn-cta { background: var(--accent-red); color: white; border: none; padding: 10px 24px; border-radius: 50px; font-weight: 700; transition: all 0.3s; }
        .btn-cta:hover { background: #ff3333; color: white; transform: translateY(-2px); }
        
        .page-header { background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); color: white; padding: 150px 0 80px; position: relative; overflow: hidden; }
        .page-header::before { content: ''; position: absolute; top: 0; right: 0; width: 500px; height: 500px; background: radial-gradient(circle, rgba(255, 82, 82, 0.15), transparent); border-radius: 50%; }
        .page-header h1 { font-size: 3rem; font-weight: 900; margin-bottom: 1rem; }
        .section-badge { display: inline-block; background: rgba(255, 82, 82, 0.1); color: var(--accent-red); padding: 6px 16px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; }
        .section-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
        .section-subtitle { color: #64748b; font-size: 1.1rem; margin-bottom: 3rem; }
        
        .content-section { padding: 80px 0; }
        .content-section.alt-bg { background: var(--bg-light); }
        
        /* Interface Tabs */
        .interface-tabs { background: white; border-radius: 20px; padding: 0.5rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05); display: inline-flex; gap: 0.5rem; margin-bottom: 3rem; flex-wrap: wrap; }
        .interface-tab { padding: 12px 24px; border-radius: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s; border: none; background: transparent; color: var(--primary-blue); }
        .interface-tab.active { background: var(--accent-red); color: white; box-shadow: 0 5px 15px rgba(255, 82, 82, 0.3); }
        .interface-tab:hover:not(.active) { background: var(--bg-light); }
        
        .interface-content { display: none; animation: fadeIn 0.5s; }
        .interface-content.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        
        .feature-detail { background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 40px rgba(10, 37, 64, 0.08); margin-bottom: 2rem; }
        .feature-icon-lg { width: 80px; height: 80px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; margin-bottom: 1.5rem; }
        .feature-list { list-style: none; padding: 0; }
        .feature-list li { padding: 0.8rem 0; border-bottom: 1px solid #f1f5f9; display: flex; align-items: flex-start; gap: 12px; }
        .feature-list li:last-child { border-bottom: none; }
        .feature-list li i { color: var(--success-green); margin-top: 4px; }
        
        .tech-card { background: white; border-radius: 16px; padding: 1.5rem; text-align: center; transition: all 0.3s; border: 2px solid #f1f5f9; height: 100%; }
        .tech-card:hover { transform: translateY(-5px); border-color: var(--accent-red); box-shadow: 0 10px 30px rgba(255, 82, 82, 0.1); }
        .tech-icon { width: 60px; height: 60px; border-radius: 15px; background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin: 0 auto 1rem; }
        
        .footer { background: #051525; color: white; padding: 60px 0 20px; }
        .footer h5 { font-weight: 700; margin-bottom: 1.5rem; }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.3s; display: block; padding: 5px 0; }
        .footer a:hover { color: var(--accent-red); padding-left: 5px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; margin-top: 40px; text-align: center; color: rgba(255,255,255,0.5); font-size: 0.9rem; }
        .social-icon { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; transition: all 0.3s; }
        .social-icon:hover { background: var(--accent-red); transform: translateY(-3px); }
        
        @media (max-width: 768px) {
            .page-header h1 { font-size: 2rem; }
            .section-title { font-size: 1.8rem; }
        }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <i class="fa-solid fa-truck-medical fa-2x" style="color: var(--accent-red);"></i>
                <span class="logo-text">Secours<span class="logo-accent">Bénin</span></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Accueil</a></li>
                    <li class="nav-item"><a class="nav-link" href="/a-propos">À propos</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/fonctionnalites">Fonctionnalités</a></li>
                    <li class="nav-item"><a class="nav-link" href="/partenaires">Partenaires</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact">Contact</a></li>
                </ul>
                <a href="/citoyen/auth" class="btn btn-cta"><i class="fa-solid fa-right-to-bracket me-2"></i>Accès plateforme</a>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="page-header">
        <div class="container position-relative" style="z-index: 2;">
            <div class="row">
                <div class="col-lg-8" data-aos="fade-up">
                    <span class="section-badge" style="background: rgba(255,255,255,0.1); color: white;">Fonctionnalités</span>
                    <h1>Une plateforme complète<br>pour chaque acteur</h1>
                    <p>Découvrez les fonctionnalités dédiées à chaque maillon de la chaîne de secours</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Interface Tabs Section -->
    <section class="content-section">
        <div class="container">
            <div class="text-center" data-aos="fade-up">
                <span class="section-badge">Interfaces dédiées</span>
                <h2 class="section-title">Choisissez votre espace</h2>
                <p class="section-subtitle">Chaque acteur dispose d'une interface adaptée à ses besoins</p>
            </div>

            <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="interface-tabs">
                    <button class="interface-tab active" onclick="showInterface('citoyen')">
                        <i class="fa-solid fa-user me-2"></i>Citoyen
                    </button>
                    <button class="interface-tab" onclick="showInterface('regulateur')">
                        <i class="fa-solid fa-headset me-2"></i>Régulateur SAMU
                    </button>
                    <button class="interface-tab" onclick="showInterface('ambulancier')">
                        <i class="fa-solid fa-truck-medical me-2"></i>Ambulancier
                    </button>
                </div>
            </div>

            <!-- Citoyen -->
            <div class="interface-content active" id="citoyen">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6" data-aos="fade-right">
                        <div class="feature-detail">
                            <div class="feature-icon-lg" style="background: rgba(255, 82, 82, 0.1); color: var(--accent-red);">
                                <i class="fa-solid fa-mobile-screen-button"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Interface Citoyen (PWA Mobile)</h3>
                            <p class="text-muted mb-4">Conçue pour être utilisée en situation de stress, en 4 étapes maximum.</p>
                            <ul class="feature-list">
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Authentification par téléphone + OTP</strong><br><small class="text-muted">Inscription rapide et sécurisée</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Sélection de la commune</strong><br><small class="text-muted">Liste déroulante des 77 communes</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Activation GPS automatique</strong><br><small class="text-muted">Localisation précise en temps réel</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Photo obligatoire sur place</strong><br><small class="text-muted">Accès caméra uniquement, compression 2G/3G</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Suivi en temps réel</strong><br><small class="text-muted">3 étapes : reçu, dispatché, arrivé</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Historique des alertes</strong><br><small class="text-muted">Consultation de toutes les interventions passées</small></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left">
                        <div style="background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); border-radius: 30px; padding: 3rem; color: white; text-align: center;">
                            <i class="fa-solid fa-mobile-screen fa-6x mb-4" style="opacity: 0.3;"></i>
                            <h4 class="fw-bold mb-3">Progressive Web App</h4>
                            <p class="opacity-75 mb-4">Installable sur l'écran d'accueil, fonctionne hors-ligne, notifications push natives.</p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">4</h3>
                                    <small class="opacity-75">étapes max</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">&lt;3s</h3>
                                    <small class="opacity-75">envoi alerte</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">200Ko</h3>
                                    <small class="opacity-75">photo max</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Régulateur -->
            <div class="interface-content" id="regulateur">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                        <div class="feature-detail">
                            <div class="feature-icon-lg" style="background: rgba(10, 37, 64, 0.1); color: var(--primary-blue);">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Plateforme Régulateur SAMU (PWA)</h3>
                            <p class="text-muted mb-4">Conçue pour la réactivité et la prise de décision rapide.</p>
                            <ul class="feature-list">
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Alertes en temps réel</strong><br><small class="text-muted">Alarme sonore et visuelle immédiate</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Visualisation complète</strong><br><small class="text-muted">Photo, GPS, commune, description</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Dispatch en un clic</strong><br><small class="text-muted">Assignation rapide d'une ambulance disponible</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Redirection automatique</strong><br><small class="text-muted">Vers centre voisin si indisponible</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Suivi cartographique</strong><br><small class="text-muted">Position temps réel de l'ambulance</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Gestion de la flotte</strong><br><small class="text-muted">Statut des ambulances en temps réel</small></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                        <div style="background: linear-gradient(135deg, var(--accent-red), #ff8a80); border-radius: 30px; padding: 3rem; color: white; text-align: center;">
                            <i class="fa-solid fa-desktop fa-6x mb-4" style="opacity: 0.3;"></i>
                            <h4 class="fw-bold mb-3">Tableau de bord temps réel</h4>
                            <p class="opacity-75 mb-4">Interface dense mais aérée, optimisée pour tablette et desktop.</p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">&lt;5s</h3>
                                    <small class="opacity-75">réception alerte</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">1 clic</h3>
                                    <small class="opacity-75">dispatch</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">24/7</h3>
                                    <small class="opacity-75">disponibilité</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ambulancier -->
            <div class="interface-content" id="ambulancier">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6" data-aos="fade-right">
                        <div class="feature-detail">
                            <div class="feature-icon-lg" style="background: rgba(16, 185, 129, 0.1); color: var(--success-green);">
                                <i class="fa-solid fa-truck-medical"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Interface Ambulancier (PWA Mobile)</h3>
                            <p class="text-muted mb-4">Optimisée pour une utilisation en mouvement, avec un fort contraste.</p>
                            <ul class="feature-list">
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Réception de mission</strong><br><small class="text-muted">Notification push avec détails complets</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Itinéraire GPS optimisé</strong><br><small class="text-muted">Navigation intégrée vers le lieu</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Photo de la scène</strong><br><small class="text-muted">Accès à la photo envoyée par le citoyen</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Mise à jour du statut</strong><br><small class="text-muted">Départ, arrivée, fin de mission</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Horodatage automatique</strong><br><small class="text-muted">Enregistrement précis des étapes</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Historique personnel</strong><br><small class="text-muted">Suivi de ses propres missions</small></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left">
                        <div style="background: linear-gradient(135deg, var(--success-green), #34d399); border-radius: 30px; padding: 3rem; color: white; text-align: center;">
                            <i class="fa-solid fa-route fa-6x mb-4" style="opacity: 0.3;"></i>
                            <h4 class="fw-bold mb-3">Navigation optimisée</h4>
                            <p class="opacity-75 mb-4">Boutons géants, lisibilité en plein soleil, interface épurée.</p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">60px</h3>
                                    <small class="opacity-75">boutons min</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">3</h3>
                                    <small class="opacity-75">étapes mission</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">GPS</h3>
                                    <small class="opacity-75">temps réel</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin -->
            <div class="interface-content" id="admin">
                <div class="row align-items-center g-5">
                    <div class="col-lg-6 order-lg-2" data-aos="fade-left">
                        <div class="feature-detail">
                            <div class="feature-icon-lg" style="background: rgba(10, 37, 64, 0.1); color: var(--primary-blue);">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Plateforme Administrateur (Web Desktop)</h3>
                            <p class="text-muted mb-4">Interface complète de gestion et d'analyse pour la Direction SAMU.</p>
                            <ul class="feature-list">
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Tableau de bord général</strong><br><small class="text-muted">Vue d'ensemble nationale en temps réel</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Gestion des utilisateurs</strong><br><small class="text-muted">CRUD complet avec RBAC</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Gestion de la flotte</strong><br><small class="text-muted">Ajout, modification, suppression d'ambulances</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Configuration territoriale</strong><br><small class="text-muted">Communes et rattachement SAMU</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Modération</strong><br><small class="text-muted">Gestion des fausses alertes et blocages</small></div></li>
                                <li><i class="fa-solid fa-circle-check"></i><div><strong>Export de rapports PDF</strong><br><small class="text-muted">Rapports mensuels et statistiques</small></div></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                        <div style="background: linear-gradient(135deg, #1a3a5c, #0d3254); border-radius: 30px; padding: 3rem; color: white; text-align: center;">
                            <i class="fa-solid fa-chart-pie fa-6x mb-4" style="opacity: 0.3;"></i>
                            <h4 class="fw-bold mb-3">Pilotage stratégique</h4>
                            <p class="opacity-75 mb-4">Données fiables pour optimiser le déploiement de la flotte.</p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">100%</h3>
                                    <small class="opacity-75">visibilité</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">PDF</h3>
                                    <small class="opacity-75">rapports</small>
                                </div>
                                <div class="text-center">
                                    <h3 class="fw-bold mb-0">RBAC</h3>
                                    <small class="opacity-75">sécurité</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tech Stack Section -->
    <section class="content-section alt-bg">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Architecture technique</span>
                <h2 class="section-title">Des technologies modernes et éprouvées</h2>
            </div>
            <div class="row g-4">
    <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="0">
        <div class="tech-card">
            <div class="tech-icon"><i class="fa-brands fa-laravel"></i></div>
            <h6 class="fw-bold mb-1">Laravel</h6>
            <small class="text-muted">Backend & Frontend</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="100">
        <div class="tech-card">
            <div class="tech-icon"><i class="fa-solid fa-database"></i></div>
            <h6 class="fw-bold mb-1">MySQL</h6>
            <small class="text-muted">Base de données</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="200">
        <div class="tech-card">
            <div class="tech-icon"><i class="fa-brands fa-bootstrap"></i></div>
            <h6 class="fw-bold mb-1">Bootstrap 5</h6>
            <small class="text-muted">Interface utilisateur</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="300">
        <div class="tech-card">
            <div class="tech-icon"><i class="fa-solid fa-map-location-dot"></i></div>
            <h6 class="fw-bold mb-1">Leaflet.js</h6>
            <small class="text-muted">Cartographie</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="400">
        <div class="tech-card">
            <div class="tech-icon"><i class="fa-solid fa-chart-line"></i></div>
            <h6 class="fw-bold mb-1">Chart.js</h6>
            <small class="text-muted">Statistiques</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2" data-aos="fade-up" data-aos-delay="500">
        <div class="tech-card">
            <div class="tech-icon"><i class="fa-solid fa-lock"></i></div>
            <h6 class="fw-bold mb-1">Sessions & RBAC</h6>
            <small class="text-muted">Sécurité</small>
        </div>
    </div>
</div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <i class="fa-solid fa-heart-pulse fa-2x" style="color: var(--accent-red);"></i>
                        <h4 class="fw-bold mb-0">Secours<span style="color: var(--accent-red);">Bénin</span></h4>
                    </div>
                    <p class="opacity-75">Plateforme nationale de gestion des urgences médicales au Bénin.</p>
                    <div class="mt-3">
                        <a href="#" class="social-icon d-inline-flex"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="social-icon d-inline-flex"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="social-icon d-inline-flex"><i class="fa-brands fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5>Navigation</h5>
                    <a href="/">Accueil</a>
                    <a href="/a-propos">À propos</a>
                    <a href="/fonctionnalites">Fonctionnalités</a>
                    <a href="/partenaires">Partenaires</a>
                    <a href="/contact">Contact</a>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h5>Plateforme</h5>
                    <a href="/citoyen/auth">Espace Citoyen</a>
                    <a href="/regulateur/login">Espace Régulateur</a>
                    <a href="/ambulancier/login">Espace Ambulancier</a>
                    <a href="/admin/login">Espace Administrateur</a>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h5>Contact</h5>
                    <p class="mb-2 opacity-75"><i class="fa-solid fa-location-dot me-2" style="color: var(--accent-red);"></i>Cotonou, Bénin</p>
                    <p class="mb-2 opacity-75"><i class="fa-solid fa-phone me-2" style="color: var(--accent-red);"></i>+229 70 00 00 00</p>
                    <p class="mb-2 opacity-75"><i class="fa-solid fa-envelope me-2" style="color: var(--accent-red);"></i>contact@secoursbenin.bj</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; 2026 Secours Bénin - Projet du Gouvernement du Bénin</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        function showInterface(name) {
            document.querySelectorAll('.interface-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.interface-tab').forEach(el => el.classList.remove('active'));
            document.getElementById(name).classList.add('active');
            event.target.closest('.interface-tab').classList.add('active');
            AOS.refresh();
        }
    </script>
    @include('partials.pwa-register')
</body>
</html>