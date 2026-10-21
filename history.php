<?php include 'partials/header.php'; ?>

<header class="history-hero py-5">
    <div class="container text-center py-5">
        <div class="hero-logo-wrapper mb-4">
            <img src="assets/img/logo.png" alt="Logo HMJ-SI" class="img-fluid history-logo animate-float">
        </div>
        <div class="hero-text-content">
            <h1 class="display-3 fw-bold mb-3 text-white history-title">SEJARAH HMJ-SI</h1>
            <p class="lead text-white-50 fw-medium">Himpunan Mahasiswa Jurusan Sistem Informasi</p>
            <div class="divider-glow mx-auto mb-3"></div>
            <p class="text-white opacity-75">Universitas Putra Indonesia "YPTK" Padang</p>
        </div>
    </div>
</header>

<style>
.history-hero {
    background: linear-gradient(rgba(15, 23, 42, 0.4), rgba(15, 23, 42, 0.6)), url('assets/img/sky-bg.png');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    min-height: 350px;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
    padding-top: 80px !important;
}

.history-logo {
    max-height: 120px;
    filter: drop-shadow(0 0 20px rgba(255, 255, 255, 0.3));
}

.history-title {
    text-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    letter-spacing: 2px;
}

.divider-glow {
    width: 80px;
    height: 4px;
    background: var(--primary);
    border-radius: 2px;
    box-shadow: 0 0 15px var(--primary);
}

.animate-float {
    animation: floating 3s ease-in-out infinite;
}

@keyframes floating {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
    100% { transform: translateY(0px); }
}

.bg-light-blue {
    background-color: #f8fafc;
}
</style>

<section class="py-5 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-5 rounded-4">
                    <h2 class="fw-bold mb-4 text-primary">Asal Usul & Kebangkitan Kembali</h2>
                    <p class="lead text-muted mb-4">HMJ-SI didirikan kembali pada tanggal 29 Februari 2012. Momen ini menjadi tonggak sejarah penting bagi mahasiswa Sistem Informasi di UPI "YPTK" Padang.</p>
                    
                    <p>Himpunan Mahasiswa Jurusan Sistem Informasi (HMJ-SI) hadir sebagai respons atas kebutuhan mahasiswa akan wadah yang dapat menampung aspirasi, kreativitas, dan kolaborasi di bidang teknologi informasi. Sejak didirikan kembali pada tahun 2012, organisasi ini telah bertransformasi menjadi salah satu himpunan mahasiswa paling dinamis di lingkungan FILKOM.</p>

                    <div class="my-5 p-4 bg-light-blue rounded-3 border-start border-4 border-primary">
                        <h5 class="fw-bold mb-2 italic">"Mewujudkan himpunan yang berintegritas dan memiliki jiwa kolaboratif."</h5>
                        <p class="mb-0 small text-muted">— Visi Kepengurusan HMJ-SI</p>
                    </div>

                    <h4 class="fw-bold mb-3">Perjalanan Dari Masa ke Masa</h4>
                    <p>Selama lebih dari satu dekade, HMJ-SI telah melahirkan banyak pemimpin hebat yang kini telah berkarier di berbagai perusahaan teknologi ternama. Prestasi demi prestasi, baik di bidang akademik maupun non-akademik, terus diraih sebagai bukti nyata dedikasi seluruh pengurus dan anggota.</p>
                    
                    <p>Fokus utama kami tetap konsisten: "Integritas, Inovasi, dan Kolaborasi". Ketiga pilar ini menjadi jiwa dari jargon kebanggaan kami, "SI BIRU BATIK!", yang melambangkan identitas dan semangat juang mahasiswa Sistem Informasi.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-light-blue">
    <div class="container text-center">
        <h3 class="fw-bold mb-5">Timeline Perjalanan Kami</h3>
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="position-relative py-4">
                    <!-- Timeline Line -->
                    <div style="position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; background: #e2e8f0; transform: translateX(-50%);"></div>
                    
                    <!-- 2012 -->
                    <div class="row mb-5">
                        <div class="col-6 text-end pe-5">
                            <h4 class="fw-bold text-primary mb-1">2012</h4>
                            <p class="mb-0">Diresmikan kembali pada 29 Februari sebagai wadah resmi mahasiswa SI.</p>
                        </div>
                        <div class="col-6"></div>
                    </div>

                    <!-- Expansion -->
                    <div class="row mb-5">
                        <div class="col-6"></div>
                        <div class="col-6 text-start ps-5">
                            <h4 class="fw-bold text-primary mb-1">2012 - Sekarang</h4>
                            <p class="mb-0">Terus bertumbuh dengan 6 divisi aktif dan ratusan program kerja sukses.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>
