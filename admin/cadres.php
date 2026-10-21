<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch all available periods for filter from cadres table
$periods = $pdo->query("SELECT DISTINCT period FROM cadres ORDER BY period DESC")->fetchAll(PDO::FETCH_COLUMN);

// Handle filtering
$selectedPeriod = $_GET['period'] ?? ($periods[0] ?? '2025/2026');

if ($selectedPeriod) {
    $stmt = $pdo->prepare("SELECT cadres.*, divisions.name as division_name 
                           FROM cadres 
                           JOIN divisions ON cadres.division_id = divisions.id 
                           WHERE cadres.period = ? 
                           ORDER BY divisions.name ASC, cadres.name ASC");
    $stmt->execute([$selectedPeriod]);
} else {
    $stmt = $pdo->query("SELECT cadres.*, divisions.name as division_name 
                         FROM cadres 
                         JOIN divisions ON cadres.division_id = divisions.id 
                         ORDER BY cadres.period DESC, divisions.name ASC, cadres.name ASC");
}
$cadres = $stmt->fetchAll();

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Manajemen Kader</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <form method="GET" class="d-flex me-3">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-calendar-range text-muted"></i></span>
                <select name="period" class="form-select border-start-0 bg-light me-2" onchange="this.form.submit()" style="min-width: 150px;">
                    <option value="">Semua Periode</option>
                    <?php foreach ($periods as $p): ?>
                        <option value="<?php echo htmlspecialchars($p); ?>" <?php echo ($selectedPeriod == $p) ? 'selected' : ''; ?>>
                            Periode <?php echo htmlspecialchars($p); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <a href="cadre_add.php" class="btn btn-primary shadow-sm"><i class="bi bi-person-plus-fill me-2"></i>Tambah Kader</a>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3 d-none-xs">No</th>
                        <th class="py-3">Foto</th>
                        <th class="py-3">Nama Kader</th>
                        <th class="d-none-xs py-3">No BP / NIP</th>
                        <th class="py-3">Jabatan</th>
                        <th class="d-none-xs py-3">Divisi</th>
                        <th class="d-none-xs py-3">Periode</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cadres)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">Belum ada data kader untuk periode ini.</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($cadres as $index => $cadre): ?>
                    <tr>
                        <td class="ps-4 py-3 d-none-xs"><?php echo $index + 1; ?></td>
                        <td>
                            <?php if ($cadre['image']): ?>
                                <img src="../assets/img/members/<?php echo $cadre['image']; ?>" width="40" height="40" class="rounded-circle shadow-sm" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; font-size: 8px;">No Img</div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold text-dark py-3"><?php echo htmlspecialchars($cadre['name']); ?></td>
                        <td class="d-none-xs py-3"><span class="badge bg-light text-primary border"><?php echo htmlspecialchars($cadre['bp']); ?></span></td>
                        <td class="py-3"><small class="text-muted"><?php echo htmlspecialchars($cadre['role']); ?></small></td>
                        <td class="d-none-xs py-3"><?php echo htmlspecialchars($cadre['division_name']); ?></td>
                        <td class="d-none-xs py-3 text-muted"><?php echo htmlspecialchars($cadre['period']); ?></td>
                        <td class="text-end pe-4 py-3">
                            <a href="cadre_edit.php?id=<?php echo $cadre['id']; ?>" class="btn btn-sm btn-outline-info border-0 me-1" title="Edit"><i class="bi bi-pencil-square fs-6"></i></a>
                            <a href="cadre_delete.php?id=<?php echo $cadre['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Yakin ingin menghapus kader ini?')" title="Hapus"><i class="bi bi-trash fs-6"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
