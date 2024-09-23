<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

$galeri_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

$galerisor = $baglanti->prepare("SELECT * FROM galeri WHERE galeri_id = :galeri_id");
$galerisor->execute(['galeri_id' => $galeri_id]);
$galericek = $galerisor->fetch(PDO::FETCH_ASSOC);

if (!$galericek) {
    header("Location: hata.php");
    exit;
}
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Galeri Resmi Düzenle</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="galeri_resim">Mevcut Resim</label>
                                    <img src="resimler/galeri/<?php echo htmlspecialchars($galericek['galeri_resim']); ?>" alt="Galeri Resim" style="max-width:200px;">
                                </div>
                                <div class="form-group">
                                    <label for="resim">Yeni Resim (Opsiyonel)</label>
                                    <input type="file" name="resim" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="baslik">Başlık</label>
                                    <input type="text" name="baslik" class="form-control" value="<?php echo htmlspecialchars($galericek['galeri_baslik']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="sira">Sıra</label>
                                    <input type="number" name="sira" class="form-control" value="<?php echo htmlspecialchars($galericek['galeri_sira']); ?>" required>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $galeri_id; ?>">
                                <input type="hidden" name="eskiresim" value="<?php echo htmlspecialchars($galericek['galeri_resim']); ?>">
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="galeriduzenle" class="btn btn-primary">Güncelle</button>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once '../partials/admin_footer.php'; ?>