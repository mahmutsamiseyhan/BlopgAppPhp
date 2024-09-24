<?php 
require_once 'partials/header.php';
require_once 'admin/auth.php';

// // Yetki kontrolü
// if (!adminYetkisi() && !moderatorYetkisi()) {
//     header("Location: yetkisiz.php");
//     exit;
// }

// Blog ve içerik bilgilerini çekme
if (adminYetkisi()) {
    $blogsor = $baglanti->prepare("
        SELECT i.*, k.kategori_baslik 
        FROM icerik i 
        LEFT JOIN kategori k ON i.kategori_id = k.kategori_id 
        ORDER BY i.icerik_sira ASC
    ");
} else {
    $blogsor = $baglanti->prepare("
        SELECT i.*, k.kategori_baslik 
        FROM icerik i 
        LEFT JOIN kategori k ON i.kategori_id = k.kategori_id 
        WHERE i.kullanici_id = :kullanici_id
        ORDER BY i.icerik_sira ASC
    ");
    $blogsor->bindParam(':kullanici_id', $_SESSION['kullanici_id']);
}
$blogsor->execute();

$bloglaror = $baglanti->prepare("SELECT * FROM blog ORDER BY blog_sira ASC");
$bloglaror->execute();
?>

<br><br>
<main id="main" data-aos="fade-in">
    <!-- Blog Section -->
    <section id="blogs" class="blogs">
        <div class="container" data-aos="fade-up">
            <h2>Bloglar</h2>
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <?php while ($blogcek = $bloglaror->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                        <div class="course-item">
                            <img src="Admin/resimler/blog/<?php echo htmlspecialchars($blogcek['blog_resim']); ?>"
                                 class="img-fluid" alt="<?php echo htmlspecialchars($blogcek['blog_baslik']); ?>">
                            <div class="course-content">
                                <h3>
                                    <a href="blog-detay.php?blog_id=<?php echo $blogcek['blog_id']; ?>">
                                        <?php echo htmlspecialchars($blogcek['blog_baslik']); ?>
                                    </a>
                                </h3>
                                <p>
                                    <?php 
                                    $aciklama = strip_tags($blogcek['blog_aciklama']);
                                    echo substr($aciklama, 0, 50) . '...';
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