<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Hakkımızda verisini veritabanından güvenli bir şekilde çekelim
$hakkimizdasor = $baglanti->prepare("SELECT * FROM hakkimizda WHERE hakkimizda_id = :id");
$hakkimizdasor->execute(['id' => 1]);
$hakkimizdacek = $hakkimizdasor->fetch(PDO::FETCH_ASSOC);
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <?php if (isset($_GET['durum'])) { 
                            $durum = $_GET['durum'];
                            $mesaj = $durum == "ok" ? "İşlem başarılı" : "İşlem başarısız";
                            $renk = $durum == "ok" ? "success" : "danger";
                        ?>
                            <div class="alert alert-<?php echo $renk; ?>" role="alert">
                                <?php echo $mesaj; ?>
                            </div>
                        <?php } ?>
                        <div class="card-header">
                            <h3 class="card-title">Hakkımızda</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="hakkimizda_resim">Hakkımızda Resim</label>
                                    <img style="width:200px" src="resimler/hakkimizda/<?php echo htmlspecialchars($hakkimizdacek['hakkimizda_resim']); ?>" alt="Hakkımızda Resim">
                                </div>
                                <input type="hidden" name="eskiresim" value="<?php echo htmlspecialchars($hakkimizdacek['hakkimizda_resim']); ?>">
                                <div class="form-group">
                                    <label for="resim">Hakkımızda Resim</label>
                                    <input name="resim" type="file" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="baslik">Hakkımızda Başlık</label>
                                    <input name="baslik" value="<?php echo htmlspecialchars($hakkimizdacek['hakkimizda_baslik']); ?>" type="text" class="form-control" placeholder="Lütfen başlık giriniz.">
                                </div>
                                <div class="form-group">
                                    <label for="aciklama">Hakkımızda Açıklama</label>
                                    <textarea name="aciklama" id="editor1" class="ckeditor"><?php echo htmlspecialchars($hakkimizdacek['hakkimizda_aciklama']); ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button name="hakkimizdakaydet" style="float:right" type="submit" class="btn btn-primary">Kaydet</button>
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