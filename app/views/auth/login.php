<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root { --primary:#ea580c; --primary-dark:#c2410c; }
        * { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            background: #fff;
            border-radius: 1.5rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
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
        .btn-login {
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
        .btn-login:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 8px 20px rgba(234,88,12,0.3); }
        .input-icon { position: relative; }
        .input-icon i { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.9rem; }
        .input-icon .form-control { padding-left: 2.5rem; }
        .toggle-pw { position:absolute; right:14px; top:50%; transform:translateY(-50%); cursor:pointer; color:#94a3b8; font-size:0.9rem; }
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
            Selamat Datang Kembali!
        </h1>
        <p style="color:#64748b;font-size:0.9rem;">Masuk untuk melanjutkan perjalananmu</p>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($error)): ?>
    <div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:10px;padding:0.8rem 1rem;margin-bottom:1.25rem;display:flex;align-items:center;gap:0.6rem;">
        <i class="fas fa-exclamation-circle" style="color:#ef4444;flex-shrink:0;"></i>
        <span style="color:#b91c1c;font-size:0.88rem;"><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <!-- Login Form -->
    <form action="<?= BASE_URL ?>/login" method="POST">
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="email@kamu.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-icon" style="position:relative;">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Masukkan password" required>
                <i class="fas fa-eye toggle-pw" onclick="togglePw('password', this)"></i>
            </div>
        </div>

        <button type="submit" class="btn-login">
            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
        </button>
    </form>

    <div style="text-align:center;margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid #e2e8f0;">
        <span style="color:#64748b;font-size:0.9rem;">Belum punya akun? </span>
        <a href="<?= BASE_URL ?>/register" style="color:var(--primary);font-weight:700;text-decoration:none;">
            Daftar Gratis
        </a>
    </div>

    <div style="text-align:center;margin-top:1rem;">
        <a href="<?= BASE_URL ?>/" style="color:#94a3b8;font-size:0.82rem;text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function togglePw(id, icon) {
    const input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
</body>
</html>
