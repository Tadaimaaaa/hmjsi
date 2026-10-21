<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: messages.php");
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->execute([$id]);
$message = $stmt->fetch();

if (!$message) {
    header("Location: messages.php");
    exit();
}

include 'partials/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4 pt-3">
    <h2 class="fw-bold">Baca Pesan</h2>
    <a href="messages.php" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 fw-bold"><?php echo htmlspecialchars($message['subject']); ?></h5>
            <small class="text-muted">Dari: <?php echo htmlspecialchars($message['name']); ?> (<?php echo htmlspecialchars($message['email']); ?>)</small>
        </div>
        <div class="text-muted small">
            <?php echo date('d M Y, H:i', strtotime($message['created_at'])); ?>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="mb-4">
            <label class="text-muted small text-uppercase fw-bold mb-2 d-block">Konten Pesan:</label>
            <div class="p-3 bg-light rounded" style="white-space: pre-wrap; font-size: 1.05rem; line-height: 1.6;">
                <?php echo htmlspecialchars($message['message']); ?>
            </div>
        </div>
        
        <div class="d-flex gap-2">
            <a href="mailto:<?php echo htmlspecialchars($message['email']); ?>?subject=Re: <?php echo htmlspecialchars($message['subject']); ?>" class="btn btn-primary">
                <i class="bi bi-reply"></i> Balas via Email
            </a>
            <a href="message_delete.php?id=<?php echo $message['id']; ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus pesan ini?')">
                <i class="bi bi-trash"></i> Hapus Pesan
            </a>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
