<?php
require_once 'config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: admin/dashboard.php');
        exit;
    } else if ($_SESSION['role'] == 'siswa') {
        header('Location: siswa/dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RADAR SEKOLAH | Portal Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
<style>
    :root {
        --dark-blue: #1A0089;      /* Utama */
        --orange: #FE5E32;         /* Aksen */
        --june-bud: #B8CE52;       /* Segar - Boho */
        --white-chocolate: #FFF3D6; /* Background Hangat */
        /* Tambahan untuk Liquid Glass */
        --glass-bg: rgba(255, 255, 255, 0.45);
        --glass-border: rgba(255, 255, 255, 0.7);
    }

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background-color: var(--white-chocolate);
    color: var(--dark-blue);
    min-height: 100vh;
    margin: 0;
    display: flex;
    flex-direction: column;
    overflow-x: hidden;
    padding-top: 60px;

    /* --- GANTI BAGIAN INI --- */
    /* Pola Bintang Kecil Menggunakan SVG */
    background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 14c.4 2.8 2.2 4.6 5 5-2.8.4-4.6 2.2-5 5-.4-2.8-2.2-4.6-5-5 2.8-.4 4.6-2.2 5-5z' fill='%23B8CE52' fill-opacity='0.6'/%3E%3C/svg%3E");
    background-size: 35px 35px;
    /* ------------------------ */
}

    /* Efek Liquid/Blob di Background */
    body::before, body::after {
        content: "";
        position: fixed;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        filter: blur(80px);
        z-index: -1;
        opacity: 0.5;
        animation: drift 20s infinite alternate;
    }

    body::before {
        background: var(--june-bud);
        top: -10%;
        left: -10%;
    }

    body::after {
        background: var(--orange);
        bottom: -10%;
        right: -10%;
        animation-delay: -5s;
    }

    @keyframes drift {
        from { transform: translate(0, 0) scale(1); }
        to { transform: translate(100px, 50px) scale(1.2); }
    }

    .top-welcome {
        padding: 15px;
        text-align: center;
        font-size: 1rem;
        letter-spacing: 4px;
        text-transform: uppercase;
        font-weight: 700;
        background-color: rgba(184, 206, 82, 0.9);
        color: #1a1a1a;
        border-bottom: none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Shadow lembut */
        border-bottom: 1px solid rgba(255, 255, 255, 0.3);

    }

    .main-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }

    .brand-zone {
        padding-right: 30px;
    }

    .radar-label {
        display: inline-block;
        background: var(--orange);
        color: white;
        padding: 5px 15px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.7rem;
        margin-bottom: 20px;
        transform: rotate(-1.5deg);
    }

    .brand-title {
        font-family: 'Syne', sans-serif;
        font-size: clamp(3.5rem, 10vw, 5rem);
        line-height: 0.8;
        font-weight: 800;
        margin-bottom: 15px;
        letter-spacing: -4px;
    }

    .brand-sub {
        font-weight: 800;
        color: var(--orange);
        font-size: 1.1rem;
        margin-bottom: 25px;
        display: block;
        letter-spacing: 5.5px;
    }

    .brand-desc {
        font-size: 1.05rem;
        line-height: 1.6;
        color: var(--dark-blue);
        opacity: 0.8;
        max-width: 450px;
    }

    /* Upgrade Contact Footer ke Glass */
    .contact-footer {
        margin-top: 40px;
        padding: 20px;
        background: rgba(255, 255, 255, 0.3); 
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        display: inline-block;
    }

    .login-grid {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    /* Upgrade Card ke Liquid Glass */
    .login-card {
        background: var(--glass-bg);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        padding: 35px;
        border-radius: 40px 100px 40px 10px;
        text-decoration: none !important;
        display: flex;
        align-items: center;
        gap: 20px;
        border: 2px solid var(--glass-border);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
    }

    /* Kilauan Cahaya (Liquid Reflection) */
    .login-card::before {
        content: "";
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%);
        transform: rotate(30deg);
        pointer-events: none;
    }

    .login-card:hover {
        transform: translateY(-8px) rotate(1deg);
        background: rgba(255, 255, 255, 0.6);
        box-shadow: 12px 12px 0px var(--june-bud);
        border-color: var(--dark-blue);
    }

    .login-card.admin-ver {
        border-radius: 10px 40px 100px 40px;
    }

    .login-card.admin-ver:hover {
        box-shadow: -12px 12px 0px var(--orange);
        border-color: var(--orange);
    }

    .icon-circle {
        width: 70px;
        height: 70px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        color: var(--dark-blue);
        flex-shrink: 0;
        border: 2px solid var(--june-bud);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .login-info h3 {
        font-family: 'Syne', sans-serif;
        margin: 0;
        font-weight: 800;
        color: var(--dark-blue);
        font-size: 1.8rem;
    }

    .login-info p {
        margin: 0;
        font-size: 0.8rem;
        color: var(--orange);
        font-weight: 700;
        text-transform: uppercase;
    }

    .jargon-footer {
        margin-top: 20px;
        margin-bottom: 40px;
        padding: 30px;
        background: rgba(184, 206, 82, 0.8);
        backdrop-filter: blur(10px);
        color: var(--dark-blue);
        border-radius: 30px;
        text-align: center;
        font-family: 'Syne', sans-serif;
        font-weight: 800;
        font-size: 1.5rem;
        transform: rotate(-1.5deg);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        border: 2px solid var(--dark-blue);
    }

    .credit {
        text-align: center;
        font-size: 0.7rem;
        opacity: 0.6;
        letter-spacing: 2px;
        margin-top: -20px;
        margin-bottom: 20px;
    }

    @media (max-width: 991px) {
        .brand-zone { text-align: center; padding-right: 0; margin-bottom: 50px; }
        .brand-desc { margin: 0 auto; }
        .login-card { border-radius: 30px; }
    }
</style>
</head>
<body>

    <div class="liquid-blob" style="top: 10%; left: -5%;"></div>
    <div class="liquid-blob" style="bottom: 10%; right: -5%; background: var(--june-bud); opacity: 0.2;"></div>

    <div class="top-welcome">
        Explore . Speak Up . Better Facilities . Radar Sekolah 2026
    </div>

    <main class="main-wrapper">
        <div class="container entrance-anim">
            <div class="row align-items-center">
                
                <div class="col-lg-6">
                    <div class="brand-zone">
                        <div class="radar-label">SELAMAT DATANG DI WEBSITE PENGADUAN SARANA SEKOLAH</div>
                        <h1 class="brand-title">RADAR<br>SEKOLAH</h1>
                        <span class="brand-sub">(Respons Aduan Dan Aspirasi Sarana)</span>
                        <p class="brand-desc">
                            Satu wadah digital untuk menyampaikan aspirasi demi fasilitas sekolah yang lebih baik, nyaman, dan berkualitas bagi seluruh warga sekolah.
                        </p>
                        
                        <div class="contact-footer">
                            <small class="d-block fw-bold opacity-50 mb-2">CONNECT WITH US:</small>
                            <div class="d-flex gap-4">
                                <span><i class="fab fa-instagram me-1"></i> @radar_sekolah</span>
                                <span><i class="fas fa-headset me-1"></i> (021) 889-123</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <div class="login-grid">
                        
                        <a href="index.php" class="login-card">
                            <div class="icon-circle">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <div class="login-info">
                                <p>Student Portal</p>
                                <h3>LOGIN SISWA</h3>
                            </div>
                        </a>

                        <a href="login_admin.php" class="login-card admin-ver">
                            <div class="icon-circle" style="border-color: var(--orange);">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="login-info">
                                <p>Authority Only</p>
                                <h3>LOGIN ADMIN</h3>
                            </div>
                        </a>

                    </div>
                </div>

            </div>

            <div class="row justify-content-center mt-5">
                <div class="col-lg-10">
                    <div class="jargon-footer">
                        "SAMPAIKAN ASPIRASIMU, WUJUDKAN PERUBAHAN NYATA UNTUK SEKOLAH KITA!"
                    </div>
                    <div class="credit">
                        RADAR SEKOLAH DIGITAL SYSTEM &bull; ACHMAD SURYA PUTRA &bull; 2026
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>