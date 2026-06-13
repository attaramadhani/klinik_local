<?php
session_start();
include '../koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'pasien') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Dokter - Cliniq</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-green: #0f3d2e;
            --accent-green: #76c720;
            --bg-soft: #f0f4f3;
        }

        body { 
            background-color: var(--bg-soft);
            color: #2d3436; 
            min-height: 100vh; 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            padding-bottom: 50px;
        }

        /* Navbar Blur Effect */
        .navbar {
            background: rgba(15, 61, 46, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Card Modern */
        .doctor-card { 
            background: white; 
            border: 1px solid rgba(0, 0, 0, 0.05); 
            border-radius: 24px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            position: relative;
            overflow: hidden;
        }
        
        .doctor-card::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(15, 61, 46, 0.02), transparent);
            transition: 0.5s;
        }

        .doctor-card:hover::before { left: 100%; }

        .doctor-card:hover { 
            transform: translateY(-8px); 
            box-shadow: 0 20px 40px rgba(15, 61, 46, 0.08);
            border-color: rgba(15, 61, 46, 0.1);
        }

        .doctor-avatar-box { 
            width: 70px; height: 70px; 
            background: #f0f9eb; 
            color: var(--primary-green); 
            border-radius: 20px; 
            display: flex; align-items: center; justify-content: center; 
            font-size: 28px;
            transition: all 0.3s ease;
        }

        .doctor-card:hover .doctor-avatar-box {
            background: var(--primary-green);
            color: white;
        }

        .schedule-info {
            background: #f8faf9;
            border-radius: 16px;
            padding: 15px;
            border-left: 4px solid var(--accent-green);
            border-top: 1px solid rgba(0, 0, 0, 0.02);
            border-right: 1px solid rgba(0, 0, 0, 0.02);
            border-bottom: 1px solid rgba(0, 0, 0, 0.02);
        }

        .btn-booking {
            background: var(--primary-green);
            color: white;
            font-weight: 800;
            border-radius: 14px;
            padding: 12px;
            transition: all 0.3s ease;
            border: none;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 1px;
        }

        .btn-booking:hover {
            background: var(--accent-green);
            color: var(--primary-green);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(118, 199, 32, 0.25);
        }

        .day-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--primary-green);
            font-weight: 700;
            opacity: 0.7;
        }

        /* Filter Buttons Style */
        .filter-btn {
            border: 1px solid rgba(15, 61, 46, 0.15);
            background: white;
            color: var(--primary-green);
            transition: all 0.3s ease;
            font-weight: 700;
        }
        .filter-btn:hover {
            background: #f0f4f3;
            border-color: var(--primary-green);
            color: var(--primary-green);
            transform: translateY(-2px);
        }
        .filter-btn.active {
            background: var(--primary-green) !important;
            border-color: var(--primary-green) !important;
            color: white !important;
            box-shadow: 0 10px 20px rgba(15, 61, 46, 0.15);
        }

        /* Mobile Responsive */
        @media (max-width: 576px) {
            .doctor-card { padding: 20px !important; }
            .doctor-avatar-box { width: 55px; height: 55px; font-size: 22px; border-radius: 14px; }
            .schedule-info { padding: 10px; }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark fixed-top shadow-sm py-3" style="background: var(--primary-green) !important; backdrop-filter: blur(10px);">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fas fa-heartbeat me-2" style="color: var(--accent-green);"></i>CLINIQ</a>
            <a href="index.php" class="btn btn-outline-light btn-sm rounded-pill px-3 fw-bold"><i class="fas fa-arrow-left me-1"></i> Dashboard</a>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px; margin-bottom: 50px;">
        <div class="text-center mb-5">
            <h2 class="fw-800 mb-2" style="color: var(--primary-green); font-size: 32px;">Jadwal Praktek Dokter</h2>
            <p class="text-muted small mb-4">Temukan dokter spesialis terbaik dan jadwalkan kunjungan Anda</p>
            
            <!-- Search Bar -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-6 col-lg-5">
                    <div class="input-group shadow-sm" style="border-radius: 16px; overflow: hidden;">
                        <span class="input-group-text bg-white border-end-0 px-3" style="border: 1px solid #e0e0e0;"><i class="fas fa-search text-success"></i></span>
                        <input type="text" id="searchDoctor" class="form-control border-start-0 py-3" placeholder="Cari nama dokter atau spesialisasi..." style="border: 1px solid #e0e0e0; outline: none; border-radius: 0 16px 16px 0; font-size: 14px;">
                    </div>
                </div>
            </div>

            <!-- Filter Poli -->
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <?php 
                $filter_poli = isset($_GET['poli']) ? db_real_escape_string($conn, $_GET['poli']) : '';
                $q_poli = db_query($conn, "SELECT DISTINCT spesialisasi FROM dokter ORDER BY spesialisasi ASC");
                ?>
                <a href="jadwal.php" class="btn px-4 rounded-pill filter-btn <?php echo ($filter_poli == '') ? 'active' : ''; ?>">Semua Poli</a>
                <?php while($p = db_fetch_assoc($q_poli)): 
                    $is_active = ($filter_poli == $p['spesialisasi']);
                ?>
                    <a href="jadwal.php?poli=<?php echo urlencode($p['spesialisasi']); ?>" 
                       class="btn px-4 rounded-pill filter-btn <?php echo $is_active ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($p['spesialisasi']); ?>
                    </a>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="row g-4">
            <?php
            $where_clause = "";
            if ($filter_poli !== '') {
                $where_clause = "WHERE d.spesialisasi = '$filter_poli'";
            }
            $q = db_query($conn, "SELECT d.*, j.id_jadwal, j.hari, j.jam_mulai, j.jam_selesai 
                                     FROM dokter d 
                                     JOIN jadwal_dokter j ON d.id_dokter = j.id_dokter
                                     $where_clause
                                     ORDER BY CASE j.hari 
                                         WHEN 'Senin' THEN 1 
                                         WHEN 'Selasa' THEN 2 
                                         WHEN 'Rabu' THEN 3 
                                         WHEN 'Kamis' THEN 4 
                                         WHEN 'Jumat' THEN 5 
                                         WHEN 'Sabtu' THEN 6 
                                         WHEN 'Minggu' THEN 7 
                                         ELSE 8 
                                     END");
            
            if(db_num_rows($q) == 0) {
                echo "<div class='col-12 text-center py-5'><i class='fas fa-calendar-times fa-3x mb-3 opacity-25'></i><p class='opacity-50'>Jadwal belum tersedia untuk saat ini.</p></div>";
            }

            while($d = db_fetch_assoc($q)) {
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="doctor-card p-4 h-100 d-flex flex-column">
                    <div class="d-flex align-items-center mb-4">
                        <div class="doctor-avatar-box me-3">
                            <i class="fas fa-user-md"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold" style="color: var(--primary-green);"><?php echo htmlspecialchars($d['nama_dokter']); ?></h5>
                            <span class="badge bg-success bg-opacity-10 text-success small fw-normal mt-1" style="font-size: 11px; padding: 6px 12px; border-radius: 8px;">
                                <?php echo htmlspecialchars($d['spesialisasi']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="schedule-info mt-auto">
                        <div class="day-label mb-1">Jadwal Praktek</div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark"><i class="far fa-calendar-check me-2 text-success"></i><?php echo $d['hari']; ?></span>
                            <span class="fw-bold text-success">
                                <?php echo date('H:i', strtotime($d['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($d['jam_selesai'])); ?>
                            </span>
                        </div>
                    </div>
                    
                    <a href="reservasi.php?id_jadwal=<?php echo $d['id_jadwal']; ?>" class="btn btn-booking w-100 mt-4 shadow-sm">
                        Booking Sekarang
                    </a>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>

    <footer class="text-center py-5 text-muted mt-5">
        <p class="small mb-0">&copy; 2026 <b>Cliniq System</b>. Crafted with care for your health.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchDoctor');
            const doctorCards = document.querySelectorAll('.doctor-card');
            const cardsContainer = document.querySelector('.row.g-4');
            
            function filterDoctorCards() {
                const query = searchInput.value.toLowerCase();
                let visibleCount = 0;
                
                doctorCards.forEach(card => {
                    const name = card.querySelector('h5').innerText.toLowerCase();
                    const spec = card.querySelector('.badge').innerText.toLowerCase();
                    const matchesSearch = name.includes(query) || spec.includes(query);
                    
                    const cardCol = card.parentElement;
                    if (matchesSearch) {
                        cardCol.style.display = 'block';
                        visibleCount++;
                    } else {
                        cardCol.style.display = 'none';
                    }
                });
                
                let emptyMessage = document.getElementById('no-doctor-message');
                if (visibleCount === 0) {
                    if (!emptyMessage) {
                        emptyMessage = document.createElement('div');
                        emptyMessage.id = 'no-doctor-message';
                        emptyMessage.className = 'col-12 text-center py-5';
                        emptyMessage.innerHTML = `<i class='fas fa-user-slash fa-3x mb-3 opacity-25' style='color: var(--primary-green);'></i><p class='opacity-50 fw-bold'>Dokter "${searchInput.value}" tidak ditemukan.</p>`;
                        cardsContainer.appendChild(emptyMessage);
                    } else {
                        emptyMessage.querySelector('p').innerHTML = `Dokter "${searchInput.value}" tidak ditemukan.`;
                    }
                } else {
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
                }
            }
            
            searchInput.addEventListener('input', filterDoctorCards);
        });
    </script>
</body>
</html>