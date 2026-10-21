<?php
require_once '../config/db.php';
include 'partials/header.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $news_date = !empty($_POST['news_date']) ? $_POST['news_date'] : date('Y-m-d');
    $slug = strtolower(str_replace(' ', '-', $title));
    
    // Simple file upload (optional - just store name for demo)
    $image = $_FILES['image']['name'] ?? '';
    
    if (!empty($title) && !empty($content)) {
        try {
            // Target directory
            $upload_dir = '../assets/img/news/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            // Handle main image upload
            $main_image_name = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $main_image_name = time() . '_' . uniqid() . '.' . $extension;
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $main_image_name);
            }

            // Insert news record
            $stmt = $pdo->prepare("INSERT INTO news (title, slug, content, image, news_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$title, $slug, $content, $main_image_name, $news_date]);
            $news_id = $pdo->lastInsertId();

            // Handle gallery images
            if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                foreach ($_FILES['gallery']['name'] as $key => $name) {
                    if ($_FILES['gallery']['error'][$key] === 0) {
                        $extension = pathinfo($name, PATHINFO_EXTENSION);
                        $gallery_image_name = time() . '_gallery_' . uniqid() . '.' . $extension;
                        if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $upload_dir . $gallery_image_name)) {
                            $stmt = $pdo->prepare("INSERT INTO news_images (news_id, image_path) VALUES (?, ?)");
                            $stmt->execute([$news_id, $gallery_image_name]);
                        }
                    }
                }
            }

            $success = "Berita berhasil ditambahkan!";
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "Judul dan konten wajib diisi.";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Tambah Berita Baru</h2>
    <a href="news.php" class="btn btn-outline-secondary btn-sm">Kembali</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Judul Berita</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Berita</label>
                <input type="date" name="news_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Utama</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Galeri (Bisa banyak)</label>
                <input type="file" name="gallery[]" class="form-control" multiple>
                <div class="form-text">Pilih satu atau lebih gambar untuk galeri berita.</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Konten</label>
                <textarea name="content" class="form-control" rows="10" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Berita</button>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
