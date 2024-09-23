<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Slider verisini veritabanından güvenli bir şekilde çekelim
$slidersor = $baglanti->prepare("SELECT * FROM slider WHERE slider_id = :slider_id");
$slidersor->execute(['slider_id' => 1]);
$slidercek = $slidersor->fetch(PDO::FETCH_ASSOC);
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
                            <h3 class="card-title">Slider</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="slider_resim">Slider Resim</label>
                                    <img style="width:200px" src="resimler/slider/<?php echo htmlspecialchars($slidercek['slider_resim']); ?>" alt="Slider Resim">
                                </div>
                                <input type="hidden" name="eskiresim" value="<?php echo htmlspecialchars($slidercek['slider_resim']); ?>">
                                <div class="form-group">
                                    <label for="resim">Slider Resim</label>
                                    <input name="resim" type="file" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="baslik">Slider Başlık</label>
                                    <input name="baslik" value="<?php echo htmlspecialchars($slidercek['slider_baslik']); ?>" type="text" class="form-control" placeholder="Lütfen başlık giriniz.">
                                </div>
                                <div class="form-group">
                                    <label for="buton">Slider Buton İsmi</label>
                                    <input name="buton" value="<?php echo htmlspecialchars($slidercek['slider_buton']); ?>" type="text" class="form-control" placeholder="Lütfen buton ismini giriniz.">
                                </div>
                                <div class="form-group">
                                    <label for="link">Slider Buton Linki</label>
                                    <input name="link" value="<?php echo htmlspecialchars($slidercek['slider_link']); ?>" type="text" class="form-control" placeholder="Lütfen buton linkini giriniz.">
                                </div>
                                <div class="form-group">
                                    <label for="aciklama">Slider Açıklama</label>
                                    <textarea name="aciklama" id="editor1" class="ckeditor"><?php echo htmlspecialchars($slidercek['slider_aciklama']); ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button name="sliderkaydet" style="float:right" type="submit" class="btn btn-primary">Kaydet</button>
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