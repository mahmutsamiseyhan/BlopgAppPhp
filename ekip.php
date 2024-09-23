<?php 
require_once 'partials/header.php';
require_once 'admin/auth.php';

// Sadece adminlerin erişimine izin verelim
if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Ekip üyelerini veritabanından çekelim
$ekipsor = $baglanti->prepare("SELECT * FROM ekip ORDER BY ekip_sira ASC");
$ekipsor->execute();
?>

<br><br>
<main id="main" data-aos="fade-in">
    <!-- Trainers Section -->
    <section id="trainers" class="trainers">
        <div class="container" data-aos="fade-up">
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <?php while ($ekipcek = $ekipsor->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                        <div class="member">
                            <img src="Admin/resimler/ekip/<?php echo htmlspecialchars($ekipcek['ekip_resim']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($ekipcek['ekip_isim']); ?>">
                            <div class="member-content">
                                <h4><?php echo htmlspecialchars($ekipcek['ekip_isim']); ?></h4>
                                <span><?php echo htmlspecialchars($ekipcek['ekip_konum']); ?></span>
                                <p><?php echo htmlspecialchars($ekipcek['ekip_aciklama']); ?></p>
                                <div class="social">
                                    <?php if (!empty($ekipcek['ekip_twitter'])) { ?>
                                        <a href="<?php echo htmlspecialchars($ekipcek['ekip_twitter']); ?>" target="_blank"><i class="icofont-twitter"></i></a>
                                    <?php } ?>
                                    <?php if (!empty($ekipcek['ekip_instagram'])) { ?>
                                        <a href="<?php echo htmlspecialchars($ekipcek['ekip_instagram']); ?>" target="_blank"><i class="icofont-instagram"></i></a>
                                    <?php } ?>
                                    <?php if (!empty($ekipcek['ekip_youtube'])) { ?>
                                        <a href="<?php echo htmlspecialchars($ekipcek['ekip_youtube']); ?>" target="_blank"><i class="icofont-youtube"></i></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'partials/footer.php'; ?>