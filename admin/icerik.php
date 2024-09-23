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

// İçerikleri güvenli bir şekilde veritabanından çekelim
if (adminYetkisi()) {
    $iceriksor = $baglanti->prepare("SELECT * FROM icerik WHERE kategori_id = :kategori_id ORDER BY icerik_sira ASC");
} else {
    $iceriksor = $baglanti->prepare("SELECT * FROM icerik WHERE kategori_id = :kategori_id AND kullanici_id = :kullanici_id ORDER BY icerik_sira ASC");
    $iceriksor->bindParam(':kullanici_id', $_SESSION['kullanici_id']);
}
$iceriksor->bindParam(':kategori_id', $kategori_id);
$iceriksor->execute();
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
                            <h3 class="card-title">İçerikler</h3>
                            <a href="icerik-ekle.php?katid=<?php echo $kategori_id; ?>" class="btn btn-info float-right">Yeni içerik ekle</a>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>İçerik Resim</th>
                                        <th>İçerik Başlık</th>
                                        <th>İçerik Sıra</th>
                                        <th>Düzenle</th>
                                        <th>Sil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($icerikcek = $iceriksor->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td><img style="width:150px" src="resimler/icerik/<?php echo htmlspecialchars($icerikcek['icerik_resim']); ?>" alt="İçerik Resim"></td>
                                            <td><?php echo htmlspecialchars($icerikcek['icerik_baslik']); ?></td>
                                            <td><?php echo htmlspecialchars($icerikcek['icerik_sira']); ?></td>
                                            <td><a href="icerik-duzenle.php?id=<?php echo $icerikcek['icerik_id']; ?>" class="btn btn-success">Düzenle</a></td>
                                            <td>
                                                <form action="islem.php" method="post" onsubmit="return confirm('Bu içeriği silmek istediğinizden emin misiniz?');">
                                                    <input type="hidden" name="id" value="<?php echo $icerikcek['icerik_id']; ?>">
                                                    <input type="hidden" name="eskiresim" value="<?php echo $icerikcek['icerik_resim']; ?>">
                                                    <input type="hidden" name="katid" value="<?php echo $icerikcek['kategori_id']; ?>">
                                                    <button name="iceriksil" type="submit" class="btn btn-danger">Sil</button>
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