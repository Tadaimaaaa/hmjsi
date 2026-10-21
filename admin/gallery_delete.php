<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Get image filename to delete from disk
    $stmt = $pdo->prepare("SELECT image FROM gallery WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    if ($item) {
        $image_path = '../assets/img/gallery/' . $item['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        // Delete from database
        $stmt = $pdo->prepare("DELETE FROM gallery WHERE id = ?");
        $stmt->execute([$id]);
        
        header("Location: gallery.php?success=deleted");
        exit();
    }
}

header("Location: gallery.php");
exit();
?>
