<?php
require_once '../config/db.php';
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: cadres.php");
    exit();
}

// Fetch cadre data
$stmt = $pdo->prepare("SELECT * FROM cadres WHERE id = ?");
$stmt->execute([$id]);
$cadre = $stmt->fetch();

if (!$cadre) {
    header("Location: cadres.php");
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
    
    $image_name = $cadre['image'];

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/img/members/';
        
        // Delete old image if exists
        if ($cadre['image'] && file_exists($upload_dir . $cadre['image'])) {
            unlink($upload_dir . $cadre['image']);
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
            $stmt = $pdo->prepare("UPDATE cadres SET name = ?, bp = ?, role = ?, image = ?, division_id = ?, period = ? WHERE id = ?");
            $stmt->execute([$name, $bp, $role, $image_name, $division_id, $period, $id]);
            $success = "Data kader berhasil diperbarui.";
            // Update local variable for display
            $cadre['name'] = $name;
            $cadre['bp'] = $bp;
            $cadre['role'] = $role;
            $cadre['image'] = $image_name;
            $cadre['division_id'] = $division_id;
            $cadre['period'] = $period;
        } catch (PDOException $e) {
            $error = "Gagal memperbarui data: " . $e->getMessage();
        }
    }
}

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Kader</h1>
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
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($cadre['name']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">No BP / NIP</label>
                <input type="text" name="bp" class="form-control" value="<?php echo htmlspecialchars($cadre['bp']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Jabatan</label>
                <input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($cadre['role']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Periode</label>
                <input type="text" name="period" class="form-control" value="<?php echo htmlspecialchars($cadre['period']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Divisi</label>
                <select name="division_id" class="form-select" required>
                    <?php foreach ($divisions as $div): ?>
                        <option value="<?php echo $div['id']; ?>" <?php echo ($cadre['division_id'] == $div['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($div['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label fw-bold">Ganti Foto (Kosongkan jika tidak ingin mengubah)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if ($cadre['image']): ?>
                    <div class="mt-2">
                        <small class="text-muted">Foto saat ini:</small><br>
                        <img src="../assets/img/members/<?php echo $cadre['image']; ?>" width="60" class="img-thumbnail mt-1">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php include 'partials/footer.php'; ?>
