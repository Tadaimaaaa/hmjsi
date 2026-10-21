<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

$error = '';
$success = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch current news data
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch();
    
    if (!$news) {
        header("Location: news.php");
        exit();
    }
    
    // Fetch gallery images
    $stmt = $pdo->prepare("SELECT * FROM news_images WHERE news_id = ?");
    $stmt->execute([$id]);
    $gallery = $stmt->fetchAll();
} else {
    header("Location: news.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $news_date = !empty($_POST['news_date']) ? $_POST['news_date'] : $news['news_date'];
    $slug = strtolower(str_replace(' ', '-', $title));
    $upload_dir = '../assets/img/news/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Handle gallery image deletion
    if (isset($_POST['delete_gallery_id'])) {
        $delete_id = $_POST['delete_gallery_id'];
        $stmt = $pdo->prepare("SELECT image_path FROM news_images WHERE id = ? AND news_id = ?");
        $stmt->execute([$delete_id, $id]);
        $img_to_delete = $stmt->fetch();
        
        if ($img_to_delete) {
            if (file_exists($upload_dir . $img_to_delete['image_path'])) {
                unlink($upload_dir . $img_to_delete['image_path']);
            }
            $stmt = $pdo->prepare("DELETE FROM news_images WHERE id = ?");
            $stmt->execute([$delete_id]);
            $success = "Gambar galeri berhasil dihapus!";
        }
    }
    
    // Handle image upload
    $image_name = $news['image']; // Default to old image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/img/news/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $new_image_name = time() . '_' . uniqid() . '.' . $extension;
        $target_file = $upload_dir . $new_image_name;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Delete old image if exists
            if ($news['image'] && file_exists($upload_dir . $news['image'])) {
                unlink($upload_dir . $news['image']);
            }
            $image_name = $new_image_name;
        }
    }
    
    if (!empty($title) && !empty($content)) {
        try {
            $stmt = $pdo->prepare("UPDATE news SET title = ?, slug = ?, content = ?, image = ?, news_date = ? WHERE id = ?");
            $stmt->execute([$title, $slug, $content, $image_name, $news_date, $id]);

            // Handle new gallery images
            if (isset($_FILES['gallery']) && !empty($_FILES['gallery']['name'][0])) {
                foreach ($_FILES['gallery']['name'] as $key => $name) {
                    if ($_FILES['gallery']['error'][$key] === 0) {
                        $extension = pathinfo($name, PATHINFO_EXTENSION);
                        $gallery_image_name = time() . '_gallery_' . uniqid() . '.' . $extension;
                        if (move_uploaded_file($_FILES['gallery']['tmp_name'][$key], $upload_dir . $gallery_image_name)) {
                            $stmt = $pdo->prepare("INSERT INTO news_images (news_id, image_path) VALUES (?, ?)");
                            $stmt->execute([$id, $gallery_image_name]);
                        }
                    }
                }
            }

            $success = "Berita berhasil diperbarui!";
            
            // Refresh data
            $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
            $stmt->execute([$id]);
            $news = $stmt->fetch();

            $stmt = $pdo->prepare("SELECT * FROM news_images WHERE news_id = ?");
            $stmt->execute([$id]);
            $gallery = $stmt->fetchAll();
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "Judul dan konten wajib diisi.";
    }
}

include 'partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Edit Berita</h2>
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
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($news['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Berita</label>
                <input type="date" name="news_date" class="form-control" value="<?php echo $news['news_date'] ? $news['news_date'] : date('Y-m-d', strtotime($news['created_at'])); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini</label>
                <div class="mb-2">
                    <?php if ($news['image']): ?>
                        <img src="../assets/img/news/<?php echo htmlspecialchars($news['image']); ?>" alt="" class="rounded" style="width: 150px; height: 100px; object-fit: cover;">
                    <?php else: ?>
                        <span class="text-muted small italic">Tidak ada gambar.</span>
                    <?php endif; ?>
                </div>
                <label class="form-label">Ganti Gambar Utama (Opsional)</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="mb-3">
                <label class="form-label">Galeri Saat Ini</label>
                <div class="row g-2 mb-2">
                    <?php if (count($gallery) > 0): ?>
                        <?php foreach ($gallery as $img): ?>
                            <div class="col-md-3 position-relative gallery-item">
                                <img src="../assets/img/news/<?php echo htmlspecialchars($img['image_path']); ?>" alt="" class="rounded w-100" style="height: 100px; object-fit: cover;">
                                <button type="submit" name="delete_gallery_id" value="<?php echo $img['id']; ?>" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="return confirm('Hapus gambar ini dari galeri?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <span class="text-muted small italic">Belum ada gambar galeri.</span>
                        </div>
                    <?php endif; ?>
                </div>
                <label class="form-label">Tambah Gambar Galeri</label>
                <input type="file" name="gallery[]" class="form-control" multiple>
            </div>
            <div class="mb-3">
                <label class="form-label">Konten</label>
                <textarea name="content" class="form-control" rows="10" required><?php echo htmlspecialchars($news['content']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
