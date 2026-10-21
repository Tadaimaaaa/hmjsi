<?php
require_once '../config/db.php';

// Get counts
$newsCount = $pdo->query("SELECT COUNT(*) FROM news")->fetchColumn();
$msgCount = $pdo->query("SELECT COUNT(*) FROM messages")->fetchColumn();
// Get latest period for accurate member count
$latestPeriod = $pdo->query("SELECT MAX(period) FROM members")->fetchColumn();
$memberCount = $pdo->prepare("SELECT COUNT(*) FROM members WHERE hierarchy_level > 1 AND period = ?");
$memberCount->execute([$latestPeriod]);
$memberCount = $memberCount->fetchColumn();
$eventCount = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();

// Get latest data for tables
$news = $pdo->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll();
$messages = $pdo->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT 5")->fetchAll();

include 'partials/header.php';
?>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card bg-primary text-white p-3 border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-uppercase small opacity-75 mb-1">Berita</h6>
                    <h2 class="fw-bold mb-0"><?php echo $newsCount; ?></h2>
                </div>
                <i class="bi bi-newspaper fs-4 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-success text-white p-3 border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-uppercase small opacity-75 mb-1">Pesan</h6>
                    <h2 class="fw-bold mb-0"><?php echo $msgCount; ?></h2>
                </div>
                <i class="bi bi-chat-dots fs-4 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-dark text-white p-3 border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-uppercase small opacity-75 mb-1">Pengurus HMJ</h6>
                    <h2 class="fw-bold mb-0"><?php echo $memberCount; ?></h2>
                </div>
                <i class="bi bi-people fs-4 opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card bg-info text-white p-3 border-0 shadow-sm h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="text-uppercase small opacity-75 mb-1">Event</h6>
                    <h2 class="fw-bold mb-0"><?php echo $eventCount; ?></h2>
                </div>
                <i class="bi bi-calendar-event fs-4 opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Latest News -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-newspaper me-2 text-primary"></i>Berita Terbaru</h6>
                <a href="news_create.php" class="btn btn-primary btn-sm rounded-pill px-3"><i class="bi bi-plus-lg"></i></a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Judul</th>
                                <th class="d-none-xs">Tanggal</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($news)): ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada berita.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($news as $n): ?>
                            <tr>
                                <td class="ps-4 fw-bold text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($n['title']); ?></td>
                                <td class="d-none-xs"><small class="text-muted"><?php echo date('d M Y', strtotime($n['created_at'])); ?></small></td>
                                <td class="text-end pe-4">
                                    <a href="news_edit.php?id=<?php echo $n['id']; ?>" class="btn btn-outline-warning btn-sm border-0"><i class="bi bi-pencil-square"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <!-- View All Link -->
                            <tr>
                                <td colspan="3" class="text-center p-3 border-0">
                                    <a href="news.php" class="text-decoration-none small fw-bold">Lihat Semua Berita <i class="bi bi-arrow-right"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Messages -->
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-chat-dots me-2 text-success"></i>Pesan Masuk</h6>
                <a href="messages.php" class="btn btn-outline-success btn-sm rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Pengirim</th>
                                <th class="d-none-xs">Subjek</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($messages)): ?>
                                <tr><td colspan="3" class="text-center py-4 text-muted small">Belum ada pesan masuk.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($messages as $m): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($m['name']); ?></div>
                                    <small class="text-muted d-block text-truncate" style="max-width: 120px;"><?php echo htmlspecialchars($m['email']); ?></small>
                                </td>
                                <td class="d-none-xs text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($m['subject']); ?></td>
                                <td class="text-end pe-4">
                                    <a href="message_view.php?id=<?php echo $m['id']; ?>" class="btn btn-sm btn-outline-info border-0"><i class="bi bi-eye"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'partials/footer.php'; ?>
