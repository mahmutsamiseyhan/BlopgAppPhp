<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';


girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}
// CSRF token kontrolü
$csrf_token = csrfTokenOlustur();
// Kullanıcıları veritabanından çekelim
$kullanicisor = $baglanti->prepare("SELECT * FROM kullanici ORDER BY kullanici_id DESC");
$kullanicisor->execute();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Kullanıcı Yönetimi</h3>
                            <a href="ekle/kullanici_ekle.php" class="btn btn-primary float-right">Yeni Kullanıcı Ekle</a>
                        </div>
                        <div class="card-body">
                            <?php 
                            if (isset($_GET['durum'])) {
                                if ($_GET['durum'] == "ok") {
                                    echo '<div class="alert alert-success">İşlem başarılı</div>';
                                } elseif ($_GET['durum'] == "no") {
                                    echo '<div class="alert alert-danger">İşlem başarısız</div>';
                                }
                            }
                            ?>
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ad Soyad</th>
                                        <th>E-mail</th>
                                        <th>Rol</th>
                                        <th>Kayıt Tarihi</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($kullanicicek = $kullanicisor->fetch(PDO::FETCH_ASSOC)) { ?>
                                    <tr>
                                        <td><?php echo $kullanicicek['kullanici_id']; ?></td>
                                        <td><?php echo htmlspecialchars($kullanicicek['kullanici_adsoyad']); ?></td>
                                        <td><?php echo htmlspecialchars($kullanicicek['kullanici_email']); ?></td>
                                        <td><?php echo htmlspecialchars($kullanicicek['kullanici_rol']); ?></td>
                                        <td><?php echo $kullanicicek['kullanici_zaman']; ?></td>
                                        <td>
                                            <form action="islem.php" method="POST" style="display: inline;">
                                                <input type="hidden" name="id" value="<?php echo $kullanicicek['kullanici_id']; ?>">
                                                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                                <button type="submit" name="kullanicisil" class="btn btn-danger btn-md" onclick="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?')">Sil</button>
                                                
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