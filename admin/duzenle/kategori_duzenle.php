<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

$kategori_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

$kategorisor = $baglanti->prepare("SELECT * FROM kategori WHERE kategori_id = :kategori_id");
$kategorisor->execute(['kategori_id' => $kategori_id]);
$kategoricek = $kategorisor->fetch(PDO::FETCH_ASSOC);

if (!$kategoricek) {
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
                            <h3 class="card-title">Kategori Düzenle</h3>
                        </div>
                        <form action="islem.php" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="baslik">Kategori Başlık</label>
                                    <input type="text" name="baslik" class="form-control" value="<?php echo htmlspecialchars($kategoricek['kategori_baslik']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="sira">Sıra</label>
                                    <input type="number" name="sira" class="form-control" value="<?php echo htmlspecialchars($kategoricek['kategori_sira']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="durum">Durum</label>
                                    <select name="durum" class="form-control">
                                        <option value="1" <?php echo $kategoricek['kategori_durum'] == 1 ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="0" <?php echo $kategoricek['kategori_durum'] == 0 ? 'selected' : ''; ?>>Pasif</option>
                                    </select>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $kategori_id; ?>">
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="kategoriduzenle" class="btn btn-primary">Güncelle</button>
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