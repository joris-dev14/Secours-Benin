<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partenaires - Secours Bénin</title>
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
        
        .partner-card { background: white; border-radius: 20px; padding: 2.5rem; text-align: center; transition: all 0.4s; border: 2px solid transparent; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .partner-card:hover { transform: translateY(-10px); border-color: var(--accent-red); box-shadow: 0 20px 50px rgba(255, 82, 82, 0.1); }
        .partner-logo { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); color: white; display: flex; align-items: center; justify-content: center; font-size: 2.5rem; margin: 0 auto 1.5rem; transition: all 0.3s; }
        .partner-card:hover .partner-logo { transform: rotate(-10deg) scale(1.1); background: linear-gradient(135deg, var(--accent-red), #ff8a80); }
        
        .hospital-card { background: white; border-radius: 16px; padding: 1.5rem; display: flex; align-items: center; gap: 1.5rem; transition: all 0.3s; border-left: 4px solid var(--success-green); margin-bottom: 1rem; }
        .hospital-card:hover { transform: translateX(5px); box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .hospital-icon { width: 60px; height: 60px; border-radius: 15px; background: rgba(16, 185, 129, 0.1); color: var(--success-green); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0; }
        
        .cta-card { background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); border-radius: 30px; padding: 4rem 3rem; color: white; text-align: center; position: relative; overflow: hidden; }
        .cta-card::before { content: ''; position: absolute; top: -50%; right: -20%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255, 82, 82, 0.2), transparent); border-radius: 50%; }
        
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
                    <li class="nav-item"><a class="nav-link" href="/fonctionnalites">Fonctionnalités</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/partenaires">Partenaires</a></li>
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
                    <span class="section-badge" style="background: rgba(255,255,255,0.1); color: white;">Partenaires</span>
                    <h1>Unis pour sauver des vies</h1>
                    <p>Secours Bénin est le fruit d'une collaboration entre institutions publiques, établissements de santé et acteurs du numérique</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Institutional Partners -->
    <section class="content-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Partenaires institutionnels</span>
                <h2 class="section-title">Soutenu par les plus hautes autorités</h2>
                <p class="section-subtitle">Un projet d'envergure nationale porté par l'État béninois</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="0">
                    <div class="partner-card">
                        <div class="partner-logo"><i class="fa-solid fa-landmark"></i></div>
                        <h5 class="fw-bold">Présidence de la République</h5>
                        <p class="text-muted small">Soutien institutionnel et validation stratégique du projet</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="partner-card">
                        <div class="partner-logo"><i class="fa-solid fa-heart-pulse"></i></div>
                        <h5 class="fw-bold">Ministère de la Santé</h5>
                        <p class="text-muted small">Pilotage opérationnel et coordination avec le SAMU</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="partner-card">
                        <div class="partner-logo"><i class="fa-solid fa-laptop-code"></i></div>
                        <h5 class="fw-bold">Ministère du Numérique</h5>
                        <p class="text-muted small">Accompagnement technique et conformité digitale</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="partner-card">
                        <div class="partner-logo"><i class="fa-solid fa-shield-halved"></i></div>
                        <h5 class="fw-bold">APDP</h5>
                        <p class="text-muted small">Validation de la conformité protection des données</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SAMU Centers -->
    <section class="content-section alt-bg">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Centres SAMU pilotes</span>
                <h2 class="section-title">Les centres de régulation engagés</h2>
                <p class="section-subtitle">Déploiement initial sur le département du Littoral</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="partner-card">
                        <div class="partner-logo" style="background: linear-gradient(135deg, var(--accent-red), #ff8a80);">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <h5 class="fw-bold">SAMU Cotonou</h5>
                        <p class="text-muted small mb-3">Centre principal de coordination pour la capitale économique</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-primary bg-opacity-10 text-primary">68 ambulances</span>
                            <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="partner-card">
                        <div class="partner-logo" style="background: linear-gradient(135deg, var(--accent-red), #ff8a80);">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <h5 class="fw-bold">SAMU Abomey-Calavi</h5>
                        <p class="text-muted small mb-3">Couverture de la ville universitaire et ses environs</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-primary bg-opacity-10 text-primary">45 ambulances</span>
                            <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="partner-card">
                        <div class="partner-logo" style="background: linear-gradient(135deg, var(--accent-red), #ff8a80);">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <h5 class="fw-bold">SAMU Ouidah</h5>
                        <p class="text-muted small mb-3">Couverture de la ville historique et la Route des Pêches</p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge bg-primary bg-opacity-10 text-primary">25 ambulances</span>
                            <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Hospitals -->
    <section class="content-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Hôpitaux partenaires</span>
                <h2 class="section-title">Un réseau de soins intégré</h2>
                <p class="section-subtitle">Les établissements de santé qui accueillent les patients pris en charge</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="hospital-card">
                        <div class="hospital-icon"><i class="fa-solid fa-hospital"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">CHD Cotonou</h6>
                            <p class="text-muted small mb-0">Centre Hospitalier Départemental du Littoral</p>
                        </div>
                    </div>
                    <div class="hospital-card">
                        <div class="hospital-icon"><i class="fa-solid fa-hospital"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Hôpital de l'Amitié</h6>
                            <p class="text-muted small mb-0">Hôpital général de référence à Cotonou</p>
                        </div>
                    </div>
                    <div class="hospital-card">
                        <div class="hospital-icon"><i class="fa-solid fa-hospital"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">Clinique Les Grâces</h6>
                            <p class="text-muted small mb-0">Établissement privé conventionné</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="hospital-card">
                        <div class="hospital-icon"><i class="fa-solid fa-hospital"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">HGD Abomey-Calavi</h6>
                            <p class="text-muted small mb-0">Hôpital Général de Zone de l'Atlantique</p>
                        </div>
                    </div>
                    <div class="hospital-card">
                        <div class="hospital-icon"><i class="fa-solid fa-hospital"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">HZV Ouidah</h6>
                            <p class="text-muted small mb-0">Hôpital de Zone de la ville historique</p>
                        </div>
                    </div>
                    <div class="hospital-card">
                        <div class="hospital-icon"><i class="fa-solid fa-hospital"></i></div>
                        <div>
                            <h6 class="fw-bold mb-1">CNHU-HKM</h6>
                            <p class="text-muted small mb-0">Centre National Hospitalier et Universitaire</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Academic Partner -->
    <section class="content-section alt-bg">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6" data-aos="fade-right">
                    <div style="background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); border-radius: 30px; padding: 3rem; color: white; text-align: center;">
                        <i class="fa-solid fa-graduation-cap fa-5x mb-4" style="opacity: 0.3;"></i>
                        <h4 class="fw-bold mb-3">IUT Bénin</h4>
                        <p class="opacity-75 mb-4">Projet de fin d'études - Promotion 2025-2026</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <div class="text-center">
                                <h3 class="fw-bold mb-0">PFE</h3>
                                <small class="opacity-75">Projet</small>
                            </div>
                            <div class="text-center">
                                <h3 class="fw-bold mb-0">2026</h3>
                                <small class="opacity-75">Année</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <span class="section-badge">Partenaire académique</span>
                    <h2 class="section-title">Un projet d'excellence étudiante</h2>
                    <p class="text-muted lead">
                        Secours Bénin est développé dans le cadre d'un projet de fin d'études à l'IUT Bénin, 
                        illustrant l'excellence de la formation en génie logiciel et systèmes d'information.
                    </p>
                    <p class="text-muted">
                        Ce projet mobilise les compétences acquises en développement web, bases de données, 
                        sécurité informatique et gestion de projet, au service d'une cause d'intérêt public majeur.
                    </p>
                    <a href="/contact" class="btn btn-cta mt-3">
                        <i class="fa-solid fa-handshake me-2"></i>Devenir partenaire
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="content-section">
        <div class="container">
            <div class="cta-card" data-aos="fade-up">
                <div class="position-relative" style="z-index: 2;">
                    <h2 class="fw-bold mb-3">Vous souhaitez rejoindre l'aventure ?</h2>
                    <p class="lead opacity-75 mb-4">
                        Établissements de santé, collectivités territoriales, acteurs du numérique : 
                        contribuez à la modernisation des urgences médicales au Bénin.
                    </p>
                    <a href="/contact" class="btn btn-hero-primary" style="background: var(--accent-red); color: white; padding: 16px 32px; border-radius: 50px; font-weight: 700; text-decoration: none; display: inline-block;">
                        <i class="fa-solid fa-envelope me-2"></i>Nous contacter
                    </a>
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
    <script>AOS.init({ duration: 800, once: true });</script>
    @include('partials.pwa-register')
</body>
</html>