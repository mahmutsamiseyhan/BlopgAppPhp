<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!yetkiKontrol('icerik')) {
    header("Location: yetkisiz.php");
    exit;
}

$icerik_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

// İçeriği güvenli bir şekilde veritabanından çekelim
$iceriksor = $baglanti->prepare("SELECT * FROM icerik WHERE icerik_id = :icerik_id");
$iceriksor->execute(['icerik_id' => $icerik_id]);
$icerikcek = $iceriksor->fetch(PDO::FETCH_ASSOC);

if (!$icerikcek) {
    header("Location: hata.php");
    exit;
}

// Moderatör ise, sadece kendi içeriğini düzenleyebilsin
if (moderatorYetkisi() && $icerikcek['kullanici_id'] != $_SESSION['kullanici_id']) {
    header("Location: yetkisiz.php");
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
                            <h3 class="card-title">İçerik Düzenle</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="icerik_resim">Mevcut Resim</label>
                                    <img style="width:150px" src="resimler/icerik/<?php echo htmlspecialchars($icerikcek['icerik_resim']); ?>" alt="İçerik Resim">
                                </div>
                                <div class="form-group">
                                    <label for="resim">Yeni Resim (Opsiyonel)</label>
                                    <input name="resim" type="file" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="baslik">İçerik Başlık</label>
                                    <input value="<?php echo htmlspecialchars($icerikcek['icerik_baslik']); ?>" name="baslik" type="text" class="form-control" required>
                                </div>
                                <input type="hidden" name="katid" value="<?php echo htmlspecialchars($icerikcek['kategori_id']); ?>">
                                <div class="form-group">
                                    <label for="sira">İçerik Sıra</label>
                                    <input value="<?php echo htmlspecialchars($icerikcek['icerik_sira']); ?>" name="sira" type="number" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="aciklama">İçerik Açıklama</label>
                                    <textarea name="aciklama" id="editor1" class="ckeditor" required><?php echo htmlspecialchars($icerikcek['icerik_aciklama']); ?></textarea>
                                </div>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($icerikcek['icerik_id']); ?>">
                                <div class="form-group">
                                    <label for="icerikanahtar">İçerik Anahtar Kelime</label>
                                    <input value="<?php echo htmlspecialchars($icerikcek['icerik_anahtarkelime']); ?>" name="icerikanahtar" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button name="icerikduzenle" type="submit" class="btn btn-primary">Güncelle</button>
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