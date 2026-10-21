<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

// Fetch all messages
$stmt = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll();

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Pesan</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Pesan Masuk</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Pengirim</th>
                        <th>Subjek</th>
                        <th>Tanggal</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($messages)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada pesan masuk.</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($messages as $m): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold"><?php echo htmlspecialchars($m['name']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($m['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($m['subject']); ?></td>
                        <td class="text-muted small"><?php echo date('d M Y, H:i', strtotime($m['created_at'])); ?></td>
                        <td class="text-end pe-4">
                            <a href="message_view.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-eye"></i> Baca
                            </a>
                            <a href="message_delete.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                                <i class="bi bi-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
