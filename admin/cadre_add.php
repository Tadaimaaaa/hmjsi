<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

// Fetch divisions for dropdown
$divisions = $pdo->query("SELECT * FROM divisions ORDER BY name ASC")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $bp = htmlspecialchars(trim($_POST['bp']));
    $role = htmlspecialchars(trim($_POST['role']));
    $division_id = (int)$_POST['division_id'];
    $period = htmlspecialchars(trim($_POST['period']));
    
    $image_name = '';

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/img/members/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = 'cadre_' . time() . '_' . uniqid() . '.' . $extension;
        $target_file = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $error = "Gagal mengunggah gambar.";
        }
    }

    if (!$error && (empty($name) || empty($bp) || empty($division_id) || empty($period))) {
        $error = "Semua field wajib harus diisi.";
    } elseif (!$error) {
        try {
            $stmt = $pdo->prepare("INSERT INTO cadres (name, bp, role, image, division_id, period) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $bp, $role, $image_name, $division_id, $period]);
            $success = "Kader berhasil ditambahkan.";
        } catch (PDOException $e) {
            $error = "Gagal menyimpan data: " . $e->getMessage();
        }
    }
}

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tambah Kader</h1>
    <a href="cadres.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger shadow-sm border-0"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success shadow-sm border-0"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm p-4">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Nama Lengkap Kader</label>
                <input type="text" name="name" class="form-control" placeholder="Masukkan nama kader" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">No BP / NIP</label>
                <input type="text" name="bp" class="form-control" placeholder="Contoh: 21101152630001" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Jabatan</label>
                <input type="text" name="role" class="form-control" placeholder="Contoh: Staff Divisi" value="Staff Divisi" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Periode</label>
                <input type="text" name="period" class="form-control" placeholder="Contoh: 2025/2026" value="2025/2026" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Divisi</label>
                <select name="division_id" class="form-select" required>
                    <option value="">-- Pilih Divisi --</option>
                    <?php foreach ($divisions as $div): ?>
                        <option value="<?php echo $div['id']; ?>"><?php echo htmlspecialchars($div['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Foto Kader (Opsional)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary px-4">Simpan Data Kader</button>
        </div>
    </form>
</div>

<?php include 'partials/footer.php'; ?>
