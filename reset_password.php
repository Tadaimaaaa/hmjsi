<?php
require_once 'config/db.php';

// Atur password baru di sini
$email = 'admin@hmjsi.com';
$new_password = 'admin123'; // Silakan ganti dengan yang diinginkan

$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE email = ?");
    $stmt->execute([$hashed_password, $email]);
    echo "Sukses! Password untuk $email telah diubah menjadi: $new_password\n";
    echo "PENTING: Segera hapus file reset_password.php ini demi keamanan!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
