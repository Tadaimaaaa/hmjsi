<?php
require_once '../config/db.php';
include 'partials/header.php';

// Handle search
$search = $_GET['search'] ?? '';
if ($search) {
    $stmt = $pdo->prepare("SELECT * FROM events WHERE title LIKE ? OR location LIKE ? ORDER BY event_date DESC");
    $stmt->execute(['%' . $search . '%', '%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
}
$events = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2 fw-bold">Manajemen Event</h1>
    <a href="event_create.php" class="btn btn-primary shadow-sm"><i class="bi bi-plus-lg me-2"></i>Tambah Event</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <form method="GET" class="d-flex" role="search">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0 bg-light" placeholder="Cari nama event atau lokasi..." value="<?php echo htmlspecialchars($search); ?>">
                <button class="btn btn-primary px-4" type="submit">Cari</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Judul Event</th>
                        <th class="py-3">Tanggal & Waktu</th>
                        <th class="d-none-xs py-3">Lokasi</th>
                        <th class="d-none-xs py-3">Info Singkat</th>
                        <th class="text-end pe-4 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($events)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted"><i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-50"></i>Belum ada agenda kegiatan yang terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td class="ps-4 py-3 fw-bold text-dark"><?php echo htmlspecialchars($event['title']); ?></td>
                            <td class="py-3">
                                <small class="badge bg-primary-subtle text-primary border border-primary-subtle fw-bold">
                                    <i class="bi bi-calendar-date me-1"></i><?php echo date('d M Y', strtotime($event['event_date'])); ?>
                                </small>
                            </td>
                            <td class="d-none-xs py-3 text-secondary"><i class="bi bi-geo-alt me-1 text-danger small"></i><?php echo htmlspecialchars($event['location']); ?></td>
                            <td class="d-none-xs py-3 text-muted small text-truncate" style="max-width: 200px;"><?php echo htmlspecialchars($event['description']); ?></td>
                            <td class="text-end pe-4 py-3">
                                <a href="event_edit.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-info border-0 me-1" title="Edit"><i class="bi bi-pencil-square fs-6"></i></a>
                                <a href="event_delete.php?id=<?php echo $event['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Apakah Anda yakin ingin menghapus event ini?')" title="Hapus"><i class="bi bi-trash fs-6"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
