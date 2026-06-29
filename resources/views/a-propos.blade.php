<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root { 
            --primary-blue: #0A2540; 
            --accent-red: #FF5252; 
            --bg-light: #F8F9FA; 
            --white: #FFFFFF; 
            --success-green: #10B981; 
        }
        * { scroll-behavior: smooth; }
        body { font-family: 'Poppins', sans-serif; color: var(--primary-blue); overflow-x: hidden; }
        
        .navbar-custom { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); box-shadow: 0 2px 20px rgba(0,0,0,0.05); padding: 1rem 0; }
        .logo-text { font-weight: 800; font-size: 1.5rem; color: var(--primary-blue); }
        .logo-accent { color: var(--accent-red); }
        .nav-link { font-weight: 600; color: var(--primary-blue) !important; margin: 0 0.5rem; position: relative; transition: all 0.3s; }
        .nav-link::after { content: ''; position: absolute; bottom: 0; left: 50%; width: 0; height: 2px; background: var(--accent-red); transition: all 0.3s; transform: translateX(-50%); }
        .nav-link:hover::after, .nav-link.active::after { width: 80%; }
        .nav-link:hover { color: var(--accent-red) !important; }
        .btn-cta { background: var(--accent-red); color: white; border: none; padding: 10px 24px; border-radius: 50px; font-weight: 700; transition: all 0.3s; }
        .btn-cta:hover { background: #ff3333; color: white; transform: translateY(-2px); }
        
        .page-header { background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); color: white; padding: 160px 0 100px; position: relative; overflow: hidden; }
        .page-header::before { content: ''; position: absolute; top: 0; right: 0; width: 600px; height: 600px; background: radial-gradient(circle, rgba(255, 82, 82, 0.15), transparent); border-radius: 50%; }
        .page-header h1 { font-size: 3.2rem; font-weight: 900; margin-bottom: 1rem; line-height: 1.2; }
        .section-badge { display: inline-block; background: rgba(255, 82, 82, 0.1); color: var(--accent-red); padding: 6px 16px; border-radius: 50px; font-size: 0.85rem; font-weight: 700; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px; }
        .section-title { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
        .section-subtitle { color: #64748b; font-size: 1.1rem; margin-bottom: 3rem; max-width: 700px; margin-left: auto; margin-right: auto; }
        
        .content-section { padding: 100px 0; }
        .content-section.alt-bg { background: var(--bg-light); }
        
        .about-card { background: white; border-radius: 20px; padding: 2.5rem; box-shadow: 0 10px 40px rgba(10, 37, 64, 0.08); height: 100%; border-top: 4px solid var(--accent-red); transition: all 0.3s; }
        .about-card:hover { transform: translateY(-5px); }
        .about-icon { width: 70px; height: 70px; border-radius: 18px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 1.5rem; }
        
        .timeline { position: relative; padding: 2rem 0; }
        .timeline::before { content: ''; position: absolute; left: 50%; top: 0; bottom: 0; width: 3px; background: linear-gradient(to bottom, var(--primary-blue), var(--accent-red)); transform: translateX(-50%); }
        .timeline-item { position: relative; margin-bottom: 3rem; }
        .timeline-content { background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 5px 20px rgba(0,0,0,0.05); position: relative; z-index: 2; }
        .timeline-dot { position: absolute; left: 50%; top: 2.5rem; width: 24px; height: 24px; background: var(--accent-red); border: 4px solid white; border-radius: 50%; transform: translateX(-50%); box-shadow: 0 0 0 4px rgba(255, 82, 82, 0.2); z-index: 3; }
        .timeline-year { display: inline-block; background: var(--primary-blue); color: white; padding: 4px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.5rem; }
        
        .values-card { background: white; border-radius: 20px; padding: 2rem; text-align: center; transition: all 0.3s; border: 2px solid transparent; height: 100%; }
        .values-card:hover { transform: translateY(-10px); border-color: var(--accent-red); box-shadow: 0 20px 50px rgba(255, 82, 82, 0.1); }
        .values-icon { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem; transition: all 0.3s; }
        .values-card:hover .values-icon { transform: rotate(-10deg) scale(1.1); background: linear-gradient(135deg, var(--accent-red), #ff8a80); }
        
        .footer { background: #051525; color: white; padding: 60px 0 20px; }
        .footer h5 { font-weight: 700; margin-bottom: 1.5rem; }
        .footer a { color: rgba(255,255,255,0.7); text-decoration: none; transition: all 0.3s; display: block; padding: 5px 0; }
        .footer a:hover { color: var(--accent-red); padding-left: 5px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; margin-top: 40px; text-align: center; color: rgba(255,255,255,0.5); font-size: 0.9rem; }
        .social-icon { width: 40px; height: 40px; border-radius: 50%; background: rgba(255,255,255,0.1); display: inline-flex; align-items: center; justify-content: center; margin-right: 8px; transition: all 0.3s; }
        .social-icon:hover { background: var(--accent-red); transform: translateY(-3px); }
        
        @media (max-width: 768px) {
            .page-header { padding: 120px 0 60px; }
            .page-header h1 { font-size: 2rem; }
            .section-title { font-size: 1.8rem; }
            .timeline::before { left: 20px; }
            .timeline-dot { left: 20px; }
            .timeline-item { padding-left: 50px; }
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
                    <li class="nav-item"><a class="nav-link active" href="/a-propos">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="/fonctionnalites">Fonctionnalités</a></li>
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
                    <span class="section-badge" style="background: rgba(255,255,255,0.15); color: white; border: 1px solid rgba(255,255,255,0.2);">À propos du projet</span>
                    <h1>Moderniser les urgences médicales<br>au cœur de l'Afrique</h1>
                    <p class="lead opacity-75">Un projet stratégique de fin d'études (IUT Bénin, 2025-2026) pour sauver des vies grâce au numérique, en accompagnant la modernisation de la flotte ambulancière de l'État.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Context Section -->
    <section class="content-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <span class="section-badge">Le Contexte</span>
                    <h2 class="section-title">Pourquoi Secours Bénin ?</h2>
                    <p class="text-muted lead">
                        Le Bénin fait face à un défi majeur dans la gestion des urgences médicales. Le système actuel repose sur des numéros verts attribués par commune, générant des délais significatifs dans une situation où chaque minute compte pour sauver des vies.
                    </p>
                    <p class="text-muted">
                        En <strong>juin 2024</strong>, le gouvernement béninois a réceptionné un premier lot de <strong>144 ambulances de dernière génération</strong> (sur une commande nationale de 188 unités). Cette modernisation de la flotte constitue une opportunité historique, mais la présence de véhicules neufs ne suffit pas sans un outil numérique intelligent pour coordonner leurs interventions.
                    </p>
                    <p class="text-muted">
                        <strong>Secours Bénin</strong> vise à combler ce vide en développant une plateforme web complète couvrant l'ensemble de la chaîne de secours : du signalement par le citoyen jusqu'à l'intervention de l'ambulancier, en passant par la régulation SAMU.
                    </p>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-card">
                        <div class="about-icon" style="background: rgba(255, 82, 82, 0.1); color: var(--accent-red);">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Notre Mission</h4>
                        <p class="text-muted mb-4">
                            Concevoir et développer une plateforme web unique et responsive permettant de réduire significativement le temps de réponse des ambulances et d'améliorer la coordination entre le SAMU et les hôpitaux, de manière sécurisée et conforme à la loi.
                        </p>
                        <div class="about-icon" style="background: rgba(10, 37, 64, 0.1); color: var(--primary-blue);">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Notre Vision</h4>
                        <p class="text-muted mb-0">
                            Devenir la référence en matière de gestion numérique des urgences médicales au Bénin, en fournissant un outil performant, accessible sans installation (PWA) et respectueux des données personnelles, avec une vision d'extension à l'ensemble du territoire national.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Objectives Section -->
    <section class="content-section alt-bg">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Objectifs spécifiques</span>
                <h2 class="section-title">Une solution pour chaque acteur</h2>
                <p class="section-subtitle">La plateforme couvre l'architecture complète : Citoyen → Centre de régulation SAMU → Ambulancier</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-mobile-screen-button"></i></div>
                        <h5 class="fw-bold">Citoyen</h5>
                        <p class="text-muted small">Signaler une urgence en quelques secondes avec transmission automatique du GPS et d'une photo anti-fraude, et suivre l'arrivée de l'ambulance en temps réel.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-headset"></i></div>
                        <h5 class="fw-bold">Régulateur SAMU</h5>
                        <p class="text-muted small">Recevoir les alertes avec alarme sonore/visuelle, visualiser la scène, et dispatcher l'ambulance la plus proche en un seul clic.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-truck-medical"></i></div>
                        <h5 class="fw-bold">Ambulancier</h5>
                        <p class="text-muted small">Recevoir la mission avec itinéraire GPS optimisé, et mettre à jour le statut (départ, arrivée, fin) avec horodatage automatique.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-user-shield"></i></div>
                        <h5 class="fw-bold">Administrateur</h5>
                        <p class="text-muted small">Gérer les comptes, la flotte, les communes et consulter les statistiques de performance via une plateforme unifiée avec contrôle d'accès par rôle (RBAC).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline Section -->
    <section class="content-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Chronologie</span>
                <h2 class="section-title">Les étapes clés du projet</h2>
                <p class="section-subtitle">Un déploiement progressif et réfléchi, ancré dans la réalité du terrain</p>
            </div>
            <div class="timeline">
                <div class="timeline-item" data-aos="fade-up">
                    <div class="timeline-dot"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="timeline-content">
                                <span class="timeline-year">Juin 2024</span>
                                <h5 class="fw-bold">Modernisation de la flotte</h5>
                                <p class="text-muted mb-0">Le gouvernement béninois reçoit un premier lot de 144 ambulances de dernière génération, marquant le début de la modernisation du SAMU et créant le besoin d'un outil de coordination numérique.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="100">
                    <div class="timeline-dot"></div>
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="timeline-content">
                                <span class="timeline-year">2025 - 2026</span>
                                <h5 class="fw-bold">Conception & Développement (PFE)</h5>
                                <p class="text-muted mb-0">Projet de fin d'études à l'IUT Bénin. Études de terrain, conception UML, développement des interfaces PWA (React.js) et de l'API backend (Node.js/PostgreSQL).</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="200">
                    <div class="timeline-dot"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="timeline-content">
                                <span class="timeline-year">Phase Pilote</span>
                                <h5 class="fw-bold">Déploiement dans le Littoral</h5>
                                <p class="text-muted mb-0">Déploiement initial prévu sur les communes du département du Littoral : <strong>Cotonou, Abomey-Calavi et Ouidah</strong>, avec formation des régulateurs et ambulanciers.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="timeline-item" data-aos="fade-up" data-aos-delay="300">
                    <div class="timeline-dot"></div>
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <div class="timeline-content">
                                <span class="timeline-year">Perspectives</span>
                                <h5 class="fw-bold">Extension nationale & IA</h5>
                                <p class="text-muted mb-0">Couverture de l'ensemble du territoire béninois, avec des évolutions futures comme la prédiction de la demande par intelligence artificielle et l'intégration aux dossiers médicaux électroniques.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values & Compliance Section -->
    <section class="content-section alt-bg">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Nos engagements</span>
                <h2 class="section-title">Des valeurs fortes et une conformité totale</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-heart"></i></div>
                        <h5 class="fw-bold">Humanité</h5>
                        <p class="text-muted small">Chaque vie compte. Notre technologie est entièrement au service de l'humain et de la rapidité des secours.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-bolt"></i></div>
                        <h5 class="fw-bold">Rapidité</h5>
                        <p class="text-muted small">Objectif : envoi d'alerte en moins de 3 secondes et réception au SAMU en moins de 5 secondes, même en réseau 2G/3G.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <h5 class="fw-bold">Sécurité & Conformité</h5>
                        <p class="text-muted small">Respect strict de la <strong>loi n°2017-20</strong> portant code du numérique et des directives de l'<strong>APDP</strong> (consentement explicite, suppression automatique des photos).</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="values-card">
                        <div class="values-icon"><i class="fa-solid fa-handshake"></i></div>
                        <h5 class="fw-bold">Transparence</h5>
                        <p class="text-muted small">Traçabilité complète des actions, horodatage des statuts et statistiques fiables pour une gestion optimale de la flotte.</p>
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
                    <p class="opacity-75">Plateforme nationale de gestion des urgences médicales au Bénin. Projet de fin d'études - IUT Bénin (2025-2026).</p>
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
                <p class="mb-0">&copy; 2026 Secours Bénin - Projet du Gouvernement du Bénin • Conforme Loi n°2017-20 portant Code du Numérique</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
    </script>
    @include('partials.pwa-register')
</body>
</html>