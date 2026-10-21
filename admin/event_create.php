<?php
require_once '../config/db.php';
include 'partials/header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    if ($title && $event_date && $location && $description) {
        $stmt = $pdo->prepare("INSERT INTO events (title, event_date, location, description) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$title, $event_date, $location, $description])) {
            $success = "Event berhasil ditambahkan!";
        } else {
            $error = "Terjadi kesalahan saat menyimpan event.";
        }
    } else {
        $error = "Semua field wajib diisi!";
    }
}
?>

<div class="mb-4">
    <a href="events.php" class="btn btn-outline-secondary btn-sm">← Kembali ke Daftar Event</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0">Tambah Event Baru</h5>
    </div>
    <div class="card-body">
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label class="form-label">Judul Event</label>
                    <input type="text" name="title" class="form-control" required placeholder="Contoh: Webinar Nasional Sistem Informasi">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Pelaksanaan</label>
                    <input type="date" name="event_date" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" class="form-control" required placeholder="Contoh: Aula G3 UPI YPTK / Zoom Meeting">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="5" required placeholder="Jelaskan detail singkat tentang kegiatan ini..."></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-primary px-4">Simpan Event</button>
            </div>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
