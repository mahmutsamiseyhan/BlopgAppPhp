<?php 
require_once 'partials/header.php';
require_once 'admin/auth.php';
require_once 'function.php';

// Hakkımızda bilgilerini veritabanından çekelim
$hakkimizdasor = $baglanti->prepare("SELECT * FROM hakkimizda WHERE hakkimizda_id = :id");
$hakkimizdasor->execute(['id' => 1]);
$hakkimizdacek = $hakkimizdasor->fetch(PDO::FETCH_ASSOC);

// Eğer hakkımızda bilgisi bulunamazsa hata sayfasına yönlendirelim
if (!$hakkimizdacek) {
    header("Location: hata.php");
    exit;
}
?>

<br><br><br>
<main id="main">
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-6 order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
                    <img src="admin/resimler/hakkimizda/<?php echo guvenliCikti($hakkimizdacek['hakkimizda_resim']); ?>"
                         class="img-fluid" 
                         alt="<?php echo guvenliCikti($hakkimizdacek['hakkimizda_baslik']); ?>">
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1 content">
                    <h3><?php echo guvenliCikti($hakkimizdacek['hakkimizda_baslik']); ?></h3>
                    <p class="font-italic">
                        <?php echo guvenliCikti($hakkimizdacek['hakkimizda_aciklama']); ?>
                    </p>
                    <?php if (!empty($hakkimizdacek['hakkimizda_vizyon'])) { ?>
                        <h4>Vizyonumuz</h4>
                        <p><?php echo guvenliCikti($hakkimizdacek['hakkimizda_vizyon']); ?></p>
                    <?php } ?>
                    <?php if (!empty($hakkimizdacek['hakkimizda_misyon'])) { ?>
                        <h4>Misyonumuz</h4>
                        <p><?php echo guvenliCikti($hakkimizdacek['hakkimizda_misyon']); ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'partials/footer.php'; ?>