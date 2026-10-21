<?php 
require_once 'config/db.php';

// Get filters
$period = $_GET['period'] ?? '2025/2026';
$division_id = $_GET['division'] ?? null;

// Fetch division info if filtering
$div_info = null;
if ($division_id) {
    $st = $pdo->prepare("SELECT * FROM divisions WHERE id = ?");
    $st->execute([$division_id]);
    $div_info = $st->fetch();
}

// Fetch members for this period and optionally division
if ($division_id) {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE period = ? AND division_id = ? ORDER BY hierarchy_level ASC, id ASC");
    $stmt->execute([$period, $division_id]);
} else {
    $stmt = $pdo->prepare("SELECT * FROM members WHERE period = ? ORDER BY hierarchy_level ASC, id ASC");
    $stmt->execute([$period]);
}
$members = $stmt->fetchAll();

// Fetch cadres for this division & period
$cadres = [];
if ($division_id) {
    $stmt = $pdo->prepare("SELECT * FROM cadres WHERE division_id = ? AND period = ? ORDER BY name ASC");
    $stmt->execute([$division_id, $period]);
    $cadres = $stmt->fetchAll();
}

// Fetch all available periods for sidebar
$periods = $pdo->query("SELECT DISTINCT period FROM members ORDER BY period DESC")->fetchAll(PDO::FETCH_COLUMN);

include 'partials/header.php'; 
?>

<section class="py-5 bg-light-blue">
    <div class="container-fluid px-lg-5">
        <div class="row">
            <!-- Sidebar: Periode -->
            <div class="col-lg-2 mb-4">
                <div class="period-sidebar shadow-sm border-0">
                    <button class="period-toggle mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#periodList" aria-expanded="true">
                        <span><i class="bi bi-calendar3 me-2"></i>Periode</span>
                        <i class="bi bi-chevron-down"></i>
                    </button>
                    
                    <div class="collapse show" id="periodList">
                        <div class="period-list d-flex flex-wrap pt-2">
                            <?php foreach ($periods as $p): ?>
                                <?php 
                                    $link = "?period=" . urlencode($p);
                                    if ($division_id) $link .= "&division=" . urlencode($division_id);
                                ?>
                                <a href="<?php echo $link; ?>" class="period-btn <?php echo ($p == $period) ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($p); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Struktur -->
            <div class="col-lg-8">
                <?php if ($division_id): ?>
                    <div class="mb-3">
                        <a href="structure.php?period=<?php echo urlencode($period); ?>" class="btn-back-modern shadow-sm">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                <?php endif; ?>

                <div class="text-center mb-5">
                    <?php if ($div_info): 
                        $logo_img = 'logo.png'; // fallback
                        if (stripos($div_info['name'], 'PSDM') !== false) $logo_img = 'Logo Divisi PSDM.png';
                        if (stripos($div_info['name'], 'Humas') !== false) $logo_img = 'Logo Divisi Humas.png';
                        if (stripos($div_info['name'], 'Pendidikan') !== false) $logo_img = 'Logo Divisi Pendidikan.png';
                        if (stripos($div_info['name'], 'Ekraf') !== false) $logo_img = 'Logo Divisi Ekraf.png';
                        if (stripos($div_info['name'], 'Litbang') !== false) $logo_img = 'Logo Divisi Litbang.png';
                        if (stripos($div_info['name'], 'Infokom') !== false) $logo_img = 'Logo Divisi Infokom.png';
                    ?>
                        <div class="mb-4">
                            <img src="assets/img/<?php echo $logo_img; ?>" alt="<?php echo htmlspecialchars($div_info['name']); ?>" class="img-fluid" style="max-height: 120px; filter: drop-shadow(0 10px 15px rgba(0,0,0,0.1));">
                        </div>
                        <h2 class="fw-bold section-title mb-2"><?php echo htmlspecialchars($div_info['name']); ?></h2>
                        <div class="mb-4 px-lg-5">
                            <p class="lead text-muted mx-auto" style="max-width: 800px;"><?php echo htmlspecialchars($div_info['description']); ?></p>
                        </div>
                    <?php else: ?>
                        <h2 class="fw-bold section-title">Struktur Organisasi</h2>
                        <p class="text-muted">Susunan kepengurusan HMJ-SI periode <?php echo htmlspecialchars($period); ?>.</p>
                    <?php endif; ?>
                </div>

                <?php
                // Function to render member cards
                function render_member($member, $type = 'normal') {
                    global $period, $division_id;
                    $img_src = $member['image'] ? 'assets/img/members/' . $member['image'] : 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=random&color=fff&size=200';
                    
                    $card_class = 'member-card';
                    if ($type === true || $type === 'small') {
                        $card_class = 'member-card-small';
                    } elseif ($type === 'medium') {
                        $card_class = 'member-card-medium';
                    }
                    ?>
                    <div class="<?php echo $card_class; ?> shadow-sm h-100">
                        <div class="member-img-wrapper">
                            <img src="<?php echo $img_src; ?>" alt="<?php echo htmlspecialchars($member['name']); ?>">
                        </div>
                        <div class="member-name"><?php echo htmlspecialchars($member['name']); ?></div>
                        
                        <?php if ($member['bp']): ?>
                            <div class="mt-1">
                                <span class="member-bp"><?php echo htmlspecialchars($member['bp']); ?></span>
                            </div>
                        <?php endif; ?>

                        <div class="member-role mt-2"><?php echo htmlspecialchars($member['role']); ?></div>
                        
                        <?php if ($member['hierarchy_level'] == 6 && $member['division_id'] && !$division_id): ?>
                            <div class="mt-3">
                                <a href="structure.php?division=<?php echo $member['division_id']; ?>&period=<?php echo urlencode($period); ?>" class="btn btn-sm btn-link text-primary text-decoration-none fw-bold small">
                                    <i class="bi bi-info-circle me-1"></i>Detail Divisi
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php
                }

                // Group members by hierarchy level
                $grouped = [];
                foreach ($members as $m) {
                    $grouped[$m['hierarchy_level']][] = $m;
                }

                // Empty state for division filtering
                if ($division_id && empty($members) && empty($cadres)) {
                    ?>
                    <div class="text-center py-5 my-5">
                        <div class="mb-4">
                            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold text-secondary">Data Belum Tersedia</h4>
                        <p class="text-muted">Maaf, struktur pengurus untuk divisi ini belum tersedia pada periode <?php echo htmlspecialchars($period); ?>.</p>
                        <a href="structure.php?period=<?php echo urlencode($period); ?>" class="btn btn-primary rounded-pill px-4 mt-2">Lihat Struktur Organisasi Umum</a>
                    </div>
                    <?php
                }
                ?>

                <?php if (!$division_id): ?>
                <!-- Level 1: Pimpinan Jurusan -->
                <?php if (isset($grouped[1])): ?>
                <div class="row justify-content-center mb-5 text-center">
                    <?php foreach ($grouped[1] as $m): ?>
                    <div class="col-md-6 col-lg-5">
                        <h5 class="fw-bold mb-3 text-secondary">Pimpinan Jurusan</h5>
                        <?php render_member($m); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mb-4"><div style="width: 2px; height: 30px; background-color: #e2e8f0; margin: 0 auto;"></div></div>
                <?php endif; ?>

                <!-- Level 2 & 3: Pimpinan Himpunan (Bupati & Wakil) -->
                <?php if (isset($grouped[2]) || isset($grouped[3])): ?>
                <div class="text-center mb-4">
                    <h5 class="fw-bold mb-4 text-primary">Pimpinan Himpunan</h5>
                    
                    <!-- Bupati -->
                    <?php if (isset($grouped[2])): ?>
                    <div class="row justify-content-center mb-4">
                        <?php foreach ($grouped[2] as $m): ?>
                        <div class="col-md-6 col-lg-5">
                            <?php render_member($m); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <!-- Connector Line -->
                    <?php if (isset($grouped[2]) && isset($grouped[3])): ?>
                    <div style="width: 2px; height: 30px; background-color: #e2e8f0; margin: 0 auto;" class="mb-4"></div>
                    <?php endif; ?>

                    <!-- Wakil -->
                    <?php if (isset($grouped[3])): ?>
                    <div class="row justify-content-center">
                        <?php foreach ($grouped[3] as $m): ?>
                        <div class="col-md-6 col-lg-5">
                            <?php render_member($m); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>

                <hr class="my-5 opacity-25">

                <?php if (!$division_id): ?>
                <!-- Level 4: BPH (Sekretaris / Bendahara Utama) -->
                <?php if (isset($grouped[4])): ?>
                <div class="row g-3 g-md-4 justify-content-center mb-5 text-center">
                    <?php foreach ($grouped[4] as $m): ?>
                    <div class="col-6 col-md-4">
                        <?php render_member($m); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <hr class="my-5 opacity-25">
                <?php endif; ?>
                <?php endif; ?>

                <!-- Level 6: KABAG (Kepala Bagian / Kepala Divisi) -->
                <?php if (isset($grouped[6])): ?>
                <div class="text-center mb-4"><div style="width: 2px; height: 30px; background-color: #e2e8f0; margin: 0 auto;"></div></div>
                <h4 class="text-center fw-bold mb-4 text-primary"><?php echo $division_id ? 'Kepala Bagian (KABAG)' : 'Kepala Divisi'; ?></h4>
                <div class="row g-3 g-md-4 text-center justify-content-center mb-5">
                    <?php foreach ($grouped[6] as $m): ?>
                    <div class="col-6 col-md-4">
                        <?php render_member($m); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Level 7: SEKBEND / SEKRETARIS / BENDAHARA Divisi -->
                <?php if (isset($grouped[7])): ?>
                <div class="text-center mb-4"><div style="width: 2px; height: 30px; background-color: #e2e8f0; margin: 0 auto;"></div></div>
                <h4 class="text-center fw-bold mb-4 text-secondary small">Sekretaris & Bendahara Divisi</h4>
                <div class="row g-3 g-md-4 text-center justify-content-center mb-5">
                    <?php foreach ($grouped[7] as $m): ?>
                    <div class="col-6 col-md-3">
                        <?php render_member($m, 'small'); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Level 8: KABID Divisi -->
                <?php if (isset($grouped[8])): ?>
                <div class="text-center mb-4"><div style="width: 2px; height: 30px; background-color: #e2e8f0; margin: 0 auto;"></div></div>
                <h4 class="text-center fw-bold mb-4 text-secondary small">Kepala Bidang (KABID)</h4>
                <div class="row g-3 g-md-4 text-center justify-content-center mb-5">
                    <?php foreach ($grouped[8] as $m): ?>
                    <div class="col-6 col-md-3">
                        <?php render_member($m, 'medium'); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <!-- Level 9: STAFF / ANGGOTA Divisi (Render from Cadres Table) -->
                <?php if ($division_id && !empty($cadres)): ?>
                <div class="text-center mb-4"><div style="width: 2px; height: 30px; background-color: #e2e8f0; margin: 0 auto;"></div></div>
                <h4 class="text-center fw-bold mb-4 text-secondary small">Staff Divisi</h4>
                <div class="row g-4 text-center justify-content-center">
                    <?php foreach ($cadres as $c): ?>
                    <div class="col-6 col-md-4 col-lg-2">
                        <?php 
                        // Map cadre data to format expected by render_member
                        $m_data = [
                            'name' => $c['name'],
                            'bp' => $c['bp'],
                            'role' => $c['role'],
                            'image' => $c['image'],
                            'hierarchy_level' => 9, // Force staff level for styling
                            'division_id' => $c['division_id']
                        ];
                        render_member($m_data, 'small'); 
                        ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                
                <!-- If staff cards come from members table -->
                <?php if (isset($grouped[9])): ?>
                <div class="text-center mb-4"><div style="width: 2px; height: 30px; background-color: #e2e8f0; margin: 0 auto;"></div></div>
                <h4 class="text-center fw-bold mb-4 text-secondary small">Staff Divisi</h4>
                <div class="row g-4 text-center justify-content-center">
                    <?php foreach ($grouped[9] as $m): ?>
                    <div class="col-6 col-md-4 col-lg-2">
                        <?php render_member($m, 'small'); ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>


                <!-- SECTION: DAFTAR KADER / ANGGOTA LENGKAP -->
                <?php if ($division_id): ?>
                <div class="mt-5 pt-5 border-top">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box-small me-3">
                            <i class="bi bi-people-fill text-primary fs-4"></i>
                        </div>
                        <h4 class="fw-bold mb-0">Kader</h4>
                    </div>
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-uppercase small fw-bold text-secondary" style="width: 60%;">Nama Lengkap</th>
                                        <th class="py-3 text-uppercase small fw-bold text-secondary text-end pe-4">No BP / NIP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($cadres)): ?>
                                    <tr>
                                        <td colspan="2" class="text-center py-4 text-muted small italic">Belum ada data kader untuk periode ini.</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php foreach ($cadres as $c): ?>
                                    <tr>
                                        <td class="ps-4 py-3 fw-bold text-dark"><?php echo htmlspecialchars($c['name']); ?></td>
                                        <td class="py-3 text-end pe-4">
                                            <span class="member-bp"><?php echo htmlspecialchars($c['bp']); ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

            </div>

            <!-- Right Spacer -->
            <div class="col-lg-2 d-none d-lg-block"></div>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
