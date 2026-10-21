<?php
require_once '../config/db.php';

session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    if ($stmt->execute([$id])) {
        header("Location: events.php?success=Event berhasil dihapus");
    } else {
        header("Location: events.php?error=Gagal menghapus event");
    }
} else {
    header("Location: events.php");
}
exit;
