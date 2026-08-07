<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun | <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --brand-blue: #00529E;
            --brand-blue-hover: #003f7a;
            --brand-green: #528934;
            --dark-text: #0f172a;
            --muted-text: #64748b;
            --border-color: #e2e8f0;
            --input-bg: #ffffff;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Outfit', sans-serif;
            min-height: 100vh;
            background-color: #ffffff;
            color: var(--dark-text);
            overflow-x: hidden;
        }

        .login-wrapper {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ── LEFT COLUMN (Form Area) ── */
        .login-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem 3rem;
            background: #ffffff;
            position: relative;
            z-index: 2;
        }

        .login-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
        }

        .login-logo img {
            height: 38px;
            width: 38px;
            object-fit: contain;
        }

        .login-logo-text {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1;
        }

        .login-logo-text .t-blue { color: var(--brand-blue); }
        .login-logo-text .t-green { color: var(--brand-green); }

        .form-container {
            width: 100%;
            max-width: 420px;
            margin: 1.5rem auto;
        }

        .welcome-heading {
            font-size: 2.1rem;
            font-weight: 800;
            color: var(--dark-text);
            letter-spacing: -0.5px;
            margin-bottom: 0.4rem;
        }

        .welcome-subtitle {
            color: var(--muted-text);
            font-size: 0.95rem;
            margin-bottom: 1.75rem;
        }

        .form-label-custom {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--dark-text);
            margin-bottom: 0.4rem;
            display: block;
        }

        .form-input-custom {
            width: 100%;
            padding: 0.7rem 1rem;
            border-radius: 10px;
            border: 1.5px solid var(--border-color);
            background-color: var(--input-bg);
            font-family: 'Outfit', sans-serif;
            font-size: 0.93rem;
            color: var(--dark-text);
            transition: all 0.2s ease;
        }

        .form-input-custom:focus {
            outline: none;
            border-color: var(--brand-blue);
            box-shadow: 0 0 0 4px rgba(0, 82, 158, 0.1);
        }

        .password-wrap {
            position: relative;
        }

        .password-toggle-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .password-toggle-btn:hover {
            color: var(--dark-text);
        }

        .btn-signin-primary {
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 10px;
            border: none;
            background-color: var(--brand-blue);
            color: #ffffff;
            font-weight: 600;
            font-size: 1rem;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            transition: all 0.22s ease;
            box-shadow: 0 4px 14px rgba(0, 82, 158, 0.25);
            margin-top: 0.5rem;
            margin-bottom: 0.85rem;
        }

        .btn-signin-primary:hover {
            background-color: var(--brand-blue-hover);
            box-shadow: 0 6px 20px rgba(0, 82, 158, 0.35);
            transform: translateY(-1px);
        }

        .btn-google-signin {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1.5px solid var(--border-color);
            background: #ffffff;
            color: var(--dark-text);
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: 1.5rem;
        }

        .btn-google-signin:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: var(--dark-text);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .signup-footer-text {
            text-align: center;
            font-size: 0.9rem;
            color: var(--muted-text);
        }

        .signup-footer-link {
            color: var(--brand-blue);
            font-weight: 700;
            text-decoration: underline;
            margin-left: 0.25rem;
        }

        .signup-footer-link:hover {
            color: var(--brand-blue-hover);
        }

        .back-home-link {
            display: inline-block;
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--muted-text);
            text-decoration: none;
            transition: color 0.2s;
        }

        .back-home-link:hover {
            color: var(--brand-blue);
        }

        .alert-custom-error {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.88rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .alert-custom-success {
            background-color: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            font-size: 0.88rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .pw-strength-bar {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s;
            margin-top: 6px;
        }

        /* ── RIGHT COLUMN (Aesthetic Brand Panel) ── */
        .login-right {
            flex: 1.1;
            background: linear-gradient(135deg, rgba(0, 82, 158, 0.82) 0%, rgba(0, 40, 80, 0.9) 100%), url('<?= BASE_URL ?>/assets/img/wisata-kota-bogor.webp') left center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background illustrations & geometry */
        .glowing-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.55;
            mix-blend-mode: screen;
            pointer-events: none;
            z-index: 1;
        }

        .blob-1 {
            top: 10%;
            left: 10%;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, #528934 0%, transparent 70%);
            animation: floatBlob1 12s infinite alternate ease-in-out;
        }

        .blob-2 {
            bottom: 15%;
            right: 10%;
            width: 320px;
            height: 320px;
            background: radial-gradient(circle, #0082fa 0%, transparent 70%);
            animation: floatBlob2 15s infinite alternate ease-in-out;
        }

        .blob-3 {
            top: 45%;
            right: 25%;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, #ffffff 0%, transparent 70%);
            opacity: 0.25;
            animation: floatBlob3 10s infinite alternate ease-in-out;
        }

        .grid-overlay {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255, 255, 255, 0.12) 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 2;
        }

        /* Animations for organic float movement */
        @keyframes floatBlob1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 40px) scale(1.15); }
        }
        @keyframes floatBlob2 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(-40px, -20px) scale(0.9); }
        }
        @keyframes floatBlob3 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(20px, -30px) scale(1.2); }
        }

        @media (max-width: 991.98px) {
            .login-right {
                display: none;
            }
            .login-left {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">

    <!-- LEFT COLUMN: Register Form -->
    <div class="login-left">
        <div>
            <a href="<?= BASE_URL ?>/" class="login-logo">
                <img src="<?= BASE_URL ?>/assets/img/bulet.png" alt="SobatBogor Logo">
                <span class="login-logo-text">
                    <span class="t-blue">Sobat</span><span class="t-green">Bogor</span>
                </span>
            </a>
        </div>

        <div class="form-container">
            <h1 class="welcome-heading">Create an account</h1>
            <p class="welcome-subtitle">Buat akun untuk menjelajahi keindahan Bogor</p>

            <!-- Alerts -->
            <?php if (!empty($error)): ?>
            <div class="alert-custom-error">
                <i class="fas fa-exclamation-circle text-danger"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
            <div class="alert-custom-success">
                <i class="fas fa-check-circle text-success"></i>
                <span><?= htmlspecialchars($success) ?></span>
            </div>
            <?php endif; ?>

            <!-- Register Form -->
            <form action="<?= BASE_URL ?>/register" method="POST" onsubmit="return validateRegister()">
                
                <!-- Full Name -->
                <div class="mb-3">
                    <label for="name" class="form-label-custom">Nama Lengkap</label>
                    <input type="text" id="name" name="name" 
                           class="form-input-custom" 
                           placeholder="Masukkan nama Anda"
                           value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" 
                           required minlength="3">
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="reg-email" class="form-label-custom">Email address</label>
                    <input type="email" id="reg-email" name="email" 
                           class="form-input-custom" 
                           placeholder="nama@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" 
                           required>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="reg-password" class="form-label-custom">Password</label>
                    <div class="password-wrap">
                        <input type="password" id="reg-password" name="password" 
                               class="form-input-custom" 
                               placeholder="Min. 8 karakter" 
                               required minlength="8"
                               oninput="checkStrength(this.value)">
                        <i class="fas fa-eye password-toggle-btn" onclick="togglePw('reg-password', this)"></i>
                    </div>
                    <div id="strengthBar" class="pw-strength-bar" style="background:#e2e8f0;width:0%;"></div>
                    <p id="strengthLabel" style="font-size:0.75rem;color:#94a3b8;margin-top:3px;"></p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="confirm-password" class="form-label-custom">Konfirmasi Password</label>
                    <div class="password-wrap">
                        <input type="password" id="confirm-password" name="password_confirm" 
                               class="form-input-custom" 
                               placeholder="Ulangi kata sandi" 
                               required>
                        <i class="fas fa-eye password-toggle-btn" onclick="togglePw('confirm-password', this)"></i>
                    </div>
                    <div id="confirmPasswordError" class="text-danger small mt-1 d-none" style="font-weight:600;"><i class="fas fa-exclamation-circle me-1"></i>Password tidak cocok!</div>
                </div>

                <!-- Submit Register Button -->
                <button type="submit" class="btn-signin-primary">
                    Sign up
                </button>

                <!-- Social Sign Up with Google -->
                <a href="<?= BASE_URL ?>/auth/google" class="btn-google-signin">
                    <svg width="18" height="18" viewBox="0 0 48 48">
                        <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                        <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                        <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                        <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    </svg>
                    Sign up with Google
                </a>

            </form>

            <div class="signup-footer-text">
                Already have an account? 
                <a href="<?= BASE_URL ?>/login" class="signup-footer-link">Sign in</a>
            </div>
            
            <div class="text-center">
                <a href="<?= BASE_URL ?>/" class="back-home-link">
                    <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
                </a>
            </div>
        </div>

        <div></div>
    </div>

    <!-- RIGHT COLUMN: Aesthetic Brand Panel -->
    <div class="login-right">
        <!-- Glowing Geometric Blobs -->
        <div class="glowing-blob blob-1"></div>
        <div class="glowing-blob blob-2"></div>
        <div class="glowing-blob blob-3"></div>
        <!-- Dotted Pattern Overlay -->
        <div class="grid-overlay"></div>
    </div>

</div>

<script>
function togglePw(id, icon) {
    const input = document.getElementById(id);
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye');
    icon.classList.toggle('fa-eye-slash');
}

function checkStrength(val) {
    const bar   = document.getElementById('strengthBar');
    const label = document.getElementById('strengthLabel');
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        {w:'0%',   color:'#e2e8f0', text:''},
        {w:'25%',  color:'#ef4444', text:'Lemah'},
        {w:'50%',  color:'#f97316', text:'Cukup'},
        {w:'75%',  color:'#eab308', text:'Kuat'},
        {w:'100%', color:'#16a34a', text:'Sangat Kuat'},
    ];
    const lvl = levels[score] || levels[0];
    bar.style.width      = lvl.w;
    bar.style.background = lvl.color;
    label.textContent    = lvl.text;
    label.style.color    = lvl.color;
}

function validateRegister() {
    const pw  = document.getElementById('reg-password').value;
    const cpw = document.getElementById('confirm-password').value;
    const errEl = document.getElementById('confirmPasswordError');
    const inputEl = document.getElementById('confirm-password');

    if (pw !== cpw) {
        inputEl.style.borderColor = '#ef4444';
        inputEl.focus();
        errEl.classList.remove('d-none');
        return false;
    }
    return true;
}

// Clear error on input match
document.addEventListener("DOMContentLoaded", function() {
    const pwInput = document.getElementById('reg-password');
    const cpwInput = document.getElementById('confirm-password');
    const errEl = document.getElementById('confirmPasswordError');

    if (cpwInput && pwInput) {
        cpwInput.addEventListener('input', function() {
            if (this.value === pwInput.value) {
                this.style.borderColor = '';
                errEl.classList.add('d-none');
            }
        });
        pwInput.addEventListener('input', function() {
            if (cpwInput.value === this.value || cpwInput.value === '') {
                cpwInput.style.borderColor = '';
                errEl.classList.add('d-none');
            }
        });
    }
});
</script>
</body>
</html>
