<?php
require_once '../config.php';
require_once '../functions.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset();
    session_destroy();
    header("Location: ../home.php");
    exit;
}

check_login('siswa');

$nis = $_SESSION['nis'];
$success = '';
$error = '';

if (isset($_POST['submit_aspirasi'])) {
    $id_kategori = $_POST['id_kategori'];
    $lokasi = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);

    $query = "INSERT INTO Aspirasi (nis, id_kategori, lokasi, keterangan) VALUES ('$nis', '$id_kategori', '$lokasi', '$keterangan')";
    if (mysqli_query($conn, $query)) {
        $success = "Aspirasi berhasil dikirim!";
    } else {
        $error = "Gagal mengirim aspirasi.";
    }
}

$query_histori = "SELECT aspirasi.*, kategori.ket_kategori 
                  FROM aspirasi 
                  JOIN kategori ON aspirasi.id_kategori = kategori.id_kategori 
                  WHERE aspirasi.nis = '$nis' 
                  ORDER BY aspirasi.created_at DESC";
$result_histori = mysqli_query($conn, $query_histori);
$result_kategori = mysqli_query($conn, "SELECT * FROM Kategori");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa | RADAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --dark-blue: #1A0089;
            --orange: #FE5E32;
            --june-bud: #B8CE52;
            --white-chocolate: #FFF3D6;
            --glass-card: rgba(255, 255, 255, 0.6);
            --glass-border: rgba(255, 255, 255, 0.8);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white-chocolate);
            color: var(--dark-blue);
            min-height: 100vh;
            overflow-x: hidden;
            background-image: radial-gradient(var(--june-bud) 0.5px, transparent 0.5px);
            background-size: 30px 30px;
            /* Dot Pattern Background */
            background-image: radial-gradient(var(--june-bud) 0.8px, transparent 0.8px);
            background-size: 30px 30px;
        }

        /* Dreamy Blobs */
        .blob {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(90px);
            z-index: -1;
            opacity: 0.4;
            animation: drift 20s infinite alternate;
        }
        .blob-1 { background: var(--june-bud); top: -10%; right: -10%; }
        .blob-2 { background: var(--orange); bottom: -10%; left: -10%; animation-delay: -5s; }

        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(-50px, 50px) scale(1.1); }
        }

        /* Navbar Enhancement */
        .navbar-custom {
            background: rgba(26, 0, 137, 0.9);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 15px 0;
            border-bottom: 3px solid var(--june-bud);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Profile & Logout Separation */
        .user-info-wrapper {
            background: rgba(255, 255, 255, 0.1);
            padding: 6px 16px;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
        }

        .btn-logout-custom {
            background: var(--orange);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 50px;
            font-weight: 800;
            font-size: 0.8rem;
            font-family: 'Syne';
            transition: 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(254, 94, 50, 0.3);
        }

        .btn-logout-custom:hover {
            background: white;
            color: var(--orange);
            transform: translateY(-2px);
        }

        /* Bento Card Styles */
        .bento-card {
            background: var(--glass-card);
            backdrop-filter: blur(10px);
            border: 2px solid var(--glass-border);
            border-radius: 30px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            height: 100%;
        }

        .form-control, .form-select {
            border-radius: 15px;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.9);
            padding: 12px 18px;
        }

        .history-card {
            background: white;
            border-radius: 40px;
            border: 3px solid var(--dark-blue);
            box-shadow: 12px 12px 0px var(--orange);
            overflow: hidden;
        }

        .table thead th {
            background: var(--dark-blue);
            color: white;
            padding: 18px;
            font-family: 'Syne', sans-serif;
            text-transform: uppercase;
            font-size: 0.7rem;
        }

        .btn-send {
            background: var(--dark-blue);
            color: white;
            border-radius: 50px;
            padding: 12px;
            font-weight: 800;
            font-family: 'Syne';
            border: none;
        }

        .btn-print {
            background: white;
            color: var(--dark-blue);
            border: 2px solid var(--dark-blue);
            border-radius: 50px;
            font-weight: 700;
            padding: 8px 20px;
        }

        @media print {
            .navbar-custom, .blob, .bento-card, .btn-print, .no-print, .btn-close { display: none !important; }
            body { background: white !important; }
            .history-card { box-shadow: none !important; border: 1px solid #000 !important; }
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <nav class="navbar-custom mb-5">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="#" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <i class="fas fa-paper-plane text-warning"></i>
                <span style="font-family: 'Syne'; font-weight: 800; font-size: 1.4rem; letter-spacing: -1px;">RADAR.</span>
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <div class="user-info-wrapper d-none d-md-flex">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 28px; height: 28px;">
                        <i class="fas fa-user-graduate text-primary" style="font-size: 0.75rem;"></i>
                    </div>
                    <div style="line-height: 1">
                        <small class="d-block opacity-75" style="font-size: 0.6rem; font-weight: 700; text-transform: uppercase;">Student</small>
                        <span class="fw-bold" style="font-size: 0.85rem;"><?= $_SESSION['nama'] ?></span>
                    </div>
                </div>
                
                <a href="dashboard.php?action=logout" class="btn-logout-custom shadow-sm">
                    <span>LOGOUT</span>
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="row mb-4 align-items-end">
            <div class="col-md-8">
                <h1 style="font-family: 'Syne'; font-weight: 800; letter-spacing: -2px;">DASHBOARD SISWA.</h1>
                <p class="text-muted">Pantau dan kirim aspirasi Anda untuk kemajuan sekolah.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <button onclick="window.print()" class="btn btn-print no-print shadow-sm">
                    <i class="fas fa-file-pdf me-2"></i> Download Laporan (PDF)
                </button>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 no-print">
                <div class="bento-card">
                    <h5 class="fw-bold mb-4" style="font-family: 'Syne';"><i class="fas fa-edit me-2 text-orange"></i>FORM LAPORAN</h5>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success border-0 rounded-4 small mb-4">
                            <i class="fas fa-check-circle me-2"></i><?= $success ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Kategori</label>
                            <select name="id_kategori" class="form-select shadow-sm" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                <?php while($kat = mysqli_fetch_assoc($result_kategori)): ?>
                                    <option value="<?= $kat['id_kategori'] ?>"><?= $kat['ket_kategori'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Detail Lokasi Kejadian</label>
                            <input type="text" name="lokasi" class="form-control shadow-sm" placeholder="Contoh: Lab Komputer" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold">Isi Aspirasi</label>
                            <textarea name="keterangan" class="form-control shadow-sm" rows="5" placeholder="Jelaskan secara detail..." required></textarea>
                        </div>
                        <button type="submit" name="submit_aspirasi" class="btn btn-send w-100 shadow-lg">
                            KIRIM ASPIRASI <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="history-card">
                    <div class="p-4 bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0" style="font-family: 'Syne';">RIWAYAT LAPORAN ANDA</h6>
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3"><?= mysqli_num_rows($result_histori) ?> Total</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>WAKTU</th>
                                    <th>KATEGORI & LOKASI</th>
                                    <th>STATUS</th>
                                    <th class="text-center no-print">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result_histori) > 0): while($row = mysqli_fetch_assoc($result_histori)): ?>
                                    <tr>
                                        <td class="px-4">
                                            <div class="fw-bold small"><?= date('d/m/Y', strtotime($row['created_at'])) ?></div>
                                            <div class="text-muted" style="font-size: 10px;"><?= date('H:i', strtotime($row['created_at'])) ?> WIB</div>
                                        </td>
                                        <td>
                                            <div class="fw-bold small text-dark"><?= $row['ket_kategori'] ?></div>
                                            <div class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i><?= $row['lokasi'] ?></div>
                                        </td>
                                        <td><?= get_status_badge($row['status']) ?></td>
                                        <td class="text-center no-print px-4">
                                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" style="font-size: 10px;" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $row['id_aspirasi'] ?>">
                                                DETAIL
                                            </button>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalDetail<?= $row['id_aspirasi'] ?>" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content" style="border-radius: 30px; border: 3px solid var(--dark-blue);">
                                                <div class="modal-body p-4">
                                                    <div class="d-flex justify-content-between align-items-start mb-4">
                                                        <h5 class="fw-bold mb-0" style="font-family: 'Syne';">DETAIL ASPIRASI</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                        <label class="text-uppercase small fw-bold text-muted mb-2 d-block">Pesan Siswa:</label>
                                                        <div class="p-3 bg-light rounded-4 border">
                                                            <?= nl2br(htmlspecialchars($row['keterangan'])) ?>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label class="text-uppercase small fw-bold text-success mb-2 d-block">Tanggapan Admin:</label>
                                                        <?php if($row['feedback']): ?>
                                                            <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 border border-success border-opacity-20">
                                                                <?= nl2br(htmlspecialchars($row['feedback'])) ?>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="p-3 text-center border-dashed rounded-4 bg-light">
                                                                <p class="mb-0 small text-muted">Belum ada tanggapan.</p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <p class="text-muted small">Data tidak ditemukan.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>