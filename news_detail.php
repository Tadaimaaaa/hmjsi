<?php
require_once 'config/db.php';

$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("SELECT * FROM news WHERE slug = ?");
$stmt->execute([$slug]);
$news = $stmt->fetch();

if (!$news) {
    header("Location: news.php");
    exit;
}

// Fetch additional gallery images
$stmt = $pdo->prepare("SELECT * FROM news_images WHERE news_id = ?");
$stmt->execute([$news['id']]);
$gallery = $stmt->fetchAll();

// Prepare all images (main + gallery)
$all_images = [];
if ($news['image']) {
    $all_images[] = $news['image'];
}
foreach ($gallery as $img) {
    $all_images[] = $img['image_path'];
}

include 'partials/header.php';
?>

<section class="py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php">Beranda</a></li>
                <li class="breadcrumb-item"><a href="news.php">Berita</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Berita</li>
            </ol>
        </nav>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($news['title']); ?></h1>
                <p class="text-muted small mb-4">Diposting pada: <?php echo date('d M Y', strtotime($news['news_date'])); ?></p>
                
                <?php if (count($all_images) > 1): ?>
                    <!-- News Carousel -->
                    <div id="newsCarousel" class="carousel slide mb-4 shadow-sm rounded overflow-hidden" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            <?php foreach ($all_images as $index => $img): ?>
                                <button type="button" data-bs-target="#newsCarousel" data-bs-slide-to="<?php echo $index; ?>" class="<?php echo $index === 0 ? 'active' : ''; ?>" aria-current="true"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner" style="height: 400px;">
                            <?php foreach ($all_images as $index => $img): ?>
                                <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?> h-100">
                                    <img src="assets/img/news/<?php echo htmlspecialchars($img); ?>" class="d-block w-100 h-100" style="object-fit: cover;" alt="...">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                <?php elseif (count($all_images) === 1): ?>
                    <img src="assets/img/news/<?php echo htmlspecialchars($all_images[0]); ?>" class="img-fluid rounded mb-4 w-100" style="max-height: 400px; object-fit: cover;" alt="...">
                <?php else: ?>
                    <img src="assets/img/news/news-placeholder.jpg" class="img-fluid rounded mb-4 w-100" alt="...">
                <?php endif; ?>
                
                <div class="content">
                    <?php echo nl2br(htmlspecialchars($news['content'])); ?>
                </div>
                
                <hr class="my-5">
                <a href="news.php" class="btn btn-outline-secondary">Kembali ke Berita</a>
            </div>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
