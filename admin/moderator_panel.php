<?php
require_once 'auth.php';

girisKontrol();

if (!moderatorYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Moderatörün blog ve yorum istatistiklerini çekelim
$kullanici_id = $_SESSION['kullanici_id'];

$blog_sayisi_sorgu = $baglanti->prepare("SELECT COUNT(*) FROM blog WHERE kullanici_id = :kullanici_id");
$blog_sayisi_sorgu->execute(['kullanici_id' => $kullanici_id]);
$blog_sayisi = $blog_sayisi_sorgu->fetchColumn();

$yorum_sayisi_sorgu = $baglanti->prepare("SELECT COUNT(*) FROM yorumlar y JOIN blog b ON y.blog_id = b.blog_id WHERE b.kullanici_id = :kullanici_id");
$yorum_sayisi_sorgu->execute(['kullanici_id' => $kullanici_id]);
$yorum_sayisi = $yorum_sayisi_sorgu->fetchColumn();

$onay_bekleyen_yorum_sorgu = $baglanti->prepare("SELECT COUNT(*) FROM yorumlar y JOIN blog b ON y.blog_id = b.blog_id WHERE b.kullanici_id = :kullanici_id AND y.yorum_onay = 0");
$onay_bekleyen_yorum_sorgu->execute(['kullanici_id' => $kullanici_id]);
$onay_bekleyen_yorum = $onay_bekleyen_yorum_sorgu->fetchColumn();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <h1 class="mt-4">Moderatör Paneli</h1>
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">Blog Sayısı: <?php echo $blog_sayisi; ?></div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="blog.php">Detaylar</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-warning text-white mb-4">
                        <div class="card-body">Toplam Yorum: <?php echo $yorum_sayisi; ?></div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="yorumlar.php">Detaylar</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">Onay Bekleyen Yorum: <?php echo $onay_bekleyen_yorum; ?></div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="yorumlar.php?onay=bekleyen">Detaylar</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Burada moderatörün son blogları ve yorumları listelenebilir -->
        </div>
    </section>
</div>
