<?php
require_once '../config/db.php';
include 'partials/header.php';

$success = '';
$error = '';
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: events.php");
    exit;
}

// Fetch event data
$st = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$st->execute([$id]);
$event = $st->fetch();

if (!$event) {
    header("Location: events.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $event_date = $_POST['event_date'];
    $location = $_POST['location'];
    $description = $_POST['description'];

    if ($title && $event_date && $location && $description) {
        $stmt = $pdo->prepare("UPDATE events SET title = ?, event_date = ?, location = ?, description = ? WHERE id = ?");
        if ($stmt->execute([$title, $event_date, $location, $description, $id])) {
            $success = "Event berhasil diperbarui!";
            // Refresh data
            $event['title'] = $title;
            $event['event_date'] = $event_date;
            $event['location'] = $location;
            $event['description'] = $description;
        } else {
            $error = "Terjadi kesalahan saat memperbarui event.";
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
    <div class="card-header bg-white py-3 text-primary">
        <h5 class="fw-bold mb-0">Edit Agenda Kegiatan</h5>
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
                    <input type="text" name="title" class="form-control" required value="<?php echo htmlspecialchars($event['title']); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Pelaksanaan</label>
                    <input type="date" name="event_date" class="form-control" required value="<?php echo htmlspecialchars($event['event_date']); ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Lokasi</label>
                <input type="text" name="location" class="form-control" required value="<?php echo htmlspecialchars($event['location']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="5" required><?php echo htmlspecialchars($event['description']); ?></textarea>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-info text-white px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
