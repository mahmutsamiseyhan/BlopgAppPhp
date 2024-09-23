<?php 
require_once 'partials/header.php';
require_once 'admin/auth.php';

// İçerik ID'sini güvenli bir şekilde alalım
$icerik_id = filter_input(INPUT_GET, 'icerik_id', FILTER_SANITIZE_NUMBER_INT);

if (!$icerik_id) {
    header("Location: hata.php");
    exit;
}

// İçerik bilgilerini veritabanından çekelim
$iceriksor = $baglanti->prepare("SELECT * FROM icerik WHERE icerik_id = :icerik_id");
$iceriksor->execute(['icerik_id' => $icerik_id]);
$icerikcek = $iceriksor->fetch(PDO::FETCH_ASSOC);

if (!$icerikcek) {
    header("Location: hata.php");
    exit;
}
?>

<br><br>
<main id="main">
    <section id="course-details" class="course-details">
        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col-lg-8">
                    <img src="admin/resimler/icerik/<?php echo guvenliCikti($icerikcek['icerik_resim']); ?>" 
                         class="img-fluid" 
                         alt="<?php echo guvenliCikti($icerikcek['icerik_baslik']); ?>">
                    <h3><?php echo guvenliCikti($icerikcek['icerik_baslik']); ?></h3>
                    <p><?php echo nl2br(guvenliCikti($icerikcek['icerik_aciklama'])); ?></p>
                    
                    <h4>Yorumlar</h4>
                    <?php
                    $yorumlarsor = $baglanti->prepare("SELECT * FROM yorumlar WHERE yorum_kategori = :yorum_kategori AND icerik_id = :icerik_id AND yorum_onay = :yorum_onay");
                    $yorumlarsor->execute([
                        'yorum_kategori' => 2,
                        'icerik_id' => $icerik_id,
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
                                <input type="text" name="adsoyad" class="form-control" id="name" placeholder="Adınız ve soyadınız" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="detay" rows="5" placeholder="Mesajınız" required></textarea>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $icerik_id; ?>">
                        <input type="hidden" name="kategori" value="2">
                        <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                        <div class="text-center">
                            <button name="icerikyorumkaydet" class="btn btn-primary" type="submit">Gönder</button>
                        </div>
                    </form>
                </div>
                
                <div class="col-lg-4">
                    <div class="course-info d-flex justify-content-between align-items-center">
                        <h5>Eklenme Tarihi:</h5>
                        <p><?php echo guvenliCikti($icerikcek['icerik_zaman']); ?></p>
                    </div>
                    <div class="course-info d-flex justify-content-between align-items-center">
                        <h5>Ekleyen:</h5>
                        <p>Admin</p>
                    </div>
                    <div class="course-info d-flex justify-content-between align-items-center">
                        <h5>İletişim:</h5>
                        <p>info@yazilimyolcusu.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require_once 'partials/footer.php'; ?>