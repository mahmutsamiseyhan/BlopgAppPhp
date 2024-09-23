<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// İstatistikleri çekelim
$blog_sayisi = $baglanti->query("SELECT COUNT(*) FROM blog")->fetchColumn();
$kategori_sayisi = $baglanti->query("SELECT COUNT(*) FROM kategori")->fetchColumn();
$yorum_sayisi = $baglanti->query("SELECT COUNT(*) FROM yorumlar")->fetchColumn();
$kullanici_sayisi = $baglanti->query("SELECT COUNT(*) FROM kullanici")->fetchColumn();
$onay_bekleyen_yorum = $baglanti->query("SELECT COUNT(*) FROM yorumlar WHERE yorum_onay = 0")->fetchColumn();

?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Admin Dashboard</h1>
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
                            <h3><?php echo $blog_sayisi; ?></h3>
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
                            <h3><?php echo $kategori_sayisi; ?></h3>
                            <p>Kategoriler</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="kategoriler.php" class="small-box-footer">Daha fazla bilgi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?php echo $kullanici_sayisi; ?></h3>
                            <p>Kullanıcılar</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="kullanicilar.php" class="small-box-footer">Daha fazla bilgi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo $yorum_sayisi; ?></h3>
                            <p>Yorumlar</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="yorumlar.php" class="small-box-footer">Daha fazla bilgi <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Son Eklenen Blog Yazıları</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Başlık</th>
                                        <th>Yazar</th>
                                        <th>Tarih</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $son_bloglar = $baglanti->query("SELECT b.*, k.kullanici_adsoyad FROM blog b JOIN kullanici k ON b.kullanici_id = k.kullanici_id ORDER BY b.blog_zaman DESC LIMIT 5");
                                    while ($blog = $son_bloglar->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($blog['blog_baslik']) . "</td>
                                                <td>" . htmlspecialchars($blog['kullanici_adsoyad']) . "</td>
                                                <td>" . htmlspecialchars($blog['blog_zaman']) . "</td>
                                                <td><a href='blog-duzenle.php?id=" . $blog['blog_id'] . "' class='text-muted'><i class='fas fa-edit'></i></a></td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header border-0">
                            <h3 class="card-title">Son Yorumlar</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th>Yazar</th>
                                        <th>Blog</th>
                                        <th>Tarih</th>
                                        <th>Durum</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $son_yorumlar = $baglanti->query("SELECT y.*, b.blog_baslik FROM yorumlar y JOIN blog b ON y.blog_id = b.blog_id ORDER BY y.yorum_tarih DESC LIMIT 5");
                                    while ($yorum = $son_yorumlar->fetch(PDO::FETCH_ASSOC)) {
                                        $durum = $yorum['yorum_onay'] ? '<span class="badge badge-success">Onaylı</span>' : '<span class="badge badge-warning">Onay Bekliyor</span>';
                                        echo "<tr>
                                                <td>" . htmlspecialchars($yorum['yorum_adsoyad']) . "</td>
                                                <td>" . htmlspecialchars($yorum['blog_baslik']) . "</td>
                                                <td>" . htmlspecialchars($yorum['yorum_tarih']) . "</td>
                                                <td>$durum</td>
                                              </tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once '../partials/admin_footer.php'; ?>