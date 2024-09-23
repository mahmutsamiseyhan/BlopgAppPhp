<?php 
require_once 'partials/header.php';
require_once 'admin/auth.php';

// Sadece adminlerin erişimine izin verelim
if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Galeri resimlerini veritabanından çekelim
$galerisor = $baglanti->prepare("SELECT * FROM galeri ORDER BY galeri_sira ASC");
$galerisor->execute();
?>

<br><br>
<main id="main" data-aos="fade-in">
    <!-- Courses Section -->
    <section id="courses" class="courses">
        <div class="container" data-aos="fade-up">
            <h2 class="text-center mb-5">Fotoğraf Galerisi</h2>
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <?php while ($galericek = $galerisor->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch mt-4">
                        <div class="course-item">
                            <img src="admin/resimler/galeri/<?php echo guvenliCikti($galericek['galeri_resim']); ?>" 
                                 class="img-fluid" 
                                 alt="Galeri Resim <?php echo guvenliCikti($galericek['galeri_id']); ?>">
                            <?php if (!empty($galericek['galeri_baslik'])) { ?>
                                <div class="course-content">
                                    <h4><?php echo guvenliCikti($galericek['galeri_baslik']); ?></h4>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'partials/footer.php'; ?>