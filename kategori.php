<?php 
require_once 'partials/header.php';
require_once 'function.php';

// Kategori ID'sini güvenli bir şekilde alalım
$kategori_id = filter_input(INPUT_GET, 'kategori_id', FILTER_SANITIZE_NUMBER_INT);

if (!$kategori_id) {
    header("Location: hata.php");
    exit;
}

// Kategori bilgilerini çekelim
$kategorisor = $baglanti->prepare("SELECT * FROM kategori WHERE kategori_id = :kategori_id");
$kategorisor->execute(['kategori_id' => $kategori_id]);
$kategoricek = $kategorisor->fetch(PDO::FETCH_ASSOC);

if (!$kategoricek) {
    header("Location: hata.php");
    exit;
}

// İçerikleri çekelim
$iceriksor = $baglanti->prepare("SELECT * FROM icerik WHERE kategori_id = :kategori_id ORDER BY icerik_sira ASC");
$iceriksor->execute(['kategori_id' => $kategori_id]);
?>

<br><br>
<main id="main" data-aos="fade-in">
    <section id="courses" class="courses">
        <div class="container" data-aos="fade-up">
            <h2 class="mb-5"><?php echo guvenliCikti($kategoricek['kategori_baslik']); ?> Kategorisi</h2>
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <?php while ($icerikcek = $iceriksor->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                        <div class="course-item">
                            <img style="height:250px" src="Admin/resimler/icerik/<?php echo guvenliCikti($icerikcek['icerik_resim']); ?>" class="img-fluid" alt="<?php echo guvenliCikti($icerikcek['icerik_baslik']); ?>">
                            <div class="course-content">
                                <h3>
                                    <a href="icerik-detay.php?icerik_id=<?php echo $icerikcek['icerik_id']; ?>">
                                        <?php echo guvenliCikti($icerikcek['icerik_baslik']); ?>
                                    </a>
                                </h3>
                                <p>
                                    <?php 
                                    $aciklama = strip_tags($icerikcek['icerik_aciklama']);
                                    echo guvenliCikti(substr($aciklama, 0, 100)) . '...'; 
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>

<?php require_once 'partials/footer.php'; ?>