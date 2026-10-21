<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

// Fetch all news
$stmt = $pdo->query("SELECT * FROM news ORDER BY created_at DESC");
$news_items = $stmt->fetchAll();

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Berita</h1>
    <a href="news_create.php" class="btn btn-primary">Tambah Berita Baru</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Daftar Berita</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 100px;">Preview</th>
                        <th>Judul</th>
                        <th>Tgl Berita</th>
                        <th class="d-none-xs">Slug</th>
                        <th class="d-none-xs text-end pe-4">Tanggal Post</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($news_items)): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Belum ada berita.</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($news_items as $item): ?>
                    <tr>
                        <td class="ps-4">
                            <?php if ($item['image']): ?>
                                <img src="../assets/img/news/<?php echo htmlspecialchars($item['image']); ?>" alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo date('d M Y', strtotime($item['news_date'])); ?></td>
                        <td class="d-none-xs"><code class="small text-muted"><?php echo htmlspecialchars($item['slug']); ?></code></td>
                        <td class="d-none-xs text-muted small text-end pe-4"><?php echo date('d M Y, H:i', strtotime($item['created_at'])); ?></td>
                        <td class="text-end pe-4">
                            <a href="news_edit.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="news_delete.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')">
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
