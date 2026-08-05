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
        :root {
            --primary:      #ea580c;
            --primary-dark: #c2410c;
            --primary-light:#fff4ee;
            --secondary:    #16a34a;
            --dark:         #0f172a;
            --gray-600:     #475569;
            --gray-400:     #94a3b8;
            --border:       #e2e8f0;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1a2a0d 60%, #0a1a2e 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative blobs */
        body::before {
            content: '';
            position: fixed;
            top: -120px; left: -120px;
            width: 420px; height: 420px;
            background: radial-gradient(circle, rgba(234,88,12,0.18) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -100px; right: -100px;
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(22,163,74,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        /* ── Card ── */
        .auth-card {
            background: rgba(255,255,255,0.97);
            border-radius: 24px;
            padding: 2.5rem 2.25rem;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 32px 64px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.05);
            position: relative;
            z-index: 1;
        }

        /* ── Brand ── */
        .brand-logo {
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }
        .brand-icon {
            width: 46px; height: 46px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 12px rgba(234,88,12,0.35);
        }
        .brand-text { font-size: 1.45rem; font-weight: 800; letter-spacing: -0.5px; }
        .brand-text .b1 { color: var(--primary); }
        .brand-text .b2 { color: var(--secondary); }

        /* ── Social Buttons ── */
        .btn-social {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 0.75rem 1rem;
            border-radius: 50px;
            font-family: 'Outfit', sans-serif;
            font-weight: 600; font-size: 0.95rem;
            cursor: pointer; transition: all 0.22s ease;
            text-decoration: none;
        }
        .btn-google {
            background: #fff;
            border: 1.5px solid #dadce0;
            color: #3c4043;
        }
        .btn-google:hover {
            background: #f8f9fa;
            border-color: #c1c7cd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transform: translateY(-1px);
            color: #3c4043;
        }
        .btn-facebook {
            background: #1877f2;
            border: 1.5px solid #1877f2;
            color: #fff;
        }
        .btn-facebook:hover {
            background: #166fe5;
            border-color: #166fe5;
            box-shadow: 0 2px 10px rgba(24,119,242,0.3);
            transform: translateY(-1px);
            color: #fff;
        }
        .google-icon {
            width: 20px; height: 20px;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 48 48'%3E%3Cpath fill='%234285F4' d='M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z'/%3E%3Cpath fill='%2334A853' d='M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z'/%3E%3Cpath fill='%23FBBC05' d='M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z'/%3E%3Cpath fill='%23EA4335' d='M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z'/%3E%3C/svg%3E") center/contain no-repeat;
            flex-shrink: 0;
        }

        /* ── Divider ── */
        .divider {
            display: flex; align-items: center; gap: 0.75rem;
            color: var(--gray-400); font-size: 0.82rem; font-weight: 500;
            margin: 1.25rem 0;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: var(--border);
        }

        /* ── Form Controls ── */
        .form-label { font-weight: 600; font-size: 0.87rem; color: #334155; margin-bottom: 0.4rem; }
        .input-wrap { position: relative; }
        .input-wrap .icon-left {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--gray-400); font-size: 0.88rem; pointer-events: none;
        }
        .input-wrap .icon-right {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            color: var(--gray-400); font-size: 0.88rem; cursor: pointer;
        }
        .form-control {
            font-family: 'Outfit', sans-serif;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            font-size: 0.95rem;
            transition: all 0.2s;
            background: #f8fafc;
            width: 100%;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(234,88,12,0.12);
        }
        .form-control.has-right { padding-right: 2.75rem; }
        .form-control.is-invalid-custom {
            border-color: #ef4444 !important;
            background: #fff8f8 !important;
            animation: shake 0.35s ease;
        }
        .form-control.is-invalid-custom:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.15) !important;
        }

        /* ── Submit Button ── */
        .btn-primary-auth {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff; border: none;
            border-radius: 50px; padding: 0.8rem 1rem;
            font-weight: 700; font-size: 1rem; width: 100%;
            cursor: pointer; transition: all 0.3s;
            font-family: 'Outfit', sans-serif;
            box-shadow: 0 4px 14px rgba(234,88,12,0.3);
        }
        .btn-primary-auth:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 22px rgba(234,88,12,0.4);
        }

        /* ── Error Alert ── */
        .alert-error {
            background: #fee2e2; border: 1.5px solid #fca5a5;
            border-radius: 12px; padding: 0.85rem 1rem;
            margin-bottom: 1.25rem;
            display: flex; align-items: flex-start; gap: 0.65rem;
        }
        .alert-error i { color: #ef4444; flex-shrink: 0; margin-top: 2px; }
        .alert-error span { color: #b91c1c; font-size: 0.88rem; line-height: 1.4; }

        /* ── Animations ── */
        @keyframes shake {
            0%,100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }
    </style>
</head>
<body>
<div class="auth-card">

    <!-- Brand -->
    <div class="text-center mb-4">
        <a href="<?= BASE_URL ?>/" class="brand-logo">
            <div class="brand-icon">
                <img src="<?= BASE_URL ?>/assets/img/bulet.png" alt="SobatBogor" style="height:28px;width:28px;object-fit:contain;">
            </div>
            <span class="brand-text">
                <span class="b1">Sobat</span><span class="b2">Bogor</span>
            </span>
        </a>
        <h1 style="font-size:1.35rem;font-weight:700;color:var(--dark);margin-top:1.2rem;margin-bottom:0.25rem;">
            Selamat Datang Kembali!
        </h1>
        <p style="color:var(--gray-600);font-size:0.9rem;">Masuk untuk melanjutkan perjalananmu</p>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($error)):
        $errorField = $errorField ?? 'both';
        $iconMap = ['email' => 'fa-envelope', 'password' => 'fa-lock', 'both' => 'fa-exclamation-circle'];
        $errIcon = $iconMap[$errorField] ?? 'fa-exclamation-circle';
    ?>
    <div class="alert-error">
        <i class="fas <?= $errIcon ?>"></i>
        <span><?= htmlspecialchars($error) ?></span>
    </div>
    <?php endif; ?>

    <!-- Social Login Buttons -->
    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:0;">
        <a href="<?= BASE_URL ?>/auth/google" class="btn-social btn-google" id="btn-google-login">
            <span class="google-icon"></span>
            Masuk dengan Google
        </a>
        <a href="<?= BASE_URL ?>/auth/facebook" class="btn-social btn-facebook" id="btn-facebook-login">
            <i class="fab fa-facebook-f"></i>
            Masuk dengan Facebook
        </a>
    </div>

    <!-- Divider -->
    <div class="divider"><span>atau masuk dengan email</span></div>

    <!-- Login Form -->
    <form action="<?= BASE_URL ?>/login" method="POST" id="loginForm">
        <div class="mb-3">
            <label for="email" class="form-label">Alamat Email</label>
            <div class="input-wrap">
                <i class="fas fa-envelope icon-left"></i>
                <input type="email" id="email" name="email"
                       class="form-control <?= isset($errorField) && in_array($errorField, ['email','both']) ? 'is-invalid-custom' : '' ?>"
                       placeholder="email@kamu.com"
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                       required autofocus>
            </div>
        </div>

        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <div class="input-wrap">
                <i class="fas fa-lock icon-left"></i>
                <input type="password" id="password" name="password"
                       class="form-control has-right <?= isset($errorField) && in_array($errorField, ['password','both']) ? 'is-invalid-custom' : '' ?>"
                       placeholder="Masukkan password" required>
                <i class="fas fa-eye icon-right" id="togglePwIcon" onclick="togglePw()"></i>
            </div>
        </div>

        <button type="submit" class="btn-primary-auth" id="btn-submit-login">
            <i class="fas fa-sign-in-alt me-2"></i>Masuk Sekarang
        </button>
    </form>

    <!-- Footer Links -->
    <div style="text-align:center;margin-top:1.5rem;padding-top:1.25rem;border-top:1px solid var(--border);">
        <span style="color:var(--gray-600);font-size:0.9rem;">Belum punya akun? </span>
        <a href="<?= BASE_URL ?>/register" style="color:var(--primary);font-weight:700;text-decoration:none;">
            Daftar Gratis
        </a>
    </div>

    <div style="text-align:center;margin-top:0.85rem;">
        <a href="<?= BASE_URL ?>/" style="color:var(--gray-400);font-size:0.82rem;text-decoration:none;">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Beranda
        </a>
    </div>

</div>

<script>
function togglePw() {
    const input = document.getElementById('password');
    const icon  = document.getElementById('togglePwIcon');
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
