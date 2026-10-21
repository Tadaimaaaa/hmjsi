<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once '../config/db.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: members.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch();

if (!$member) {
    header("Location: members.php");
    exit();
}

$error = '';
$success = '';

// Fetch divisions for dropdown
$divisions = $pdo->query("SELECT * FROM divisions")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $bp = htmlspecialchars(trim($_POST['bp']));
    $role = htmlspecialchars(trim($_POST['role']));
    $period = htmlspecialchars(trim($_POST['period']));
    $hierarchy = (int)$_POST['hierarchy_level'];
    $division_id = !empty($_POST['division_id']) ? (int)$_POST['division_id'] : null;
    
    $image_name = $member['image'];

    // Handle Image Upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $upload_dir = '../assets/img/members/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Delete old image if exists
        if ($member['image'] && file_exists($upload_dir . $member['image'])) {
            unlink($upload_dir . $member['image']);
        }

        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $image_name = time() . '_' . uniqid() . '.' . $extension;
        $target_file = $upload_dir . $image_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $error = "Gagal mengunggah gambar.";
        }
    }

    if (!$error) {
        try {
            $stmt = $pdo->prepare("UPDATE members SET name = ?, bp = ?, role = ?, image = ?, period = ?, hierarchy_level = ?, division_id = ? WHERE id = ?");
            $stmt->execute([$name, $bp, $role, $image_name, $period, $hierarchy, $division_id, $id]);
            $success = "Data pengurus berhasil diperbarui.";
            // Update local member data for display
            $member['name'] = $name;
            $member['bp'] = $bp;
            $member['role'] = $role;
            $member['image'] = $image_name;
            $member['period'] = $period;
            $member['hierarchy_level'] = $hierarchy;
            $member['division_id'] = $division_id;
        } catch (PDOException $e) {
            $error = "Gagal memperbarui data: " . $e->getMessage();
        }
    }
}

include 'partials/header.php';
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Pengurus</h1>
    <a href="members.php" class="btn btn-secondary">Kembali</a>
</div>

<?php if ($error): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm p-4">
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($member['name']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">No BP / NIP</label>
                <input type="text" name="bp" class="form-control" value="<?php echo htmlspecialchars($member['bp']); ?>">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Jabatan</label>
                <input type="text" name="role" class="form-control" value="<?php echo htmlspecialchars($member['role']); ?>" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Periode</label>
                <input type="text" name="period" class="form-control" value="<?php echo htmlspecialchars($member['period']); ?>" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Tingkat Hirarki</label>
                <select name="hierarchy_level" class="form-select">
                    <option value="1" <?php if($member['hierarchy_level'] == 1) echo 'selected'; ?>>1 - Ketua Jurusan</option>
                    <option value="2" <?php if($member['hierarchy_level'] == 2) echo 'selected'; ?>>2 - Bupati</option>
                    <option value="3" <?php if($member['hierarchy_level'] == 3) echo 'selected'; ?>>3 - Wakil Bupati</option>
                    <option value="4" <?php if($member['hierarchy_level'] == 4) echo 'selected'; ?>>4 - BPH Himpunan (Sekretaris/Bendahara)</option>
                    <option value="6" <?php if($member['hierarchy_level'] == 6) echo 'selected'; ?>>6 - Kepala Divisi (Kabag)</option>
                    <option value="7" <?php if($member['hierarchy_level'] == 7) echo 'selected'; ?>>7 - Sekretaris/Bendahara Divisi</option>
                    <option value="8" <?php if($member['hierarchy_level'] == 8) echo 'selected'; ?>>8 - Kepala Bidang (Kabid)</option>
                    <option value="9" <?php if($member['hierarchy_level'] == 9) echo 'selected'; ?>>9 - Staff Divisi</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Asosiasi Divisi</label>
                <select name="division_id" class="form-select">
                    <option value="">-- Bukan Bagian Divisi (BPH/Pimpinan) --</option>
                    <?php foreach ($divisions as $div): ?>
                        <option value="<?php echo $div['id']; ?>" <?php if($member['division_id'] == $div['id']) echo 'selected'; ?>><?php echo htmlspecialchars($div['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Ganti Foto (Kosongkan jika tidak ingin mengubah)</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                <?php if ($member['image']): ?>
                    <div class="mt-2">
                        <small class="text-muted">Foto saat ini:</small><br>
                        <img src="../assets/img/members/<?php echo $member['image']; ?>" width="80" class="img-thumbnail mt-1">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Simpan Perubahan</button>
    </form>
</div>

<?php include 'partials/footer.php'; ?>
