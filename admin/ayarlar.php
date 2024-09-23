<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';

// Ayarları veritabanından güvenli bir şekilde çekelim
$ayarsor = $baglanti->prepare("SELECT * FROM ayarlar WHERE ayar_id = :id");
$ayarsor->execute(['id' => 1]);
$ayarcek = $ayarsor->fetch(PDO::FETCH_ASSOC);

// Kullanıcıları veritabanından çekelim
$kullanicisor = $baglanti->prepare("SELECT * FROM kullanici");
$kullanicisor->execute();
?>

<?php if (isset($_GET['sayfa']) && $_GET['sayfa'] == "ayarlar") { ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <?php if (isset($_GET['durum']) && $_GET['durum'] == "okey"): ?>
                                <p style="color:green;">İşlem başarılı</p>
                            <?php elseif (isset($_GET['durum']) && $_GET['durum'] == "no"): ?>
                                <p style="color:red;">İşlem başarısız</p>
                            <?php endif; ?>
                            <div class="card-header">
                                <h3 class="card-title">Ayarlar</h3>
                            </div>
                            <form action="islem.php" method="post">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="baslik">Site Başlığı</label>
                                        <input name="baslik" value="<?php echo htmlspecialchars($ayarcek['ayar_baslik'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen sitenizin başlığını giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="aciklama">Site Açıklama</label>
                                        <input name="aciklama" value="<?php echo htmlspecialchars($ayarcek['ayar_aciklama'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen sitenizin açıklamasını giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="anahtar">Site Anahtar Kelime</label>
                                        <input name="anahtar" value="<?php echo htmlspecialchars($ayarcek['ayar_anahtar'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen sitenizin anahtar kelimesini giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="adres">Adres</label>
                                        <input name="adres" value="<?php echo htmlspecialchars($ayarcek['ayar_adres'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen adresinizi giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="telefon">Site Telefon Numarası</label>
                                        <input name="telefon" value="<?php echo htmlspecialchars($ayarcek['ayar_telefon'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen telefon numaranızı giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Site Email Adresi</label>
                                        <input name="email" value="<?php echo htmlspecialchars($ayarcek['ayar_email'], ENT_QUOTES, 'UTF-8'); ?>" type="email" class="form-control" placeholder="Lütfen email adresinizi giriniz.">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button name="ayarkaydet" style="float:right" type="submit" class="btn btn-primary">Kaydet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } elseif (isset($_GET['sayfa']) && $_GET['sayfa'] == "sosyalmedya") { ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <?php if (isset($_GET['durum']) && $_GET['durum'] == "okey"): ?>
                                <p style="color:green;">İşlem başarılı</p>
                            <?php elseif (isset($_GET['durum']) && $_GET['durum'] == "no"): ?>
                                <p style="color:red;">İşlem başarısız</p>
                            <?php endif; ?>
                            <div class="card-header">
                                <h3 class="card-title">Sosyal Medya Ayarları</h3>
                            </div>
                            <form action="islem.php" method="post">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="facebook">Facebook</label>
                                        <input name="facebook" value="<?php echo htmlspecialchars($ayarcek['ayar_facebook'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen Facebook adresinizi giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="instagram">Instagram</label>
                                        <input name="instagram" value="<?php echo htmlspecialchars($ayarcek['ayar_instagram'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen Instagram adresinizi giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="youtube">YouTube</label>
                                        <input name="youtube" value="<?php echo htmlspecialchars($ayarcek['ayar_youtube'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen YouTube adresinizi giriniz.">
                                    </div>
                                    <div class="form-group">
                                        <label for="twitter">Twitter</label>
                                        <input name="twitter" value="<?php echo htmlspecialchars($ayarcek['ayar_twitter'], ENT_QUOTES, 'UTF-8'); ?>" type="text" class="form-control" placeholder="Lütfen Twitter adresinizi giriniz.">
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button name="sosyalmedyakaydet" style="float:right" type="submit" class="btn btn-primary">Kaydet</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } elseif (isset($_GET['sayfa']) && $_GET['sayfa'] == "kullanici") { ?>
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Kullanıcılar</h3>
                                <a href="kayit.php">
                                    <button style="float:right" type="button" class="btn btn-info">Yeni Kullanıcı Ekle</button>
                                </a>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Kullanıcı Adı</th>
                                            <th>Kullanıcı Rolü</th>
                                            <th>Kayıt Tarihi</th>
                                            <th>Düzenle</th>
                                            <th>Sil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($kullanici = $kullanicisor->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($kullanici['kullanici_adsoyad'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($kullanici['kullanici_rol'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo htmlspecialchars($kullanici['kullanici_zaman'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td>
                                                    <a href="duzenle.php?sayfa=kullanici&id=<?php echo htmlspecialchars($kullanici['kullanici_id'], ENT_QUOTES, 'UTF-8'); ?>">
                                                        <button type="button" class="btn btn-success">Düzenle</button>
                                                    </a>
                                                </td>
                                                <td>
                                                    <form action="islem.php" method="post">
                                                        <button name="kullanicisil" type="submit" class="btn btn-danger">Sil</button>
                                                        <input name="id" value="<?php echo htmlspecialchars($kullanici['kullanici_id'], ENT_QUOTES, 'UTF-8'); ?>" type="hidden">
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php } ?>

<?php require_once '../partials/admin_footer.php'; ?>
