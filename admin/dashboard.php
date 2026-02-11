<?php
require_once '../config.php';
require_once '../functions.php';

check_login('admin');

$success = '';
$error = '';

if (isset($_POST['update_aspirasi'])) {
    $id_aspirasi = $_POST['id_aspirasi'];
    $status = $_POST['status'];
    // TAMBAHAN: Mengambil input keterangan baru
    $keterangan = mysqli_real_escape_string($conn, $_POST['keterangan']);
    $feedback = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    // REVISI QUERY: Menambahkan update pada kolom keterangan
    $query = "UPDATE Aspirasi SET status = '$status', feedback = '$feedback', keterangan = '$keterangan' WHERE id_aspirasi = '$id_aspirasi'";
    
    if (mysqli_query($conn, $query)) { 
        $success = "Data aspirasi berhasil diperbarui!"; 
    } else { 
        $error = "Gagal memperbarui data."; 
    }
}

if (isset($_POST['delete_aspirasi'])) {
    $id_aspirasi = $_POST['id_aspirasi'];
    $query_delete = "DELETE FROM Aspirasi WHERE id_aspirasi = '$id_aspirasi'";
    if (mysqli_query($conn, $query_delete)) { 
        $success = "Data aspirasi berhasil dihapus!"; 
    } else { 
        $error = "Gagal menghapus data."; 
    }
}

$where = "WHERE 1=1";
if (isset($_GET['filter'])) {
    if (!empty($_GET['tgl'])) { $tgl = $_GET['tgl']; $where .= " AND DATE(a.created_at) = '$tgl'"; }
    if (!empty($_GET['kategori'])) { $kat = $_GET['kategori']; $where .= " AND a.id_kategori = '$kat'"; }
    if (!empty($_GET['nis'])) { $nis = $_GET['nis']; $where .= " AND a.nis = '$nis'"; }
}   

$query_all = "SELECT a.*, k.ket_kategori, s.nama, s.kelas FROM Aspirasi a JOIN Kategori k ON a.id_kategori = k.id_kategori JOIN Siswa s ON a.nis = s.nis $where ORDER BY a.created_at DESC";
$result_all = mysqli_query($conn, $query_all);
$result_kategori = mysqli_query($conn, "SELECT * FROM Kategori");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Radar Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --dark-blue: #1A0089;
            --orange: #FE5E32;
            --june-bud: #B8CE52;
            --white-chocolate: #FFF3D6;
            --glass-card: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(255, 255, 255, 0.9);
            --liquid-shadow: 0 20px 40px rgba(26, 0, 137, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--white-chocolate);
            color: var(--dark-blue);
            min-height: 100vh;
            background-image: radial-gradient(var(--june-bud) 0.8px, transparent 0.8px);
            background-size: 30px 30px;
        }

        .blob {
            position: fixed; width: 600px; height: 600px; border-radius: 50%;
            filter: blur(100px); z-index: -2; opacity: 0.4; animation: drift 25s infinite alternate;
        }
        .blob-1 { background: var(--june-bud); top: -20%; left: -10%; }
        .blob-2 { background: var(--orange); bottom: -20%; right: -10%; animation-delay: -5s; }

        @keyframes drift {
            from { transform: rotate(0deg) translate(0, 0) scale(1); }
            to { transform: rotate(20deg) translate(100px, 80px) scale(1.1); }
        }

        .glass-float-card {
            background: var(--glass-card);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 2px solid var(--glass-border);
            border-radius: 25px;
            box-shadow: var(--liquid-shadow);
            transition: all 0.4s ease;
        }

        .glass-float-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(26, 0, 137, 0.15);
        }

        .navbar-custom {
            background: rgba(26, 0, 137, 0.85);
            backdrop-filter: blur(15px);
            padding: 12px 0;
            border-bottom: 2px solid var(--june-bud);
            position: sticky; top: 0; z-index: 1000;
        }

        .admin-profile-card {
            background: rgba(255, 255, 255, 0.15);
            padding: 6px 18px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .logout-link {
            color: #ffbaba;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 15px;
            border-radius: 50px;
            background: rgba(255, 71, 71, 0.2);
            transition: 0.3s;
        }

        .logout-link:hover {
            background: rgba(255, 71, 71, 0.4);
            color: #fff;
        }

        .management-card {
            background: white;
            border-radius: 40px;
            border: 3px solid var(--dark-blue);
            box-shadow: 15px 15px 0px var(--june-bud);
            overflow: hidden;
            margin-top: 30px;
        }

        .table thead th {
            background: var(--dark-blue);
            color: white;
            padding: 20px;
            font-family: 'Syne', sans-serif;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }

        .btn-action {
            padding: 12px 24px;
            border-radius: 50px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            transition: 0.3s;
        }

        .btn-orange {
            background: var(--orange);
            color: white;
            border: none;
            box-shadow: 0 10px 20px rgba(254, 94, 50, 0.2);
        }

        .btn-orange:hover {
            background: #e04d26;
            color: white;
            transform: scale(1.05);
        }

        @media print {
            .navbar-custom, .no-print, .blob, .btn-group, .admin-profile-card, .logout-link {
                display: none !important;
            }
            body { background: white !important; padding: 0 !important; margin: 0 !important; }
            .container { max-width: 100% !important; width: 100% !important; padding: 0 !important; }
            .management-card { 
                box-shadow: none !important; 
                border: 1px solid #000 !important; 
                border-radius: 0 !important; 
                width: 100% !important;
            }
            .text-truncate { 
                overflow: visible !important; 
                white-space: normal !important; 
                max-width: none !important; 
            }
            th:last-child, td:last-child { 
                display: none !important; 
            }
        }
    </style>
</head>
<body>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <nav class="navbar-custom mb-5">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="#" class="text-white text-decoration-none d-flex align-items-center gap-2">
                <i class="fas fa-bullseye fa-lg text-warning"></i>
                <span style="font-family: 'Syne'; font-weight: 800; font-size: 1.4rem;">RADAR ADMIN</span>
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <div class="admin-profile-card">
                    <div class="text-end d-none d-md-block">
                        <small class="d-block text-white-50" style="font-size: 0.6rem; font-weight: 800;">ADMIN MODE</small>
                        <span class="text-white fw-bold" style="font-size: 0.9rem;"><?= $_SESSION['username'] ?></span>
                    </div>
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border: 2px solid var(--june-bud);">
                        <i class="fas fa-user-tie"></i>
                    </div>
                </div>
                <a href="../logout.php" class="logout-link">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <?php if($success): ?>
            <div class="alert alert-success border-0 glass-float-card mb-4"><?= $success ?></div>
        <?php endif; ?>

        <div class="row mb-5 align-items-center">
<div class="col-lg-7">
    <h1 style="font-family: 'Syne'; font-weight: 800; letter-spacing: -2px; font-size: 3rem; line-height: 1;">
        MANAJEMEN<br>ADUAN.
    </h1>
    <p class="text-muted mt-3">
        Pusat kendali laporan siswa. Validasi, proses, dan tuntaskan setiap laporan untuk transparansi sekolah yang lebih baik.
    </p>
</div>
            <div class="col-lg-5">
                <div class="glass-float-card p-4 text-center">
                    <p class="small fw-bold mb-3 opacity-75">EKSPOR DATA LAPORAN</p>
                    <button onclick="window.print()" class="btn btn-action w-100" style="background: var(--dark-blue); color: white;">
                        <i class="fas fa-file-pdf me-2"></i> Cetak Laporan PDF
                    </button>
                </div>
            </div>
        </div>

        <form action="" method="GET" class="no-print">
            <div class="row g-4 mb-5">
                <div class="col-md-3">
                    <div class="glass-float-card p-3 h-100">
                        <label class="form-label small fw-bold mb-2"><i class="fas fa-calendar-alt me-2 text-primary"></i>Tanggal</label>
                        <input type="date" name="tgl" class="form-control border-0 bg-white rounded-pill px-3" value="<?= $_GET['tgl'] ?? '' ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-float-card p-3 h-100">
                        <label class="form-label small fw-bold mb-2"><i class="fas fa-tag me-2 text-primary"></i>Kategori</label>
                        <select name="kategori" class="form-select border-0 bg-white rounded-pill px-3">
                            <option value="">Semua Kategori</option>
                            <?php mysqli_data_seek($result_kategori, 0); while($kat = mysqli_fetch_assoc($result_kategori)): ?>
                                <option value="<?= $kat['id_kategori'] ?>" <?= (isset($_GET['kategori']) && $_GET['kategori'] == $kat['id_kategori']) ? 'selected' : '' ?>><?= $kat['ket_kategori'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-float-card p-3 h-100">
                        <label class="form-label small fw-bold mb-2"><i class="fas fa-user me-2 text-primary"></i>NIS Siswa</label>
                        <input type="text" name="nis" class="form-control border-0 bg-white rounded-pill px-3" placeholder="Contoh: 2223..." value="<?= $_GET['nis'] ?? '' ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-float-card p-3 h-100 d-flex flex-column justify-content-end">
                        <div class="d-flex gap-2">
                            <button type="submit" name="filter" class="btn btn-action btn-orange flex-grow-1">
                                <i class="fas fa-filter me-1"></i> Filter
                            </button>
                            <a href="dashboard.php" class="btn btn-light rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="management-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>IDENTITAS</th>
                            <th>LAPORAN</th>
                            <th>KATEGORI</th>
                            <th>STATUS</th>
                            <th class="text-center">MANAJEMEN</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <?php if (mysqli_num_rows($result_all) > 0): while($row = mysqli_fetch_assoc($result_all)): ?>
                            <tr>
                                <td class="px-4">
                                    <div class="fw-bold"><?= $row['nama'] ?></div>
                                    <small class="text-muted"><?= $row['nis'] ?> • <?= $row['kelas'] ?></small>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 250px;"><?= $row['keterangan'] ?></div>
                                    <small class="text-primary fw-bold" style="font-size: 10px;"><?= date('d M Y', strtotime($row['created_at'])) ?></small>
                                </td>
                                <td><span class="badge rounded-pill bg-light text-dark border"><?= $row['ket_kategori'] ?></span></td>
                                <td><?= get_status_badge($row['status']) ?></td>
                                <td class="text-center px-4">
                                    <div class="btn-group gap-2">
                                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalUpdate<?= $row['id_aspirasi'] ?>">Kelola</button>
                                        <form action="" method="POST" onsubmit="return confirm('Hapus data ini?')" style="display:inline;">
                                            <input type="hidden" name="id_aspirasi" value="<?= $row['id_aspirasi'] ?>">
                                            <button type="submit" name="delete_aspirasi" class="btn btn-sm btn-outline-danger rounded-circle"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            
                            <div class="modal fade" id="modalUpdate<?= $row['id_aspirasi'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 30px; border: 3px solid var(--dark-blue);">
                                        <form action="" method="POST">
                                            <div class="modal-body p-4">
                                                <h5 class="fw-bold mb-4">Sistem Respon & Update</h5>
                                                <input type="hidden" name="id_aspirasi" value="<?= $row['id_aspirasi'] ?>">
                                                
                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Update Isi Laporan:</label>
                                                    <textarea name="keterangan" class="form-control rounded-4" rows="4" required><?= $row['keterangan'] ?></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Update Progres:</label>
                                                    <select name="status" class="form-select rounded-pill">
                                                        <option value="Menunggu" <?= $row['status'] == 'Menunggu' ? 'selected' : '' ?>>Menunggu</option>
                                                        <option value="Proses" <?= $row['status'] == 'Proses' ? 'selected' : '' ?>>Proses</option>
                                                        <option value="Selesai" <?= $row['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label small fw-bold">Umpan Balik Admin:</label>
                                                    <textarea name="feedback" class="form-control rounded-4" rows="3" placeholder="Tanggapan untuk siswa..."><?= $row['feedback'] ?></textarea>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <button type="submit" name="update_aspirasi" class="btn btn-action w-100" style="background: var(--dark-blue); color: white;">Simpan Perubahan</button>
                                                    <button type="button" class="btn btn-light btn-action" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; else: ?>
                            <tr><td colspan="5" class="text-center py-5 opacity-50">Belum ada laporan masuk.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>