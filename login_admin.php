<?php
require_once 'config.php';

if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: siswa/dashboard.php");
    }
    exit;
}

$error = '';

if (isset($_POST['login_admin'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $res_admin = mysqli_query($conn, "SELECT * FROM Admin WHERE username = '$username'");
    if (mysqli_num_rows($res_admin) === 1) {
        $row = mysqli_fetch_assoc($res_admin);
        if ($password === $row['password']) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = 'admin';
            header("Location: admin/dashboard.php");
            exit;
        }
    }
    $error = 'Username/Password salah!';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Radar Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --dark-blue: #1A0089;
            --orange: #FE5E32;
            --june-bud: #B8CE52;
            --white-chocolate: #FFF3D6;
            --glass-card: rgba(255, 255, 255, 0.5);
            --glass-border: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white-chocolate);
            color: var(--dark-blue);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-image: radial-gradient(var(--june-bud) 0.8px, transparent 0.8px);
            background-size: 25px 25px;
        }

        .blob {
            position: fixed; width: 350px; height: 350px; border-radius: 50%;
            filter: blur(80px); z-index: -1; opacity: 0.4;
        }
        .blob-1 { background: var(--june-bud); top: -5%; left: -5%; }
        .blob-2 { background: var(--orange); bottom: -5%; right: -5%; }

        .glass-login-card {
            background: var(--glass-card);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 2px solid var(--glass-border);
            border-radius: 35px;
            box-shadow: 12px 12px 0px var(--june-bud); 
            width: 100%;
            max-width: 380px;
            padding: 30px;
            position: relative;
        }

        .admin-badge {
            background: var(--dark-blue);
            color: white;
            padding: 4px 12px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
            margin-bottom: 10px;
            transform: rotate(-1deg);
        }

        h2 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            letter-spacing: -2px;
            font-size: 2.2rem;
            line-height: 0.9;
            margin-bottom: 5px;
        }

        .form-label { font-weight: 800; font-size: 0.7rem; margin-bottom: 4px; }
        
        .form-control {
            border-radius: 12px;
            padding: 10px 14px;
            border: 2px solid transparent;
            background: white;
            font-size: 0.9rem;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--dark-blue);
        }

        .btn-admin {
            background: var(--dark-blue);
            color: #ffffff !important;
            border-radius: 50px;
            padding: 12px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            border: none;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            margin-top: 5px;
            width: 100%; /* MEMASTIKAN SEJAJAR */
            box-shadow: 0 4px 15px rgba(26, 0, 137, 0.2);
        }

        .btn-admin:hover {
            transform: translateY(-4px) scale(1.02);
            background: var(--orange) !important;
            color: #ffffff !important;
            box-shadow: 0 8px 25px rgba(254, 94, 50, 0.4);
        }

        .btn-home {
            color: var(--orange);
            font-weight: 800;
            text-decoration: none;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px;
            border: 2px solid var(--orange);
            border-radius: 50px;
            transition: 0.3s;
            background: transparent;
            width: 100%; /* MEMASTIKAN SEJAJAR */
        }

        .btn-home:hover {
            background: var(--orange);
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(254, 94, 50, 0.2);
        }

        .divider {
            height: 1px;
            background: rgba(26, 0, 137, 0.1);
            margin: 20px 0;
        }

        .error-msg {
            font-size: 0.75rem;
            background: #ffdada;
            color: #b00000;
            padding: 8px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="glass-login-card">
        <div class="text-center mb-3">
            <span class="admin-badge">Authorized Personnel Only</span>
            <h2>ADMIN<br>RADAR.</h2>
            <p style="font-size: 0.7rem; font-weight: 600; opacity: 0.8; margin-top: 10px; line-height: 1.4;">
               Akses eksklusif untuk manajemen data dan pengawasan ekosistem Radar Sekolah.
            </p>
        </div>

        <?php if ($error): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle me-1"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-2">
                <label class="form-label text-uppercase">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="ID Admin">
            </div>
            <div class="mb-3">
                <label class="form-label text-uppercase">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            
            <button type="submit" name="login_admin" class="btn btn-admin">
                MASUK <i class="fas fa-chevron-right ms-1"></i>
            </button>
        </form>

        <div class="divider"></div>

        <a href="home.php" class="btn btn-home text-uppercase">
            <i class="fas fa-house"></i> Kembali ke Homepage
        </a>
    </div>
</body>
</html>