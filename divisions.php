<?php
require_once 'config/db.php';

$stmt = $pdo->query("SELECT * FROM divisions");
$divisions = $stmt->fetchAll();

include 'partials/header.php';
?>

<section class="py-5 bg-light-blue">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title d-inline-block">Bidang Kerja & Divisi</h2>
            <p class="text-muted">Mengenal lebih dekat peran dan tanggung jawab setiap divisi di HMJ-SI.</p>
        </div>
        
        <div class="row g-4">
            <?php 
            foreach ($divisions as $div): 
                // Assign image logos based on division name
                $logo_img = 'logo.png'; // fallback
                if (stripos($div['name'], 'PSDM') !== false) $logo_img = 'Logo Divisi PSDM.png';
                if (stripos($div['name'], 'Humas') !== false) $logo_img = 'Logo Divisi Humas.png';
                if (stripos($div['name'], 'Pendidikan') !== false) $logo_img = 'Logo Divisi Pendidikan.png';
                if (stripos($div['name'], 'Ekraf') !== false) $logo_img = 'Logo Divisi Ekraf.png';
                if (stripos($div['name'], 'Litbang') !== false) $logo_img = 'Logo Divisi Litbang.png';
                if (stripos($div['name'], 'Infokom') !== false) $logo_img = 'Logo Divisi Infokom.png';
            ?>
            <div class="col-md-6 col-lg-4">
                <a href="structure.php?division=<?php echo $div['id']; ?>" class="text-decoration-none">
                    <div class="card h-100 p-4 border-0 shadow-sm translate-up-hover division-card">
                        <div class="card-body text-center">
                        <div class="icon-box mx-auto mb-4 bg-transparent border-0 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <img src="assets/img/<?php echo $logo_img; ?>" alt="<?php echo htmlspecialchars($div['name']); ?>" class="img-fluid" style="max-height: 100%; object-fit: contain;">
                        </div>
                            <h4 class="fw-bold mb-3 text-dark"><?php echo htmlspecialchars($div['name']); ?></h4>
                            <p class="text-muted mb-4"><?php echo htmlspecialchars($div['description']); ?></p>
                            <span class="btn btn-outline-primary btn-sm rounded-pill px-4">Lihat Bidang Kerja →</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
