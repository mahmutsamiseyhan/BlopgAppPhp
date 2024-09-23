<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!yetkiKontrol('blog')) {
    header("Location: yetkisiz.php");
    exit;
}

$blog_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : null;

if (moderatorYetkisi()) {
    $blogsor = $baglanti->prepare("SELECT * FROM blog WHERE blog_id = :id AND kullanici_id = :kullanici_id");
    $blogsor->execute(['id' => $blog_id, 'kullanici_id' => $_SESSION['kullanici_id']]);
} else if (adminYetkisi()) {
    $blogsor = $baglanti->prepare("SELECT * FROM blog WHERE blog_id = :id");
    $blogsor->execute(['id' => $blog_id]);
}

$blogcek = $blogsor->fetch(PDO::FETCH_ASSOC);

// Blog yazılarını veritabanından güvenli bir şekilde çekelim
$blogsor = null; // Sorgu için başlangıç
if (moderatorYetkisi()) {
    $blogsor = $baglanti->prepare("SELECT * FROM blog WHERE kullanici_id = :kullanici_id ORDER BY blog_sira ASC");
    $blogsor->execute(['kullanici_id' => $_SESSION['kullanici_id']]);
} elseif (adminYetkisi()) {
    $blogsor = $baglanti->prepare("SELECT * FROM blog ORDER BY blog_sira ASC");
    $blogsor->execute();
}

// Blog verilerini kontrol et
$bloglar = [];
if ($blogsor) {
    $bloglar = $blogsor->fetchAll(PDO::FETCH_ASSOC);
}
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
                            <h3 class="card-title">Blog Yazıları</h3>
                            <a href="ekle.php?sayfa=blog" class="btn btn-info float-right">Yeni blog ekle</a>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Blog Resim</th>
                                        <th>Blog Başlık</th>
                                        <th>Blog Sıra</th>
                                        <th>Düzenle</th>
                                        <th>Sil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bloglar as $blog) { ?>
                                    <tr>
                                        <td><img style="width:150px"
                                                src="resimler/blog/<?php echo htmlspecialchars($blog['blog_resim']); ?>"
                                                alt="Blog Resim"></td>
                                        <td><?php echo htmlspecialchars($blog['blog_baslik']); ?></td>
                                        <td><?php echo htmlspecialchars($blog['blog_sira']); ?></td>
                                        <td><a href="duzenle.php?sayfa=blog&id=<?php echo htmlspecialchars($blog['blog_id']); ?>"
                                                class="btn btn-success">Düzenle</a></td>
                                        <td>
                                            <form action="islem.php" method="post"
                                                onsubmit="return confirm('Bu blog yazısını silmek istediğinizden emin misiniz?');">
                                                <input type="hidden" name="id"
                                                    value="<?php echo htmlspecialchars($blog['blog_id']); ?>">
                                                <input type="hidden" name="eskiresim"
                                                    value="<?php echo htmlspecialchars($blog['blog_resim']); ?>">
                                                <button name="blogsil" type="submit" class="btn btn-danger">Sil</button>
                                                <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
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