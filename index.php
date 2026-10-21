<?php
require_once 'config/db.php';

// Get 3 latest news
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC LIMIT 3");
$latest_news = $stmt->fetchAll();

// Get 2 latest events
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC LIMIT 2");
$upcoming_events = $stmt->fetchAll();

// Get total active HMJ members (excluding hierarchy level 1)
$latestPeriod = $pdo->query("SELECT MAX(period) FROM members")->fetchColumn();
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM members WHERE hierarchy_level > 1 AND period = ?");
$stmtCount->execute([$latestPeriod]);
$total_pengurus = $stmtCount->fetchColumn();

include 'partials/header.php';
?>

<!-- Hero Section -->
<header class="hero">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9 z-1">
                <div class="mb-4">
                    <img src="assets/img/logo.png" alt="Logo HMJ-SI" class="hero-logo-main">
                </div>
                <h1 class="display-1 fw-bold mb-4 hero-title-main">Si Biru <br><span class="text-gradient">Batik!</span></h1>
                <p class="lead mb-5 fs-4 mx-auto text-light opacity-75" style="max-width: 800px;">Himpunan Mahasiswa Jurusan Sistem Informasi UPI "YPTK" Padang</p>
                <div class="d-flex gap-3 justify-content-center">
                    <a href="about.php" class="btn btn-primary btn-lg px-5 shadow-lg">Tentang Kami</a>
                    <a href="events.php" class="btn btn-outline-light btn-lg px-5">Lihat Agenda</a>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- Stats Counter Section -->
<section class="py-5 bg-white border-bottom">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_pengurus; ?></div>
                    <div class="stat-label">Pengurus Himpunan</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">6</div>
                    <div class="stat-label">Divisi</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">20+</div>
                    <div class="stat-label">Program Kerja</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="stat-item">
                    <div class="stat-number">14th</div>
                    <div class="stat-label">Berkarya</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Core Values Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center text-center mb-5">
            <div class="col-lg-7">
                <h2 class="fw-bold section-title">Pilar Organisasi</h2>
                <p class="text-muted">Landasan dasar kami dalam menjalankan setiap aktivitas di lingkungan kampus.</p>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="value-card p-4 border-0 shadow-sm text-center">
                    <div class="icon-box-large mb-4">
                        <i class="bi bi-shield-check text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Integritas</h4>
                    <p class="text-muted">Menjaga kepercayaan dan kejujuran dalam setiap tindakan organisasi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card p-4 border-0 shadow-sm text-center">
                    <div class="icon-box-large mb-4">
                        <i class="bi bi-lightning-charge text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Inovasi</h4>
                    <p class="text-muted">Terus beradaptasi dan menciptakan terobosan berbasis teknologi informasi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="value-card p-4 border-0 shadow-sm text-center">
                    <div class="icon-box-large mb-4">
                        <i class="bi bi-people text-primary"></i>
                    </div>
                    <h4 class="fw-bold">Kolaborasi</h4>
                    <p class="text-muted">Bekerja sama dengan kompak untuk mencapai tujuan bersama yang lebih besar.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Division Preview Section (New) -->
<section class="py-5 bg-light-blue">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-md-8">
                <h2 class="fw-bold">Bidang Kerja Kami</h2>
                <p class="text-muted mb-0">6 Divisi yang bergerak secara dinamis untuk memajukan jurusan.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="divisions.php" class="btn btn-outline-primary rounded-pill">Lihat Semua Divisi</a>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-4 col-md-6">
                <a href="structure.php?division=1" class="text-decoration-none h-100">
                    <div class="glass-card p-4 h-100 d-flex align-items-center translate-up-hover">
                        <div class="rounded-3 bg-white p-2 me-3 shadow-sm" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/Logo Divisi PSDM.png" class="img-fluid" alt="PSDM">
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">PSDM</h6>
                            <small class="text-muted">Pengembangan SDM</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="structure.php?division=2" class="text-decoration-none h-100">
                    <div class="glass-card p-4 h-100 d-flex align-items-center translate-up-hover">
                        <div class="rounded-3 bg-white p-2 me-3 shadow-sm" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/Logo Divisi Humas.png" class="img-fluid" alt="Humas">
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Humas</h6>
                            <small class="text-muted">Relasi Publik</small>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-6">
                <a href="structure.php?division=3" class="text-decoration-none h-100">
                    <div class="glass-card p-4 h-100 d-flex align-items-center translate-up-hover">
                        <div class="rounded-3 bg-white p-2 me-3 shadow-sm" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <img src="assets/img/Logo Divisi Pendidikan.png" class="img-fluid" alt="Pendidikan">
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Pendidikan</h6>
                            <small class="text-muted">Akademik & Riset</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Newest Events Section -->
<section class="py-5 bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title">Agenda Terdekat</h2>
            <p class="text-muted">Jangan lewatkan kegiatan seru kami!</p>
        </div>
        <div class="row g-4 justify-content-center">
            <?php foreach ($upcoming_events as $event): ?>
            <div class="col-md-5">
                <div class="card h-100 p-2">
                    <div class="card-body">
                        <div class="badge-custom mb-3 d-inline-block"><?php echo date('d M Y', strtotime($event['event_date'])); ?></div>
                        <h4 class="card-title mb-3"><?php echo htmlspecialchars($event['title']); ?></h4>
                        <p class="text-muted"><i class="bi bi-geo-alt me-2"></i><?php echo htmlspecialchars($event['location']); ?></p>
                        <p class="card-text mb-4"><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                        <a href="events.php" class="text-primary fw-bold text-decoration-none">Detail Kegiatan →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="py-5 mt-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h2 class="fw-bold mb-0">Berita Terbaru</h2>
            <a href="news.php" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row g-4">
            <?php foreach ($latest_news as $news): ?>
            <div class="col-md-4">
                <div class="card h-100 overflow-hidden">
                    <img src="assets/img/news/<?php echo $news['image'] ? htmlspecialchars($news['image']) : 'news-placeholder.jpg'; ?>" class="card-img-top" style="height: 200px; object-fit: cover;" alt="...">
                    <div class="card-body p-4">
                        <p class="text-muted small mb-2"><?php echo date('d M Y', strtotime($news['news_date'])); ?></p>
                        <h5 class="card-title mb-3"><?php echo htmlspecialchars($news['title']); ?></h5>
                        <p class="card-text text-muted"><?php echo htmlspecialchars(substr($news['content'], 0, 90)); ?>...</p>
                        <a href="news_detail.php?slug=<?php echo $news['slug']; ?>" class="btn btn-link p-0 text-primary text-decoration-none fw-bold">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
