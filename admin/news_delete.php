<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Get image name to delete file
    $stmt = $pdo->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch();

    if ($news) {
        if ($news['image']) {
            $image_path = '../assets/img/news/' . $news['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete gallery images from filesystem
        $stmt = $pdo->prepare("SELECT image_path FROM news_images WHERE news_id = ?");
        $stmt->execute([$id]);
        $gallery = $stmt->fetchAll();
        foreach ($gallery as $img) {
            $gallery_path = '../assets/img/news/' . $img['image_path'];
            if (file_exists($gallery_path)) {
                unlink($gallery_path);
            }
        }

        // Delete from DB
        $stmt = $pdo->prepare("DELETE FROM news WHERE id = ?");
        $stmt->execute([$id]);
    }
}

header("Location: news.php");
exit();
?>
