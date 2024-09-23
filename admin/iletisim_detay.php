<?php
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$iletisimsor = $baglanti->prepare("SELECT * FROM iletisim WHERE iletisim_id = :id");
$iletisimsor->execute(['id' => $id]);
$iletisim = $iletisimsor->fetch(PDO::FETCH_ASSOC);

if (!$iletisim) {
    header("Location: iletisim.php");
    exit;
}

// Mesajı okundu olarak işaretle
$guncelle = $baglanti->prepare("UPDATE iletisim SET iletisim_okundu = 1 WHERE iletisim_id = :id");
$guncelle->execute(['id' => $id]);

?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>İletişim Mesaj Detayı</h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5>Gönderen: <?php echo htmlspecialchars($iletisim['iletisim_adsoyad']); ?></h5>
                            <p>E-mail: <?php echo htmlspecialchars($iletisim['iletisim_email']); ?></p>
                            <p>Konu: <?php echo htmlspecialchars($iletisim['iletisim_konu']); ?></p>
                            <p>Tarih: <?php echo $iletisim['iletisim_zaman']; ?></p>
                            <hr>
                            <h6>Mesaj:</h6>
                            <p><?php echo nl2br(htmlspecialchars($iletisim['iletisim_mesaj'])); ?></p>
                        </div>
                        <div class="card-footer">
                            <a href="iletisim.php" class="btn btn-secondary">Geri Dön</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php require_once '../partials/admin_footer.php'; ?>