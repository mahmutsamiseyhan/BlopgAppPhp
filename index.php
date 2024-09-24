<?php
require_once 'partials/header.php';
require_once 'function.php';
?>

<section style="background-image:url(admin/resimler/slider/<?php echo guvenliCikti($slidercek['slider_resim']); ?>)" id="hero" class="d-flex justify-content-center align-items-center">
    <div class="container position-relative" data-aos="zoom-in" data-aos-delay="100">
        <h1><?php echo guvenliCikti($slidercek['slider_baslik']); ?></h1>
        <h2><?php echo guvenliCikti($slidercek['slider_aciklama']); ?></h2>
        <a href="<?php echo guvenliCikti($slidercek['slider_link']); ?>" class="btn-get-started"><?php echo guvenliCikti($slidercek['slider_buton']); ?></a>
    </div>
</section>

<main id="main">
    <!-- Blog Section -->
    <section id="blogs" class="blogs">
        <div class="container" data-aos="fade-up">
            <h2>Bloglar</h2>
            <div class="row" data-aos="zoom-in" data-aos-delay="100">
                <?php 
                $blogsor = $baglanti->prepare("SELECT * FROM blog ORDER BY blog_sira ASC LIMIT 12");
                $blogsor->execute();
                while ($blogcek = $blogsor->fetch(PDO::FETCH_ASSOC)) { ?>
                    <div class="col-lg-4 col-md-6 d-flex align-items-stretch">
                        <div class="course-item">
                            <img style="height: 350px;" src="Admin/resimler/blog/<?php echo guvenliCikti($blogcek['blog_resim']); ?>" class="img-fluid" alt="<?php echo guvenliCikti($blogcek['blog_baslik']); ?>">
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