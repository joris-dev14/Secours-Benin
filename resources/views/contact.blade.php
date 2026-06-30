<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
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
        
        .content-section { padding: 80px 0; }
        
        .contact-card { background: white; border-radius: 20px; padding: 2rem; text-align: center; transition: all 0.3s; border: 2px solid transparent; height: 100%; box-shadow: 0 5px 20px rgba(0,0,0,0.03); }
        .contact-card:hover { transform: translateY(-10px); border-color: var(--accent-red); box-shadow: 0 20px 50px rgba(255, 82, 82, 0.1); }
        .contact-icon { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); color: white; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 1.5rem; transition: all 0.3s; }
        .contact-card:hover .contact-icon { background: linear-gradient(135deg, var(--accent-red), #ff8a80); transform: rotate(-10deg) scale(1.1); }
        
        .form-card { background: white; border-radius: 20px; padding: 3rem; box-shadow: 0 10px 40px rgba(10, 37, 64, 0.08); }
        .form-control, .form-select { border-radius: 12px; border: 2px solid #e2e8f0; padding: 14px; min-height: 52px; transition: all 0.3s; }
        .form-control:focus, .form-select:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(10, 37, 64, 0.1); }
        .form-label { font-weight: 600; color: var(--primary-blue); margin-bottom: 0.5rem; }
        .btn-submit { background: var(--accent-red); color: white; border: none; min-height: 56px; border-radius: 12px; font-weight: 700; font-size: 1.1rem; width: 100%; transition: all 0.3s; box-shadow: 0 4px 15px rgba(255, 82, 82, 0.3); }
        .btn-submit:hover { background: #ff3333; transform: translateY(-2px); color: white; }
        
        .info-card { background: linear-gradient(135deg, var(--primary-blue), #1a3a5c); border-radius: 20px; padding: 3rem; color: white; height: 100%; position: relative; overflow: hidden; }
        .info-card::before { content: ''; position: absolute; bottom: -50%; right: -30%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255, 82, 82, 0.2), transparent); border-radius: 50%; }
        .info-item { display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 2rem; position: relative; z-index: 2; }
        .info-icon { width: 50px; height: 50px; border-radius: 12px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        
        .map-container { height: 400px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(10, 37, 64, 0.1); }
        
        .emergency-card { background: linear-gradient(135deg, var(--accent-red), #ff8a80); border-radius: 20px; padding: 2rem; color: white; text-align: center; }
        .emergency-card h4 { font-weight: 800; font-size: 2rem; margin-bottom: 0.5rem; }
        
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
                    <li class="nav-item"><a class="nav-link" href="/partenaires">Partenaires</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/contact">Contact</a></li>
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
                    <span class="section-badge" style="background: rgba(255,255,255,0.1); color: white;">Contact</span>
                    <h1>Parlons de votre projet<br>ou de votre urgence</h1>
                    <p>Notre équipe est à votre disposition pour toute question ou demande d'information</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Emergency Notice -->
    <section class="content-section" style="padding: 40px 0;">
        <div class="container">
            <div class="emergency-card" data-aos="fade-up">
                <i class="fa-solid fa-phone-volume fa-2x mb-2"></i>
                <h4>URGENCE MÉDICALE ?</h4>
                <p class="mb-3">N'utilisez pas ce formulaire. Appelez immédiatement le numéro d'urgence ou utilisez la plateforme.</p>
                <a href="/citoyen/auth" class="btn btn-light fw-bold px-4 py-2" style="border-radius: 50px;">
                    <i class="fa-solid fa-bolt me-2"></i>Signaler une urgence
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Methods -->
    <section class="content-section" style="padding-top: 0;">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fa-solid fa-phone"></i></div>
                        <h5 class="fw-bold">Téléphone</h5>
                        <p class="text-muted mb-2">Du lundi au vendredi, 8h-18h</p>
                        <a href="tel:+22970000000" class="fw-bold text-decoration-none" style="color: var(--primary-blue);">+229 70 00 00 00</a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fa-solid fa-envelope"></i></div>
                        <h5 class="fw-bold">Email</h5>
                        <p class="text-muted mb-2">Réponse sous 24h ouvrées</p>
                        <a href="mailto:contact@secoursbenin.bj" class="fw-bold text-decoration-none" style="color: var(--primary-blue);">contact@secoursbenin.bj</a>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-card">
                        <div class="contact-icon"><i class="fa-solid fa-location-dot"></i></div>
                        <h5 class="fw-bold">Adresse</h5>
                        <p class="text-muted mb-2">Siège du projet</p>
                        <strong style="color: var(--primary-blue);">Cotonou, Bénin</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Form + Info -->
    <section class="content-section" style="padding-top: 0;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-7" data-aos="fade-right">
                    <div class="form-card">
                        <span class="section-badge">Formulaire de contact</span>
                        <h3 class="fw-bold mb-4">Envoyez-nous un message</h3>
                        <form id="contactForm" method="POST" action="{{ url('/contact') }}">
                            @csrf
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nom complet *</label>
                                    <input type="text" class="form-control" name="nom" required placeholder="Votre nom">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email *</label>
                                    <input type="email" class="form-control" name="email" required placeholder="votre@email.com">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" name="telephone" placeholder="+229 XX XX XX XX">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Sujet *</label>
                                    <select class="form-select" name="sujet" required>
                                        <option value="">Sélectionnez...</option>
                                        <option>Demande d'information</option>
                                        <option>Partenariat</option>
                                        <option>Support technique</option>
                                        <option>Presse / Médias</option>
                                        <option>Autre</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Message *</label>
                                    <textarea class="form-control" name="message" rows="5" required placeholder="Votre message..."></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="consent" id="consentCheck" required>
                                        <label class="form-check-label small" for="consentCheck">
                                            J'accepte que mes données soient traitées conformément à la <a href="{{ url('/mentions-legales') }}" style="color: var(--accent-red);">politique de confidentialité</a> *
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-submit" id="submitBtn">
                                        <i class="fa-solid fa-paper-plane me-2"></i>Envoyer le message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-5" data-aos="fade-left">
                    <div class="info-card">
                        <h4 class="fw-bold mb-4 position-relative" style="z-index: 2;">Informations de contact</h4>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-building"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1">Organisation</h6>
                                <p class="mb-0 opacity-75">Secours Bénin<br>Projet du Gouvernement du Bénin</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1">Adresse</h6>
                                <p class="mb-0 opacity-75">Cotonou, Département du Littoral<br>République du Bénin</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1">Téléphone</h6>
                                <p class="mb-0 opacity-75">+229 70 00 00 00<br>Lun-Ven : 8h-18h</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-envelope"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1">Email</h6>
                                <p class="mb-0 opacity-75">contact@secoursbenin.bj<br>support@secoursbenin.bj</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-clock"></i></div>
                            <div>
                                <h6 class="fw-bold mb-1">Plateforme</h6>
                                <p class="mb-0 opacity-75">Disponible 24h/24 et 7j/7<br>pour les urgences médicales</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 position-relative" style="z-index: 2;">
                            <h6 class="fw-bold mb-3">Suivez-nous</h6>
                            <div>
                                <a href="#" class="social-icon d-inline-flex" style="background: rgba(255,255,255,0.1);"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="#" class="social-icon d-inline-flex" style="background: rgba(255,255,255,0.1);"><i class="fa-brands fa-twitter"></i></a>
                                <a href="#" class="social-icon d-inline-flex" style="background: rgba(255,255,255,0.1);"><i class="fa-brands fa-linkedin-in"></i></a>
                                <a href="#" class="social-icon d-inline-flex" style="background: rgba(255,255,255,0.1);"><i class="fa-brands fa-youtube"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map -->
    <section class="content-section" style="padding-top: 0;">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="section-badge">Localisation</span>
                <h2 class="section-title">Nous trouver</h2>
            </div>
            <div data-aos="fade-up">
                <div id="map" class="map-container"></div>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });
        
        // Map
        const map = L.map('map').setView([6.3654, 2.4183], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
            attribution: '© OpenStreetMap' 
        }).addTo(map);
        L.marker([6.3654, 2.4183]).addTo(map)
            .bindPopup("<strong>Secours Bénin</strong><br>Siège du projet<br>Cotonou, Bénin")
            .openPopup();
        
        // Form submission
        function submitForm(e) {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi en cours...';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.innerHTML = '<i class="fa-solid fa-check me-2"></i>Message envoyé !';
                btn.style.background = 'var(--success-green)';
                
                setTimeout(() => {
                    alert('Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
                    document.getElementById('contactForm').reset();
                    btn.innerHTML = originalText;
                    btn.style.background = '';
                    btn.disabled = false;
                }, 1500);
            }, 2000);
        }
    </script>
    @include('partials.pwa-register')
</body>
</html>