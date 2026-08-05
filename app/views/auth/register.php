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
    <style>
        :root {
            --primary:#ea580c; --primary-dark:#c2410c;
            --secondary:#16a34a; --dark:#0f172a;
            --gray-600:#475569; --gray-400:#94a3b8;
            --border:#e2e8f0;
        }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a2a0d 60%, #0a1a2e 100%);
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            position: relative; overflow-x: hidden;
        }
        body::before {
            content: ''; position: fixed;
            top: -120px; left: -120px;
            width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(234,88,12,0.18) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        body::after {
            content: ''; position: fixed;
            bottom: -100px; right: -100px;
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(22,163,74,0.15) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .auth-card {
            background: rgba(255,255,255,0.97);
            border-radius: 24px;
            padding: 2.25rem 2.25rem 2rem;
            width: 100%; max-width: 460px;
            box-shadow: 0 32px 64px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
            position: relative; z-index: 1;
        }
        .form-control {
            font-family: 'Outfit', sans-serif;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(234,88,12,0.12);
        }
        .form-label { font-weight: 600; font-size: 0.88rem; color: #334155; margin-bottom: 0.4rem; }
        .btn-register {
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.8rem;
            font-weight: 700;
            font-size: 1rem;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Outfit', sans-serif;
        }
        .btn-register:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(234,88,12,0.3); }
        .input-icon { position: relative; }
        .input-icon i.ico { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.9rem; z-index:1; }
        .input-icon .form-control { padding-left: 2.5rem; }
        .toggle-pw { position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:#94a3b8; font-size:0.9rem; z-index:1; }
        .pw-strength-bar { height:4px; border-radius:2px; transition:all 0.3s; margin-top:6px; }
    </style>
</head>
<body>
<div class="auth-card">

    <!-- Brand -->
    <div class="text-center mb-4">
        <a href="<?= BASE_URL ?>/" style="text-decoration:none;">
            <div style="display:inline-flex;align-items:center;gap:0.6rem;">
                <div style="width:44px;height:44px;border-radius:12px;background:var(--primary);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-compass" style="color:#fff;font-size:1.2rem;"></i>
                </div>
                <span style="font-size:1.5rem;font-weight:800;color:var(--primary);">SobatBogor</span>
            </div>
        </a>
        <h1 style="font-size:1.35rem;font-weight:700;color:#0f172a;margin-top:1.25rem;margin-bottom:0.25rem;">
            Buat Akun Baru
        </h1>
        <p style="color:#64748b;font-size:0.9rem;">Bergabung dan mulai jelajahi wisata Bogor</p>
    </div>

    <!-- Error/Success Alert -->
    <?php if (!empty($error)): ?>
    <div style="background:#fee2e2;border:1.5px solid #fca5a5;border-radius:12px;padding:0.85rem 1rem;margin-bottom:1.25rem;display:flex;align-items:flex-start;gap:0.65rem;">
        <i class="fas fa-exclamation-circle" style="color:#ef4444;flex-shrink:0;margin-top:2px;"></i>
        <span style="color:#b91c1c;font-size:0.88rem;line-height:1.4;"><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
    <div style="background:#dcfce7;border:1.5px solid #86efac;border-radius:12px;padding:0.85rem 1rem;margin-bottom:1.25rem;display:flex;align-items:flex-start;gap:0.65rem;">
        <i class="fas fa-check-circle" style="color:#16a34a;flex-shrink:0;margin-top:2px;"></i>
        <span style="color:#15803d;font-size:0.88rem;line-height:1.4;"><?= htmlspecialchars($success) ?></span>
    </div>
    <?php endif; ?>

    <!-- Social Register Buttons -->
    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:0;">
        <a href="<?= BASE_URL ?>/auth/google" id="btn-google-register"
           style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:0.72rem 1rem;border-radius:50px;font-family:'Outfit',sans-serif;font-weight:600;font-size:0.95rem;text-decoration:none;background:#fff;border:1.5px solid #dadce0;color:#3c4043;transition:all 0.22s ease;">
            <svg width="20" height="20" viewBox="0 0 48 48" style="flex-shrink:0;"><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/></svg>
            Daftar dengan Google
        </a>
        <a href="<?= BASE_URL ?>/auth/facebook" id="btn-facebook-register"
           style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:0.72rem 1rem;border-radius:50px;font-family:'Outfit',sans-serif;font-weight:600;font-size:0.95rem;text-decoration:none;background:#1877f2;border:1.5px solid #1877f2;color:#fff;transition:all 0.22s ease;">
            <i class="fab fa-facebook-f"></i>
            Daftar dengan Facebook
        </a>
    </div>
    <div style="display:flex;align-items:center;gap:0.75rem;color:#94a3b8;font-size:0.82rem;font-weight:500;margin:1.15rem 0;">
        <span style="flex:1;height:1px;background:#e2e8f0;"></span>
        <span>atau daftar dengan email</span>
        <span style="flex:1;height:1px;background:#e2e8f0;"></span>
    </div>

    <!-- Register Form -->
    <form action="<?= BASE_URL ?>/register" method="POST" onsubmit="return validateRegister()">

        <div class="mb-3">
            <label for="name" class="form-label">Nama Lengkap</label>
            <div class="input-icon">
                <i class="fas fa-user ico"></i>
                <input type="text" id="name" name="name" class="form-control"
                       placeholder="Nama kamu"
                       value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
                       required minlength="3">
            </div>
        </div>

        <div class="mb-3">
            <label for="reg-email" class="form-label">Alamat Email</label>
            <div class="input-icon">
                <i class="fas fa-envelope ico"></i>
                <input type="email" id="reg-email" name="email" class="form-control"
                       placeholder="email@kamu.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required>
            </div>
        </div>

        <div class="mb-3">
            <label for="reg-password" class="form-label">Password</label>
            <div class="input-icon" style="position:relative;">
                <i class="fas fa-lock ico"></i>
                <input type="password" id="reg-password" name="password" class="form-control"
                       placeholder="Min. 8 karakter"
                       required minlength="8"
                       oninput="checkStrength(this.value)">
                <i class="fas fa-eye toggle-pw" onclick="togglePw('reg-password', this)"></i>
            </div>
            <!-- Strength Bar -->
            <div id="strengthBar" class="pw-strength-bar" style="background:#e2e8f0;width:0%;"></div>
            <p id="strengthLabel" style="font-size:0.75rem;color:#94a3b8;margin-top:3px;"></p>
        </div>

        <div class="mb-4">
            <label for="confirm-password" class="form-label">Konfirmasi Password</label>
            <div class="input-icon" style="position:relative;">
                <i class="fas fa-lock ico"></i>
                <input type="password" id="confirm-password" name="password_confirm" class="form-control"
                       placeholder="Ulangi password" required>
                <i class="fas fa-eye toggle-pw" onclick="togglePw('confirm-password', this)"></i>
            </div>
        </div>

        <button type="submit" class="btn-register">
            <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
        </button>
    </form>

    <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid #e2e8f0;">
        <span style="color:#64748b;font-size:0.9rem;">Sudah punya akun? </span>
        <a href="<?= BASE_URL ?>/login" style="color:var(--primary);font-weight:700;text-decoration:none;">
            Masuk Sekarang
        </a>
    </div>
    <div style="text-align:center;margin-top:1rem;">
        <a href="<?= BASE_URL ?>/" style="color:#94a3b8;font-size:0.82rem;text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
        </a>
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
    bar.style.width     = lvl.w;
    bar.style.background = lvl.color;
    label.textContent   = lvl.text;
    label.style.color   = lvl.color;
}

function validateRegister() {
    const pw  = document.getElementById('reg-password').value;
    const cpw = document.getElementById('confirm-password').value;
    if (pw !== cpw) {
        alert('Password dan konfirmasi password tidak sama!');
        return false;
    }
    return true;
}
</script>
</body>
</html>
