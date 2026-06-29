<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Secours Bénin - Authentification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --primary-blue: #0A2540; --accent-red: #FF5252; --bg-light: #F8F9FA; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--primary-blue); height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { background: white; border-radius: 24px; box-shadow: 0 10px 40px rgba(10, 37, 64, 0.08); padding: 2rem; width: 100%; max-width: 400px; border: none; }
        .btn-primary-custom { background-color: var(--primary-blue); color: white; border: none; min-height: 52px; border-radius: 12px; font-weight: 600; transition: all 0.3s ease; }
        .btn-primary-custom:hover { background-color: #0d3254; transform: translateY(-2px); }
        .otp-input { width: 50px; height: 56px; text-align: center; font-size: 1.5rem; font-weight: 700; border: 2px solid #e0e0e0; border-radius: 12px; color: var(--primary-blue); transition: all 0.2s; }
        .otp-input:focus { border-color: var(--primary-blue); outline: none; box-shadow: 0 0 0 4px rgba(10, 37, 64, 0.1); }
        .phone-input-group { border: 2px solid #e0e0e0; border-radius: 12px; overflow: hidden; }
        .phone-prefix { background: var(--bg-light); border: none; font-weight: 600; color: var(--primary-blue); padding: 0 15px; display: flex; align-items: center; }
        .phone-field { border: none; min-height52px; }
        .phone-field:focus { box-shadow: none; }
        .hidden { display: none !important; }
    </style>
    @include('partials.pwa-head')
</head>
<body>
    <div class="auth-card text-center">
        <div class="mb-4">
            <i class="fa-solid fa-heart-pulse fa-3x" style="color: var(--accent-red);"></i>
            <h2 class="mt-3 fw-bold">Secours Bénin</h2>
            <p class="text-muted">Votre sécurité, notre priorité.</p>
        </div>

       <!-- Étape 1 : Téléphone -->
        <div id="step-phone">
            <label class="form-label text-start fw-semibold d-block mb-2">Numéro de téléphone</label>
            <div class="input-group mb-4 phone-input-group">
                <span class="phone-prefix"><img src="https://flagcdn.com/w20/bj.png" class="me-2" alt="BJ"> +229</span>
                <input type="tel" id="phone" class="form-control phone-field" placeholder="01 XX XX XX XX" maxlength="14" required>
            </div>
            <div id="error-phone" class="alert alert-danger mb-3 d-none" style="border-radius: 10px;"></div>
            <button  class="btn btn-primary-custom w-100" onclick="sendOTP()">
                Recevoir le code <i class="fa-solid fa-arrow-right ms-2"></i>
            </button>
        </div>

        <!-- Étape 2 : OTP -->
        <div id="step-otp" class="hidden">
            <p class="text-muted mb-4">Entrez le code à 4 chiffres envoyé au <span id="display-phone" class="fw-bold"></span></p>
            <div id="error-otp" class="alert alert-danger mb-3 d-none" style="border-radius: 10px;"></div>
            <div class="d-flex justify-content-center gap-2 mb-4">
                <input type="text" class="otp-input" maxlength="1" oninput="moveFocus(this, 1)">
                <input type="text" class="otp-input" maxlength="1" oninput="moveFocus(this, 2)">
                <input type="text" class="otp-input" maxlength="1" oninput="moveFocus(this, 3)">
                <input type="text" class="otp-input" maxlength="1" oninput="checkOTP(this)">
            </div>
            <button class="btn btn-primary-custom w-100" onclick="validateOTP()">
                Valider <i class="fa-solid fa-check ms-2"></i>
            </button>
            <p class="mt-3 text-muted small">Renvoyer le code dans <span id="timer">30</span>s</p>
        </div>

    <script>
    const csrfToken = '{{ csrf_token() }}';

    async function sendOTP() {
        const phone = document.getElementById('phone').value;
        if (phone.length < 10) {
            document.getElementById('error-phone').textContent = "Veuillez entrer un numéro valide.";
            document.getElementById('error-phone').classList.remove('d-none');
            return;
        }

        const response = await fetch('/otp/envoyer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ telephone: phone })
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('display-phone').textContent = "+229 " + phone;
            document.getElementById('step-phone').classList.add('hidden');
            document.getElementById('step-otp').classList.remove('hidden');
            document.querySelector('.otp-input').focus();

            // Affiche le code OTP pour les tests
            alert('Code OTP (test) : ' + data.code);

            // Timer 30s
            let seconds = 30;
            const interval = setInterval(() => {
                seconds--;
                document.getElementById('timer').textContent = seconds;
                if (seconds <= 0) clearInterval(interval);
            }, 1000);
        } else {
            document.getElementById('error-phone').textContent = "Erreur lors de l'envoi.";
            document.getElementById('error-phone').classList.remove('d-none');
        }
    }

    function moveFocus(current, nextIndex) {
        if (current.value.length === 1) {
            const inputs = document.querySelectorAll('.otp-input');
            if (inputs[nextIndex]) inputs[nextIndex].focus();
        }
    }

    function checkOTP(current) {
        if (current.value.length === 1) {
            const inputs = document.querySelectorAll('.otp-input');
            const allFilled = Array.from(inputs).every(input => input.value.length === 1);
            if (allFilled) validateOTP();
        }
    }

    async function validateOTP() {
        const inputs = document.querySelectorAll('.otp-input');
        const code = Array.from(inputs).map(i => i.value).join('');

        if (code.length < 4) {
            document.getElementById('error-otp').textContent = "Veuillez entrer le code complet.";
            document.getElementById('error-otp').classList.remove('d-none');
            return;
        }

        const response = await fetch('/otp/valider', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ code: code })
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = '/citoyen/consentement';
        } else {
            document.getElementById('error-otp').textContent = data.message;
            document.getElementById('error-otp').classList.remove('d-none');
        }
    }
</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.pwa-register')
</body>
</html>