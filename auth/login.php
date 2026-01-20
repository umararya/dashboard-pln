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
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            
            header('Location: ../index.php');
            exit;
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
    <title>Login - Jadwal Kegiatan PLN</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 32px;
        }

        .logo-area img {
            max-width: 100px;
            height: auto;
            display: block;
            margin: 0 auto 16px auto;
        }

        .logo-area h1 {
            font-size: 24px;
            color: #111827;
            margin-bottom: 8px;
        }

        .logo-area p {
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            font-size: 15px;
            outline: none;
            transition: all 0.3s;
        }

        input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-msg {
            background: #fef2f2;
            border: 1px solid #ef4444;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .info-box {
            background: #eff6ff;
            border: 1px solid #3b82f6;
            border-radius: 10px;
            padding: 16px;
            margin-top: 24px;
            font-size: 13px;
            color: #1e40af;
        }

        .info-box strong {
            display: block;
            margin-bottom: 8px;
        }

        .info-box p {
            margin: 4px 0;
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
                <input type="text" name="username" placeholder="Masukkan username" autofocus>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password">
            </div>

            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="info-box">
            <strong>🔑 Default Login:</strong>
            <p><strong>Admin:</strong> admin / password</p>
            <p><strong>User:</strong> user / password</p>
        </div>
    </div>
</body>
</html>