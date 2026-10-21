<?php
require_once 'config/db.php';

// Fetch all images from members, cadres, and news
$gallery = [];

// From Gallery Table (New Admin Management)
$stmt = $pdo->query("SELECT title, image, photo_date, created_at FROM gallery ORDER BY photo_date DESC, created_at DESC");
while ($row = $stmt->fetch()) {
    $gallery[] = [
        'src' => 'assets/img/gallery/' . $row['image'],
        'title' => $row['title'],
        'date' => $row['photo_date'] ? date('d M Y', strtotime($row['photo_date'])) : ''
    ];
}

include 'partials/header.php';
?>

<section class="py-5 bg-light-blue min-vh-100">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold section-title">Galeri Foto</h2>
            <p class="text-muted">Dokumentasi kepengurusan dan kegiatan HMJ-SI.</p>
        </div>


        <!-- Gallery Grid -->
        <div class="row g-4" id="galleryGrid">
            <?php foreach ($gallery as $item): ?>
            <div class="col-sm-6 col-md-4 col-lg-3 gallery-col">
                <div class="gallery-item shadow-sm" data-bs-toggle="modal" data-bs-target="#imageModal" 
                     data-src="<?php echo $item['src']; ?>" 
                     data-title="<?php echo htmlspecialchars($item['title']); ?>"
                     data-date="<?php echo htmlspecialchars($item['date']); ?>">
                    <div class="gallery-img-container">
                        <img src="<?php echo $item['src']; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" loading="lazy">
                        <div class="gallery-overlay">
                            <div class="gallery-info">
                                <h6 class="mb-0 text-white fw-bold"><?php echo htmlspecialchars($item['title']); ?></h6>
                                <?php if ($item['date']): ?>
                                    <small class="text-white-50"><?php echo $item['date']; ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="gallery-icon">
                                <i class="bi bi-zoom-in"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Lightbox Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0 p-0 position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3 z-3" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="modalImg" src="" class="img-fluid rounded shadow-lg" alt="Preview">
                <div class="mt-3 text-white pb-3">
                    <h5 id="modalTitle" class="fw-bold mb-0"></h5>
                    <small id="modalDate" class="text-white-50 d-block mt-1"></small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalImg = document.getElementById('modalImg');
    const modalTitle = document.getElementById('modalTitle');

    // Modal data handling
    const galleryItems = document.querySelectorAll('.gallery-item');
    galleryItems.forEach(item => {
        item.addEventListener('click', () => {
            modalImg.src = item.getAttribute('data-src');
            modalTitle.textContent = item.getAttribute('data-title');
            document.getElementById('modalDate').textContent = item.getAttribute('data-date');
        });
    });
});
</script>

<?php include 'partials/footer.php'; ?>
