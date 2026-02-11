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

if (isset($_POST['login_siswa'])) {
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);

    $res_siswa = mysqli_query($conn, "SELECT * FROM Siswa WHERE nis = '$nis'");
    if (mysqli_num_rows($res_siswa) === 1) {
        $row = mysqli_fetch_assoc($res_siswa);
        $_SESSION['login'] = true;
        $_SESSION['nis'] = $row['nis'];
        $_SESSION['nama'] = $row['nama'];
        $_SESSION['role'] = 'siswa';
        header("Location: siswa/dashboard.php");
        exit;
    } else {
        $error = 'NIS tidak terdaftar!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelapor | RADAR</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Syne:wght@800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --dark-blue: #1A0089;
            --orange: #FE5E32;
            --june-bud: #B8CE52;
            --white-chocolate: #FFF3D6;
            --glass-card: rgba(255, 255, 255, 0.7);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white-chocolate);
            color: var(--dark-blue);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(var(--june-bud) 0.8px, transparent 0.8px);
            background-size: 30px 30px;
            overflow-x: hidden;
        }

        .blob {
            position: fixed; width: 400px; height: 400px; border-radius: 50%;
            filter: blur(80px); z-index: -1; opacity: 0.5;
        }
        .blob-1 { background: var(--june-bud); top: -10%; left: -10%; }
        .blob-2 { background: var(--orange); bottom: -10%; right: -10%; }

        .glass-login-card {
            background: var(--glass-card);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 3px solid var(--dark-blue);
            border-radius: 40px 10px 40px 10px;
            box-shadow: 12px 12px 0px var(--dark-blue);
            width: 100%;
            max-width: 400px;
            padding: 40px;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        h2 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 2.5rem;
            line-height: 1;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .form-control {
            border-radius: 15px;
            padding: 12px 18px;
            border: 3px solid var(--dark-blue);
            font-weight: 600;
        }

        /* --- PERBAIKAN & EFEK BUTTON MASUK --- */
        .btn-submit {
            background: var(--orange);
            color: #ffffff !important; /* Paksa warna teks tetap putih */
            border-radius: 15px;
            padding: 15px;
            font-weight: 800;
            text-transform: uppercase;
            border: 3px solid var(--dark-blue);
            box-shadow: 6px 6px 0px var(--dark-blue);
            width: 100%;
            transition: all 0.1s ease-in-out;
            position: relative;
            outline: none;
        }

        .btn-submit:hover {
            color: #ffffff !important;
            background: var(--orange);
            transform: translate(2px, 2px); /* Gerakan sedikit ke bawah */
            box-shadow: 3px 3px 0px var(--dark-blue); /* Bayangan mengecil seolah ditekan */
        }

        .btn-submit:active {
            transform: translate(6px, 6px); /* Full ditekan */
            box-shadow: 0px 0px 0px var(--dark-blue);
        }

        /* --- STYLE KEMBALI KE HOMEPAGE --- */
        .back-link {
            color: var(--dark-blue);
            text-decoration: none;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 24px;
            border-radius: 50px;
            background: rgba(26, 0, 137, 0.08);
            transition: all 0.3s ease;
            display: inline-block;
            border: 2px solid transparent;
        }

        .back-link:hover {
            background: var(--dark-blue);
            color: #ffffff !important;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(26, 0, 137, 0.2);
        }

        .divider {
            height: 3px;
            background: var(--dark-blue);
            margin: 25px 0;
            border-radius: 10px;
            opacity: 1;
        }

        .error-msg {
            background: #FFDADA;
            color: #B00000;
            padding: 12px;
            border-radius: 15px;
            border: 2px solid #B00000;
            margin-bottom: 20px;
            font-weight: 700;
            font-size: 0.85rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="container d-flex justify-content-center">
        <div class="glass-login-card">
            <h2>Haloo<br>Siswa!</h2>
            <p class="text-muted small mb-4">Gunakan NIS untuk masuk ke sistem.</p>

            <?php if ($error): ?>
                <div class="error-msg"><?= $error ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="mb-4">
                    <label class="form-label fw-bold text-uppercase small">NIS ANDA</label>
                    <input type="number" name="nis" class="form-control" required placeholder="Masukkan 5 digit NIS...">
                </div>
                
                <button type="submit" name="login_siswa" class="btn btn-submit mb-3">
                    Masuk Sekarang →
                </button>
            </form>

            <div class="divider"></div>

            <div class="footer-links text-center">
                <p class="mb-3 small fw-bold">Belum terdaftar? <a href="register.php" style="color: var(--orange); text-decoration: none; font-weight: 800;">BUAT AKUN</a></p>
                <a href="home.php" class="back-link">
                    <i class="fas fa-arrow-left me-2"></i> KEMBALI KE HOMEPAGE
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>