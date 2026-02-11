<?php
require_once 'config.php';

if (isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $nis = mysqli_real_escape_string($conn, $_POST['nis']);
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $kelas = mysqli_real_escape_string($conn, $_POST['kelas']);

    $check_nis = mysqli_query($conn, "SELECT nis FROM Siswa WHERE nis = '$nis'");
    if (mysqli_num_rows($check_nis) > 0) {
        $error = "NIS sudah terdaftar!";
    } else {
        $query = "INSERT INTO Siswa (nis, nama, kelas) VALUES ('$nis', '$nama', '$kelas')";
        if (mysqli_query($conn, $query)) {
            $success = "Berhasil! Silakan login.";
        } else {
            $error = "Gagal registrasi.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Pengaduan Sekolah</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=Syne:wght@800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --dark-blue: #1A0089;
            --orange: #FE5E32;
            --june-bud: #B8CE52;
            --white-chocolate: #FFF3D6;
            --glass-bg: rgba(255, 255, 255, 0.45);
            --glass-border: rgba(255, 255, 255, 0.7);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white-chocolate);
            color: var(--dark-blue);
            height: 100vh;
            margin: 0;
            display: flex;
            flex-direction: column; /* Biar navbar di atas, konten di bawahnya */
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background-image: radial-gradient(var(--june-bud) 0.8px, transparent 0.8px);
            background-size: 30px 30px;
        }

        /* Navbar Panjang Memenuhi Sudut */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: var(--dark-blue);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }

        .navbar-text-left {
            color: var(--june-bud);
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.9rem;
        }

        .navbar-text-right {
            color: white;
            font-weight: 700;
            font-size: 0.75rem;
            letter-spacing: 1px;
            opacity: 0.8;
        }

        /* Blob Background */
        body::before, body::after {
            content: ""; position: fixed; width: 400px; height: 400px;
            border-radius: 50%; filter: blur(80px); z-index: -1; opacity: 0.4;
        }
        body::before { background: var(--june-bud); top: -10%; left: -10%; }
        body::after { background: var(--orange); bottom: -10%; right: -10%; }

        .register-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 30px;
            border-radius: 60px 20px 60px 20px;
            border: 2px solid var(--glass-border);
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
            margin-top: 60px; /* Jarak dari navbar */
        }

        h3 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .form-label {
            font-weight: 800;
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .form-control {
            border-radius: 12px;
            padding: 10px 15px;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
        }

        .btn-regis {
            background: var(--dark-blue);
            color: white;
            border-radius: 15px;
            padding: 12px;
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.85rem;
            border: none;
            transition: 0.3s;
        }

        .btn-regis:hover {
            background: var(--orange);
            color: white;
        }

        .footer-link {
            font-size: 0.8rem;
            font-weight: 700;
            text-decoration: none;
            color: var(--orange);
        }

        .corner-badge {
        position: absolute; top: 20px; right: -10px;
        background: var(--june-bud); color: var(--dark-blue);
        padding: 4px 12px; font-weight: 800; font-size: 0.6rem;
        border-radius: 5px; transform: rotate(5deg);
        }
    </style>
</head>
<body>

    <nav class="navbar-custom">
        <div class="navbar-text-left">RADAR Sekolah</div>
    </nav>

    <div class="register-card">
        <div class="text-center mb-4">
            <h3>Daftar Akun</h3>
            <p class="text-muted small">Silakan lengkapi data diri Anda</p>
        </div>

                <div class="corner-badge">JOIN US</div>
        <div class="text-center mb-4">
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2" style="font-size: 0.8rem;"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success py-2" style="font-size: 0.8rem;"><?= $success ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">NIS (Nomor Induk Siswa)</label>
                <input type="number" name="nis" class="form-control" required placeholder="12345">
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required placeholder="Nama Lengkap">
            </div>
            <div class="mb-3">
                <label class="form-label">Kelas</label>
                <input type="text" name="kelas" class="form-control" required placeholder="XII RPL 1">
            </div>
            
            <button type="submit" name="register" class="btn btn-regis w-100 mb-3">Daftar Sekarang</button>
            
            <div class="text-center">
                <p class="small mb-0 font-weight-bold">
                    Sudah punya akun? <a href="index.php" class="footer-link">Login NIS</a>
                </p>
            </div>
        </form>
    </div>

</body>
</html>