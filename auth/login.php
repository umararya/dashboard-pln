<?php
/**
 * Login Page
 * Path: auth/login.php
 */

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$error = '';

// Redirect jika sudah login
if (is_logged_in()) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $pdo = db();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Cek apakah user aktif
            if ($user['is_active'] == 0) {
                $error = 'Akun Anda telah dinonaktifkan oleh Administrator. Silakan hubungi admin untuk mengaktifkan kembali.';
            } else {
                // Login berhasil
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                
                header('Location: ../index.php');
                exit;
            }
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Sistem Jadwal PLN</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 440px;
            padding: 45px;
            animation: slideUp 0.5s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logo-area {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-area img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 0 auto 20px auto;
            border-radius: 12px;
            padding: 8px;
            background: #f8fafc;
        }

        .logo-area h1 {
            font-size: 26px;
            color: #1e293b;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .logo-area p {
            color: #64748b;
            font-size: 15px;
        }

        .form-group {
            margin-bottom: 22px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 10px;
            color: #334155;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
            font-family: inherit;
        }

        input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-msg {
            background: #fef2f2;
            border: 2px solid #ef4444;
            color: #991b1b;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 22px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .error-msg::before {
            content: '⚠️';
            font-size: 18px;
        }

        .info-box {
            background: #eff6ff;
            border: 2px solid #3b82f6;
            border-radius: 12px;
            padding: 18px;
            margin-top: 28px;
            font-size: 13px;
            color: #1e40af;
        }

        .info-box strong {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            color: #1e3a8a;
        }

        .info-box p {
            margin: 6px 0;
            padding-left: 8px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 35px 25px;
            }
            
            .logo-area h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-area">
            <img src="<?= base_url('assets/images/logo_pln.png') ?>" alt="PLN Logo">
            <h1>PLN UID JATENG DIY</h1>
            <p>Sistem Jadwal Kegiatan</p>
        </div>

        <?php if ($error): ?>
            <div class="error-msg"><?= h($error) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Username</label>
                <input 
                    type="text" 
                    name="username" 
                    placeholder="Masukkan username Anda" 
                    autofocus
                    value="<?= h($_POST['username'] ?? '') ?>"
                >
            </div>

            <div class="form-group">
                <label>Password</label>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Masukkan password Anda"
                >
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="divider">
            <span>Informasi Akses</span>
        </div>

        <div class="info-box">
            <strong>🔑 Default Login untuk Testing:</strong>
            <p><strong>Admin:</strong> admin / password</p>
            <p><strong>User:</strong> user / password</p>
        </div>
    </div>
</body>
</html>