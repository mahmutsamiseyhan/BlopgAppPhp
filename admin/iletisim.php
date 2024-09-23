<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// İletişim mesajlarını veritabanından çekelim
$iletisimsor = $baglanti->prepare("SELECT * FROM iletisim ORDER BY iletisim_zaman DESC");
$iletisimsor->execute();

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>İletişim Mesajları</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Gelen Mesajlar</h3>
                        </div>
                        <div class="card-body">
                            <table id="iletisimTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ad Soyad</th>
                                        <th>E-mail</th>
                                        <th>Konu</th>
                                        <th>Tarih</th>
                                        <th>Durum</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($iletisim = $iletisimsor->fetch(PDO::FETCH_ASSOC)) { ?>
                                        <tr>
                                            <td><?php echo $iletisim['iletisim_id']; ?></td>
                                            <td><?php echo htmlspecialchars($iletisim['iletisim_adsoyad']); ?></td>
                                            <td><?php echo htmlspecialchars($iletisim['iletisim_email']); ?></td>
                                            <td><?php echo htmlspecialchars($iletisim['iletisim_konu']); ?></td>
                                            <td><?php echo $iletisim['iletisim_zaman']; ?></td>
                                            <td>
                                                <?php if ($iletisim['iletisim_okundu'] == 0) { ?>
                                                    <span class="badge badge-warning">Okunmadı</span>
                                                <?php } else { ?>
                                                    <span class="badge badge-success">Okundu</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="iletisim_detay.php?id=<?php echo $iletisim['iletisim_id']; ?>" class="btn btn-info btn-sm">Görüntüle</a>
                                                <a href="javascript:void(0)" onclick="mesajSil(<?php echo $iletisim['iletisim_id']; ?>)" class="btn btn-danger btn-sm">Sil</a>
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

<script>
function mesajSil(id) {
    if (confirm('Bu mesajı silmek istediğinizden emin misiniz?')) {
        window.location.href = 'islem.php?iletisimsil=' + id;
    }
}
</script>

<?php require_once '../partials/admin_footer.php'; ?>