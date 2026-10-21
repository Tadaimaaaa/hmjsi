<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM cadres WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: cadres.php");
exit();
?>
