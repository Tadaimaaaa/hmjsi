<?php
require_once 'config/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $stmt = $pdo->prepare("INSERT INTO messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            if ($stmt->execute([$name, $email, $subject, $message])) {
                $success = "Pesan Anda telah berhasil dikirim!";
            } else {
                $error = "Terjadi kesalahan saat mengirim pesan.";
            }
        } else {
            $error = "Format email tidak valid.";
        }
    } else {
        $error = "Semua kolom harus diisi.";
    }
}

include 'partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-5">
                <h2 class="fw-bold section-title">Hubungi Kami</h2>
                <p>Silakan kirimkan pertanyaan, saran, atau kritik Anda melalui formulir di bawah ini.</p>
                
                <div class="mt-4">
                    <div class="d-flex mb-3">
                        <div class="bg-primary text-white rounded p-2 me-3">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Alamat</h6>
                            <p class="text-muted small">Jl. Raya Lubuk Begalung, Lubuk Begalung Nan XX, Kec. Lubuk Begalung, Kota Padang, Sumatera Barat 25145.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="bg-primary text-white rounded p-2 me-3">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">Email</h6>
                            <p class="text-muted small">hmj.si.upi@gmail.com</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card p-4 shadow-sm border-0">
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subjek</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Pesan</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
