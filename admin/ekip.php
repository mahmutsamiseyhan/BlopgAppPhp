<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Ekip üyelerini veritabanından güvenli bir şekilde çekelim
$ekipsor = $baglanti->prepare("SELECT * FROM ekip ORDER BY ekip_sira ASC");
$ekipsor->execute();
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
                            <h3 class="card-title">Ekip Üyeleri</h3>
                            <a href="ekle.php?sayfa=ekip" class="btn btn-info float-right">Yeni ekip üyesi ekle</a>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Ekip Resim</th>
                                        <th>Ekip Sıra</th>
                                        <th>Ekip İsim</th>
                                        <th>Ekip Konum</th>
                                        <th>Düzenle</th>
                                        <th>Sil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($ekipcek = $ekipsor->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td><img style="width:150px" src="resimler/ekip/<?php echo htmlspecialchars($ekipcek['ekip_resim']); ?>" alt="Ekip Resim"></td>
                                            <td><?php echo htmlspecialchars($ekipcek['ekip_sira']); ?></td>
                                            <td><?php echo htmlspecialchars($ekipcek['ekip_isim']); ?></td>
                                            <td><?php echo htmlspecialchars($ekipcek['ekip_konum']); ?></td>
                                            <td><a href="duzenle.php?sayfa=ekip&id=<?php echo $ekipcek['ekip_id']; ?>" class="btn btn-success">Düzenle</a></td>
                                            <td>
                                                <form action="islem.php" method="post" onsubmit="return confirm('Bu ekip üyesini silmek istediğinizden emin misiniz?');">
                                                    <input type="hidden" name="id" value="<?php echo $ekipcek['ekip_id']; ?>">
                                                    <input type="hidden" name="eskiresim" value="<?php echo $ekipcek['ekip_resim']; ?>">
                                                    <button name="ekipsil" type="submit" class="btn btn-danger">Sil</button>
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