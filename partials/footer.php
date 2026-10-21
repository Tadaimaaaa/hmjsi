<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">HMJ-SI</h5>
                <p>Himpunan Mahasiswa Jurusan Sistem Informasi. Wadah aspirasi dan pengembangan kreativitas mahasiswa SI.</p>
            </div>
            <div class="col-md-4 mb-4">
                <h5 class="fw-bold mb-3">Tautan Cepat</h5>
                <ul class="list-unstyled">
                    <li><a href="about.php" class="text-decoration-none">Tentang Kami</a></li>
                    <li><a href="news.php" class="text-decoration-none">Berita Terbaru</a></li>
                    <li><a href="events.php" class="text-decoration-none">Agenda Kegiatan</a></li>
                    <li><a href="contact.php" class="text-decoration-none">Hubungi Kami</a></li>
                </ul>
                <div class="social-links mt-4">
                    <a href="https://www.instagram.com/hmjsi_upiyptk?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw=="><i class="bi bi-instagram"></i></a>
                    <a href="https://www.facebook.com/hmjsiupiyptk.upiyptk/"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.tiktok.com/@hmjsi_upiyptk?_r=1&_t=ZS-941CJX2JVh7"><i class="bi bi-tiktok"></i></a>
                    <a href="https://www.youtube.com/@hmjsisteminformasi-upiyptk6830"><i class="bi bi-youtube"></i></a>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <p class="small text-muted mt-3">Email: hmj.si.upi@gmail.com<br>
                Jl. Raya Lubuk Begalung, Lubuk Begalung Nan XX, Kec. Lubuk Begalung, Kota Padang, Sumatera Barat 25145.</p>
            </div>
        </div>
        <hr class="mt-4 mb-4">
        <div class="text-center">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Tadaimaaaa. All Rights Reserved.</p>
        </div>
    </div>
</footer>

<!-- Floating Music Player -->
<div class="music-control">
    <button class="music-btn" id="musicToggle" title="Play/Pause Music">
        <i class="bi bi-play-fill"></i>
    </button>
    <audio id="bgMusic" loop>
        <source src="assets/audio/music.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const music = document.getElementById('bgMusic');
    const toggle = document.getElementById('musicToggle');
    let isPlaying = false;

    // Set volume
    music.volume = 0.3;

    function toggleMusic() {
        if (isPlaying) {
            music.pause();
            toggle.classList.remove('playing');
        } else {
            music.play().catch(e => console.log("Autoplay blocked, waiting for interaction"));
            toggle.classList.add('playing');
        }
        isPlaying = !isPlaying;
    }

    toggle.addEventListener('click', toggleMusic);

    // Try to auto-play on first user interaction with the document
    document.addEventListener('click', function() {
        if (!isPlaying && music.paused) {
            // Uncomment if you want true autoplay after first click
            // toggleMusic();
        }
    }, { once: true });
});
</script>
</body>
</html>
