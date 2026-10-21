<?php
require_once 'config/db.php';

$stmt = $pdo->query("SELECT * FROM news ORDER BY news_date DESC, created_at DESC");
$news_list = $stmt->fetchAll();

include 'partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <h2 class="fw-bold mb-5 section-title">Berita Utama</h2>
        <div class="row g-4">
            <?php foreach ($news_list as $news): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="assets/img/news/<?php echo $news['image'] ? htmlspecialchars($news['image']) : 'news-placeholder.jpg'; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($news['title']); ?></h5>
                        <p class="card-text text-muted small"><?php echo date('d M Y', strtotime($news['news_date'])); ?></p>
                        <p class="card-text"><?php echo htmlspecialchars(substr($news['content'], 0, 100)); ?>...</p>
                        <a href="news_detail.php?slug=<?php echo $news['slug']; ?>" class="btn btn-outline-primary btn-sm">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
