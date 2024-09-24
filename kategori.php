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
$bloglaror = $baglanti->prepare("SELECT * FROM blog ORDER BY blog_sira ASC");
$bloglaror->execute();
?>

<br><br>
<main id="main" data-aos="fade-in">
    <section id="courses" class="courses">
        <div class="container" data-aos="fade-up">
            <h2 class="mb-5"><?php echo guvenliCikti($kategoricek['kategori_baslik']); ?> Kategorisi</h2>
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <?php while ($blogcek = $bloglaror->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                        <div class="course-item">
                            <img style="height:250px" src="Admin/resimler/blog/<?php echo guvenliCikti($blogcek['blog_resim']); ?>" class="img-fluid" alt="<?php echo guvenliCikti($blogcek['blog_baslik']); ?>">
                            <div class="course-content">
                                <h3>
                                    <a href="blog-detay.php?blog_id=<?php echo $blogcek['blog_id']; ?>">
                                        <?php echo guvenliCikti($blogcek['blog_baslik']); ?>
                                    </a>
                                </h3>
                                <p>
                                    <?php 
                                    $aciklama = strip_tags($blogcek['blog_aciklama']);
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