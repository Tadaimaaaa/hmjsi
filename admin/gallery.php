<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

$error = '';
$success = '';

// Handle Image Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['upload'])) {
    $title = htmlspecialchars(trim($_POST['title']));
    $photo_date = !empty($_POST['photo_date']) ? $_POST['photo_date'] : null;
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/img/gallery/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_' . uniqid() . '.' . $extension;
        $target_file = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            try {
                $stmt = $pdo->prepare("INSERT INTO gallery (title, image, photo_date) VALUES (?, ?, ?)");
                $stmt->execute([$title, $image_name, $photo_date]);
                $success = "Foto berhasil diunggah ke galeri.";
            } catch (PDOException $e) {
                $error = "Terjadi kesalahan database: " . $e->getMessage();
            }
        } else {
            $error = "Gagal mengunggah file.";
        }
    } else {
        $error = "Pilih file gambar yang valid.";
    }
}

// Fetch all gallery images
$stmt = $pdo->query("SELECT * FROM gallery ORDER BY created_at DESC");
$gallery_items = $stmt->fetchAll();

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Galeri</h1>
</div>

<?php if ($success): ?>
    <div class="alert alert-success mt-3"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
<?php endif; ?>

<!-- Upload Form -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-cloud-arrow-up me-2 text-primary"></i>Unggah Foto Baru</h5>
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Judul Foto</label>
                <input type="text" name="title" class="form-control" placeholder="Masukkan judul foto (misal: Kegiatan Bakti Sosial)" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Foto</label>
                <input type="date" name="photo_date" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Pilih Gambar</label>
                <input type="file" name="image" class="form-control" accept="image/*" required>
            </div>
            <div class="col-12 text-end">
                <button type="submit" name="upload" class="btn btn-primary px-4">Unggah Foto</button>
            </div>
        </form>
    </div>
</div>

<!-- Gallery List -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-images me-2 text-success"></i>Daftar Galeri</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4" style="width: 100px;">Preview</th>
                        <th>Judul</th>
                        <th>Tanggal Foto</th>
                        <th class="d-none-xs">Tanggal Unggah</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($gallery_items)): ?>
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">Belum ada foto di galeri.</td>
                    </tr>
                    <?php endif; ?>
                    <?php foreach ($gallery_items as $item): ?>
                    <tr>
                        <td class="ps-4">
                            <img src="../assets/img/gallery/<?php echo htmlspecialchars($item['image']); ?>" alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                        </td>
                        <td class="fw-bold"><?php echo htmlspecialchars($item['title']); ?></td>
                        <td><?php echo $item['photo_date'] ? date('d M Y', strtotime($item['photo_date'])) : '-'; ?></td>
                        <td class="d-none-xs text-muted small"><?php echo date('d M Y, H:i', strtotime($item['created_at'])); ?></td>
                        <td class="text-end pe-4">
                            <a href="gallery_delete.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')" title="Hapus">
                                <i class="bi bi-trash fs-6"></i>
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
