<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Galeriyi veritabanından güvenli bir şekilde çekelim
$galerisor = $baglanti->prepare("SELECT * FROM galeri ORDER BY galeri_sira ASC");
$galerisor->execute();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <?php if (isset($_GET['durum'])) { 
                    $durum = $_GET['durum'];
                    $mesaj = $durum == "ok" ? "İşlem başarılı" : "İşlem başarısız";
                    $renk = $durum == "ok" ? "success" : "danger";
                ?>
                    <div class="col-12">
                        <div class="alert alert-<?php echo $renk; ?>" role="alert">
                            <?php echo $mesaj; ?>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fotoğraf Galerisi</h3>
                            <a href="ekle.php?sayfa=galeri" class="btn btn-info float-right">Yeni galeri ekle</a>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Galeri Resim</th>
                                        <th>Galeri Sıra</th>
                                        <th>Düzenle</th>
                                        <th>Sil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($galericek = $galerisor->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td><img style="width:150px" src="resimler/galeri/<?php echo htmlspecialchars($galericek['galeri_resim']); ?>" alt="Galeri Resim"></td>
                                            <td><?php echo htmlspecialchars($galericek['galeri_sira']); ?></td>
                                            <td><a href="duzenle.php?sayfa=galeri&id=<?php echo $galericek['galeri_id']; ?>" class="btn btn-success">Düzenle</a></td>
                                            <td>
                                                <form action="islem.php" method="post" onsubmit="return confirm('Bu galeri resmini silmek istediğinizden emin misiniz?');">
                                                    <input type="hidden" name="id" value="<?php echo $galericek['galeri_id']; ?>">
                                                    <input type="hidden" name="eskiresim" value="<?php echo $galericek['galeri_resim']; ?>">
                                                    <button name="galerisil" type="submit" class="btn btn-danger">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php } ?>
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