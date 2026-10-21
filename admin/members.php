<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

// Fetch all available periods for filter
$periods = $pdo->query("SELECT DISTINCT period FROM members ORDER BY period DESC")->fetchAll(PDO::FETCH_COLUMN);

// Handle filtering
$selectedPeriod = $_GET['period'] ?? ($periods[0] ?? '');

if ($selectedPeriod) {
    // Fetch Jurusan (Level 1)
    $stmtJurusan = $pdo->prepare("SELECT * FROM members WHERE period = ? AND hierarchy_level = 1 ORDER BY hierarchy_level ASC");
    $stmtJurusan->execute([$selectedPeriod]);
    $jurusanMembers = $stmtJurusan->fetchAll();

    // Fetch Himpunan (Level > 1)
    $stmtHimpunan = $pdo->prepare("SELECT * FROM members WHERE period = ? AND hierarchy_level > 1 ORDER BY hierarchy_level ASC");
    $stmtHimpunan->execute([$selectedPeriod]);
    $himpunanMembers = $stmtHimpunan->fetchAll();
} else {
    $jurusanMembers = $pdo->query("SELECT * FROM members WHERE hierarchy_level = 1 ORDER BY period DESC")->fetchAll();
    $himpunanMembers = $pdo->query("SELECT * FROM members WHERE hierarchy_level > 1 ORDER BY hierarchy_level ASC, period DESC")->fetchAll();
}

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Manajemen Struktur Organisasi</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form method="GET" class="d-flex me-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-event text-muted"></i></span>
                <select name="period" class="form-select border-start-0 bg-light me-2" onchange="this.form.submit()" style="min-width: 150px;">
                    <option value="">Semua Periode</option>
                    <?php foreach ($periods as $p): ?>
                        <option value="<?php echo htmlspecialchars($p); ?>" <?php echo ($selectedPeriod == $p) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($p); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <a href="member_add.php" class="btn btn-primary shadow-sm"><i class="bi bi-person-plus me-2"></i>Tambah Pengurus</a>
    </div>
</div>

<!-- Bagian Pimpinan Jurusan -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-mortarboard me-2 text-primary"></i>Pimpinan Jurusan</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 d-none-xs">No</th>
                        <th class="py-3">Foto</th>
                        <th class="py-3">Nama</th>
                        <th class="d-none-xs py-3">NIP / No BP</th>
                        <th class="py-3">Jabatan</th>
                        <th class="d-none-xs py-3">Periode</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($jurusanMembers)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Tidak ada data pimpinan jurusan.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($jurusanMembers as $index => $member): ?>
                    <tr>
                        <td class="ps-4 py-3 d-none-xs"><?php echo $index + 1; ?></td>
                        <td class="py-3">
                            <?php if ($member['image']): ?>
                                <img src="../assets/img/members/<?php echo $member['image']; ?>" width="40" height="40" class="rounded-circle shadow-sm" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px; font-size: 10px;">No Img</div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-dark py-3"><?php echo htmlspecialchars($member['name']); ?></td>
                        <td class="d-none-xs py-3 text-muted"><?php echo htmlspecialchars($member['bp']); ?></td>
                        <td class="py-3"><span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?php echo htmlspecialchars($member['role']); ?></span></td>
                        <td class="d-none-xs py-3"><?php echo htmlspecialchars($member['period']); ?></td>
                        <td class="text-end pe-4 py-3">
                            <a href="member_edit.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-info border-0 me-1" title="Edit"><i class="bi bi-pencil-square fs-6"></i></a>
                            <a href="member_delete.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus"><i class="bi bi-trash fs-6"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<hr class="my-5 border-2">

<!-- Bagian Pengurus Himpunan -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-people me-2 text-success"></i>Pengurus Himpunan <span class="badge bg-success-subtle text-success ms-2"><?php echo count($himpunanMembers); ?> Anggota</span></h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 d-none-xs">No</th>
                        <th class="py-3">Foto</th>
                        <th class="py-3">Nama</th>
                        <th class="d-none-xs py-3">No BP</th>
                        <th class="py-3">Jabatan</th>
                        <th class="d-none-xs py-3">Periode</th>
                        <th class="d-none-xs py-3">Hirarki</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($himpunanMembers)): ?>
                        <tr><td colspan="8" class="text-center py-5 text-muted">Tidak ada data pengurus himpunan.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($himpunanMembers as $index => $member): ?>
                    <tr>
                        <td class="ps-4 py-3 d-none-xs"><?php echo $index + 1; ?></td>
                        <td class="py-3">
                            <?php if ($member['image']): ?>
                                <img src="../assets/img/members/<?php echo $member['image']; ?>" width="40" height="40" class="rounded-circle shadow-sm" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center border" style="width: 40px; height: 40px; font-size: 10px;">No Img</div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-dark py-3"><?php echo htmlspecialchars($member['name']); ?></td>
                        <td class="d-none-xs py-3 text-muted"><?php echo htmlspecialchars($member['bp']); ?></td>
                        <td class="py-3"><span class="badge bg-success-subtle text-success border border-success-subtle"><?php echo htmlspecialchars($member['role']); ?></span></td>
                        <td class="d-none-xs py-3"><?php echo htmlspecialchars($member['period']); ?></td>
                        <td class="d-none-xs py-3">
                            <?php 
                            $lvls = [
                                1 => 'Jurusan',
                                2 => 'Bupati',
                                3 => 'Wakil Bupati',
                                4 => 'BPH',
                                6 => 'Kadiv',
                                7 => 'Sekbend',
                                8 => 'Kabid',
                                9 => 'Staff'
                            ];
                            echo "<span class='badge bg-light text-secondary border'>Lvl " . $member['hierarchy_level'] . "</span>"; 
                            ?>
                        </td>
                        <td class="text-end pe-4 py-3">
                            <a href="member_edit.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-info border-0 me-1" title="Edit"><i class="bi bi-pencil-square fs-6"></i></a>
                            <a href="member_delete.php?id=<?php echo $member['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Yakin ingin menghapus?')" title="Hapus"><i class="bi bi-trash fs-6"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
