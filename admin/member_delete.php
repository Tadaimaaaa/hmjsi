<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Get member to delete image file
    $stmt = $pdo->prepare("SELECT image FROM members WHERE id = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();

    if ($member && $member['image']) {
        $image_path = '../assets/img/members/' . $member['image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: members.php");
exit();
?>
