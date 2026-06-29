<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Alerte - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); padding-bottom: 2rem; }
        .header { background: var(--primary-blue); color: white; padding: 1.5rem 1rem 2.5rem; border-radius: 0 0 24px 24px; margin-bottom: -1.5rem; }
        .main-card { background: white; border-radius: 24px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(10, 37, 64, 0.08); }
        .gps-indicator { display: inline-flex; align-items: center; gap: 8px; background: rgba(10, 37, 64, 0.05); padding: 8px 16px; border-radius: 50px; font-size: 0.9rem; font-weight: 600; }
        .pulse { width: 10px; height: 10px; background: #10B981; border-radius: 50%; position: relative; }
        .pulse::after { content: ''; position: absolute; width: 100%; height: 100%; top: 0; left: 0; background: #10B981; border-radius: 50%; animation: pulse-animation 1.5s infinite; }
        @keyframes pulse-animation { 0% { transform: scale(1); opacity: 1; } 100% { transform: scale(3); opacity: 0; } }
        
        .photo-upload { border: 2px dashed #cbd5e1; border-radius: 16px; padding: 2rem; text-align: center; cursor: pointer; transition: all 0.3s; background: var(--bg-light); }
        .photo-upload:hover { border-color: var(--primary-blue); background: rgba(10, 37, 64, 0.02); }
        .photo-upload i { font-size: 2rem; color: var(--primary-blue); margin-bottom: 0.5rem; }
        .photo-preview { max-width: 100%; border-radius: 12px; margin-top: 1rem; display: none; }
        
        .btn-send { background-color: var(--accent-red); color: white; border: none; min-height: 56px; border-radius: 16px; font-weight: 700; font-size: 1.1rem; width: 100%; transition: all 0.3s; box-shadow: 0 4px 15px rgba(255, 82, 82, 0.3); }
        .btn-send:hover { background-color: #ff3333; transform: translateY(-2px); }
        .form-select, .form-control { border-radius: 12px; border: 2px solid #e2e8f0; padding: 12px; min-height: 52px; }
        .form-select:focus, .form-control:focus { border-color: var(--primary-blue); box-shadow: 0 0 0 4px rgba(10, 37, 64, 0.1); }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="header text-center">
        <h4 class="fw-bold mb-1">Signaler une urgence</h4>
        <p class="mb-0 opacity-75 small">Chaque seconde compte</p>
    </div>

    <div class="container">
        <form id="alerteForm" method="POST" action="/citoyen/nouvelle-alerte" enctype="multipart/form-data">
            @csrf
            <div class="main-card">
                <!-- Étape 1 : Commune & GPS -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">1. Localisation</label>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <select class="form-select mb-3" id="departement" name="departement">
                        <option value="" selected disabled>Sélectionnez votre département</option>
                        @foreach($departements as $departement => $communes)
                            <option value="{{ $departement }}" {{ old('departement') == $departement ? 'selected' : '' }}>
                                {{ $departement }} ({{ $communes->join(', ') }})
                            </option>
                        @endforeach
                    </select>
                    <select class="form-select mb-3" id="commune" name="commune" {{ old('departement') ? '' : 'disabled' }}>
                        <option value="" selected disabled>{{ old('departement') ? 'Sélectionnez votre commune' : 'Choisissez d\'abord un département' }}</option>
                    </select>
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <div class="gps-indicator text-success" id="gpsStatus">
                        <div class="pulse"></div>
                        <span id="gpsText">Acquisition GPS en cours...</span>
                    </div>
                </div>

                <!-- Étape 2 : Photo -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">2. Photo de la scène (Obligatoire)</label>
                    <div class="photo-upload" onclick="document.getElementById('photoInput').click()">
                        <i class="fa-solid fa-camera"></i>
                        <p class="mb-0 fw-semibold small">Appuyez pour prendre une photo</p>
                        <p class="text-muted small mb-0">La caméra s'ouvrira directement</p>
                        <input type="file" id="photoInput" name="photo" accept="image/*" capture="environment" class="d-none" onchange="previewPhoto(this)">
                        <img id="photoPreview" class="photo-preview" alt="Aperçu">
                    </div>
                </div>

                <!-- Consentement Photo -->
                <div class="alert alert-info d-flex align-items-start gap-2 small" role="alert" style="border-radius: 12px; border: none; background: rgba(10, 37, 64, 0.05); color: var(--primary-blue);">
                    <i class="fa-solid fa-circle-info mt-1"></i>
                    <span>Cette photo sera transmise <strong>uniquement</strong> au régulateur SAMU pour évaluer l'urgence. Elle sera supprimée après l'intervention (Loi n°2017-20 du Bénin).</span>
                </div>

                <!-- Étape 3 : Description -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">3. Description (Optionnel)</label>
                    <textarea class="form-control" name="description" rows="3" placeholder="Décrivez brièvement la situation (ex: accident de moto, personne inconsciente...)"></textarea>
                </div>

                <!-- Bouton d'envoi -->
                <button type="button" class="btn btn-send" id="sendBtn" onclick="sendAlert()">
                    <span id="btnText"><i class="fa-solid fa-paper-plane me-2"></i> ENVOYER L'ALERTE</span>
                    <span id="btnSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-2" role="status"></span> Compression et envoi...</span>
                </button>
            </div>
        </form>
    </div>
    <script>
        // GPS réel
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                    document.getElementById('gpsText').textContent =
                        "GPS localisé : " + position.coords.latitude.toFixed(4) + "° N, " + position.coords.longitude.toFixed(4) + "° E";
                },
                () => {
                    document.getElementById('gpsText').textContent = "GPS non disponible";
                }
            );
        }

        const communesByDepartement = @json($departements->map(function ($items) {
            return $items->all();
        }));
        const oldDepartement = '{{ old('departement', '') }}';
        const oldCommune = '{{ old('commune', '') }}';

        function populateCommunes(departement, selectedCommune = '') {
            const communeSelect = document.getElementById('commune');
            communeSelect.innerHTML = '<option value="" selected disabled>Sélectionnez votre commune</option>';
            communeSelect.disabled = !departement;

            if (!departement || !communesByDepartement[departement]) {
                communeSelect.innerHTML = '<option value="" selected disabled>Choisissez d\'abord un département</option>';
                return;
            }

            communesByDepartement[departement].forEach((commune) => {
                const option = document.createElement('option');
                option.value = commune;
                option.textContent = commune;
                if (commune === selectedCommune) {
                    option.selected = true;
                }
                communeSelect.appendChild(option);
            });
        }

        document.getElementById('departement').addEventListener('change', function () {
            populateCommunes(this.value);
        });

        if (oldDepartement) {
            document.getElementById('departement').value = oldDepartement;
            populateCommunes(oldDepartement, oldCommune);
        }

        function previewPhoto(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('photoPreview');
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function sendAlert() {
            const departement = document.getElementById('departement').value;
            const commune = document.getElementById('commune').value;
            const photo = document.getElementById('photoInput').files.length;

            if (!departement) { alert("Veuillez sélectionner un département."); return; }
            if (!commune) { alert("Veuillez sélectionner une commune."); return; }
            if (!photo) { alert("La photo de la scène est obligatoire pour valider l'urgence."); return; }

            const btn = document.getElementById('sendBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');

            document.getElementById('alerteForm').submit();
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.pwa-register')
</body>
</html>