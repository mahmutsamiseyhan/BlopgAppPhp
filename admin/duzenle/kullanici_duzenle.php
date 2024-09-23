<?php
require_once '../../partials/admin_header.php';
require_once '../../partials/admin_sidebar.php';
require_once '../auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

$kullanici_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

$kullanicisor = $baglanti->prepare("SELECT * FROM kullanici WHERE kullanici_id = :id");
$kullanicisor->execute(['id' => $kullanici_id]);
$kullanicicek = $kullanicisor->fetch(PDO::FETCH_ASSOC);

if (!$kullanicicek) {
    header("Location: kullanicilar.php");
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
                            <h3 class="card-title">Kullanıcı Düzenle</h3>
                        </div>
                        <form action="islem.php" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="adsoyad">Ad Soyad</label>
                                    <input type="text" class="form-control" id="adsoyad" name="adsoyad" value="<?php echo htmlspecialchars($kullanicicek['kullanici_adsoyad']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="email">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($kullanicicek['kullanici_mail']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="rol">Rol</label>
                                    <select class="form-control" id="rol" name="rol" required>
                                        <option value="admin" <?php echo ($kullanicicek['kullanici_rol'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                                        <option value="moderator" <?php echo ($kullanicicek['kullanici_rol'] == 'moderator') ? 'selected' : ''; ?>>Moderator</option>
                                        <option value="kullanici" <?php echo ($kullanicicek['kullanici_rol'] == 'kullanici') ? 'selected' : ''; ?>>Kullanıcı</option>
                                    </select>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $kullanici_id; ?>">
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="kullaniciduzenle" class="btn btn-primary">Kullanıcı Güncelle</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once '../partials/admin_footer.php'; ?>