<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!yetkiKontrol('icerik')) {
    header("Location: yetkisiz.php");
    exit;
}

$kategori_id = isset($_GET['katid']) ? filter_var($_GET['katid'], FILTER_SANITIZE_NUMBER_INT) : 0;

// Kategoriyi kontrol edelim
$kategorisor = $baglanti->prepare("SELECT * FROM kategori WHERE kategori_id = :kategori_id");
$kategorisor->execute(['kategori_id' => $kategori_id]);
$kategoricek = $kategorisor->fetch(PDO::FETCH_ASSOC);

if (!$kategoricek) {
    header("Location: hata.php");
    exit;
}

// Moderatör ise, sadece kendi kategorilerine içerik ekleyebilsin
if (moderatorYetkisi() && $kategoricek['kullanici_id'] != $_SESSION['kullanici_id']) {
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
                            <h3 class="card-title">İçerik Ekle</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="resim">İçerik Resim</label>
                                    <input name="resim" type="file" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="baslik">İçerik Başlık</label>
                                    <input name="baslik" type="text" class="form-control" placeholder="Lütfen başlık giriniz." required>
                                </div>
                                <div class="form-group">
                                    <label for="sira">İçerik Sıra</label>
                                    <input name="sira" type="number" class="form-control" placeholder="Lütfen sıra giriniz." required>
                                </div>
                                <div class="form-group">
                                    <label for="aciklama">İçerik Açıklama</label>
                                    <textarea name="aciklama" id="editor1" class="ckeditor" required></textarea>
                                </div>
                                <input type="hidden" name="katid" value="<?php echo $kategori_id; ?>">
                                <div class="form-group">
                                    <label for="icerikanahtar">İçerik Anahtar Kelime</label>
                                    <input name="icerikanahtar" type="text" class="form-control" placeholder="Lütfen anahtar kelime giriniz.">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button name="icerikkaydet" type="submit" class="btn btn-primary">Kaydet</button>
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