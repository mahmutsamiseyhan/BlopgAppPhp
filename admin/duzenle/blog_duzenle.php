<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!yetkiKontrol('blog')) {
    header("Location: yetkisiz.php");
    exit;
}

$blog_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

$blogsor = $baglanti->prepare("SELECT * FROM blog WHERE blog_id = :blog_id");
$blogsor->execute(['blog_id' => $blog_id]);
$blogcek = $blogsor->fetch(PDO::FETCH_ASSOC);

if (!$blogcek) {
    header("Location: hata.php");
    exit;
}

if (moderatorYetkisi() && $blogcek['kullanici_id'] != $_SESSION['kullanici_id']) {
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
                            <h3 class="card-title">Blog Düzenle</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="blog_resim">Mevcut Resim</label>
                                    <img src="resimler/blog/<?php echo htmlspecialchars($blogcek['blog_resim']); ?>" alt="Blog Resim" style="max-width:200px;">
                                </div>
                                <div class="form-group">
                                    <label for="resim">Yeni Resim (Opsiyonel)</label>
                                    <input type="file" name="resim" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="baslik">Blog Başlık</label>
                                    <input type="text" name="baslik" class="form-control" value="<?php echo htmlspecialchars($blogcek['blog_baslik']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="icerik">Blog İçerik</label>
                                    <textarea name="aciklama" id="editor1" class="ckeditor" required><?php echo htmlspecialchars($blogcek['blog_aciklama']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="sira">Sıra</label>
                                    <input type="number" name="sira" class="form-control" value="<?php echo htmlspecialchars($blogcek['blog_sira']); ?>" required>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $blog_id; ?>">
                                <input type="hidden" name="eskiresim" value="<?php echo htmlspecialchars($blogcek['blog_resim']); ?>">
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="blogduzenle" class="btn btn-primary">Güncelle</button>
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