<?php
require_once 'config/db.php';

$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date ASC");
$events = $stmt->fetchAll();

include 'partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-5 section-title">Agenda Kegiatan</h2>
        <div class="row g-4">
            <?php if (empty($events)): ?>
                <div class="col-12">
                    <div class="alert alert-info">Belum ada agenda kegiatan yang terdaftar.</div>
                </div>
            <?php else: ?>
                <?php foreach ($events as $event): ?>
                <div class="col-md-6">
                    <div class="card mb-3 shadow-sm border-0">
                        <div class="row g-0">
                            <div class="col-sm-4 bg-primary text-white d-flex align-items-center justify-content-center flex-column p-3 rounded-start">
                                <h3 class="mb-0 fw-bold"><?php echo date('d', strtotime($event['event_date'])); ?></h3>
                                <span><?php echo date('M', strtotime($event['event_date'])); ?></span>
                                <span class="small"><?php echo date('Y', strtotime($event['event_date'])); ?></span>
                            </div>
                            <div class="col-sm-8">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <p class="card-text text-muted small mb-2"><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                                    <p class="card-text"><?php echo htmlspecialchars($event['description']); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
