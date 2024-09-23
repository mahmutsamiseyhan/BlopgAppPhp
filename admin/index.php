<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

// İstatistikleri çekelim
$blogSayisi = $baglanti->query("SELECT COUNT(*) FROM blog")->fetchColumn();
$kategoriSayisi = $baglanti->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
$yorumSayisi = $baglanti->query("SELECT COUNT(*) FROM yorumlar")->fetchColumn();
$kullaniciSayisi = $baglanti->query("SELECT COUNT(*) FROM kullanici")->fetchColumn();

// Moderatör ise kendi istatistiklerini gösterelim
if (moderatorYetkisi()) {
    $kullanici_id = $_SESSION['kullanici_id'];
    $blogSayisi = $baglanti->query("SELECT COUNT(*) FROM blog WHERE kullanici_id = $kullanici_id")->fetchColumn();
    $kategoriSayisi = $baglanti->query("SELECT COUNT(*) FROM kategori WHERE kullanici_id = $kullanici_id")->fetchColumn();
    $yorumSayisi = $baglanti->query("SELECT COUNT(*) FROM yorumlar WHERE kullanici_id = $kullanici_id")->fetchColumn();
}
?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $blogSayisi; ?></h3>
                            <p>Blog Yazıları</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="blog.php" class="small-box-footer">Daha fazla bilgi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?php echo $kategoriSayisi; ?></h3>
                            <p>Kategoriler</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="kategori.php" class="small-box-footer">Daha fazla bilgi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $yorumSayisi; ?></h3>
                            <p>Yorumlar</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="yorumlar.php" class="small-box-footer">Daha fazla bilgi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <?php if (adminYetkisi()): ?>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $kullaniciSayisi; ?></h3>
                            <p>Kullanıcılar</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="kullanicilar.php" class="small-box-footer">Daha fazla bilgi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php require_once '../partials/admin_footer.php'; ?>