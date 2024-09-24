<?php 
require_once 'partials/header.php';
require_once 'admin/auth.php';
require_once 'function.php';

// Kullanıcıları veritabanından çekelim
$kullanicisor = $baglanti->prepare("SELECT * FROM kullanici ORDER BY kullanici_id DESC");
$kullanicisor->execute();

// Blog verilerini çekme
$blogsor = $baglanti->prepare("SELECT * FROM blog WHERE blog_id = :blog_id");
$blogsor->execute(['blog_id' => $_GET['blog_id']]);
$blogcek = $blogsor->fetch(PDO::FETCH_ASSOC);

if (!$blogcek) {
    // Blog bulunamadıysa hata sayfasına yönlendir
    header("Location: hata.php");
    exit;
}

// Yazar bilgilerini almak için kullanıcı ID'sini kullan
$yazar_id = $blogcek['kullanici_id']; // blog tablosundaki yazarın ID'si
$yazarsor = $baglanti->prepare("SELECT * FROM kullanici WHERE kullanici_id = :kullanici_id");
$yazarsor->execute(['kullanici_id' => $yazar_id]);
$yazarcek = $yazarsor->fetch(PDO::FETCH_ASSOC);

?>

<br><br>
<main id="main">
    <section id="course-details" class="course-details">
        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-8">
                    <img src="admin/resimler/blog/<?php echo guvenliCikti($blogcek['blog_resim']); ?>" class="img-fluid" alt="<?php echo guvenliCikti($blogcek['blog_baslik']); ?>">
                    <h3><?php echo guvenliCikti($blogcek['blog_baslik']); ?></h3>
                    <p><?php echo guvenliCikti($blogcek['blog_aciklama']); ?></p>

                    <h4>Yorumlar</h4>
                    <?php
                    $yorumlarsor = $baglanti->prepare("SELECT * FROM yorumlar WHERE yorum_kategori = :yorum_kategori AND blog_id = :blog_id AND yorum_onay = :yorum_onay");
                    $yorumlarsor->execute([
                        'yorum_kategori' => 1,
                        'blog_id' => $_GET['blog_id'],
                        'yorum_onay' => 1
                    ]);
                    while ($yorumlarcek = $yorumlarsor->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                        <div class="course-info d-flex justify-content-between align-items-center">
                            <h5>Yazan kişi: <?php echo guvenliCikti($yorumlarcek['yorum_adsoyad']); ?></h5>
                            <p><?php echo guvenliCikti($yorumlarcek['yorum_detay']); ?></p>
                        </div>
                    <?php } ?>

                    <h4>Yorum Yaz</h4>
                    <form action="admin/islem.php" method="post">
                        <div class="form-row">
                            <div class="col-md-12 form-group">
                                <input type="text" name="adsoyad" class="form-control" id="name" placeholder="Adınız ve soyadınız" required />
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="detay" rows="5" required placeholder="Mesajınız"></textarea>
                        </div>
                        <input type="hidden" name="id" value="<?php echo guvenliCikti($blogcek['blog_id']); ?>">
                        <input type="hidden" name="kategori" value="1">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="text-center">
                            <button name="blogyorumkaydet" class="btn btn-info" type="submit">Gönder</button>
                        </div>
                    </form>
                </div>

                <div class="col-lg-4">
                    <?php if($yazarcek) { ?>
                        <div class="course-info d-flex justify-content-between align-items-center">
                            <h5>Eklenme Tarihi:</h5>
                            <p><?php echo guvenliCikti($blogcek['blog_zaman']); ?></p>
                        </div>
                        <div class="course-info d-flex justify-content-between align-items-center">
                            <h5>Ekleyen:</h5>
                            <p><?php echo guvenliCikti($yazarcek['kullanici_adsoyad']); ?></p>
                        </div>
                        <div class="course-info d-flex justify-content-between align-items-center">
                            <h5>İletişim:</h5>
                            <p><?php echo guvenliCikti($yazarcek['kullanici_email']); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'partials/footer.php'; ?>
