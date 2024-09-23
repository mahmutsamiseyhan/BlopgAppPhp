<?php

$ekip_id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

$ekipsor = $baglanti->prepare("SELECT * FROM ekip WHERE ekip_id = :id");
$ekipsor->execute(['id' => $ekip_id]);
$ekipcek = $ekipsor->fetch(PDO::FETCH_ASSOC);

if (!$ekipcek) {
    header("Location: ekip.php");
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
                            <h3 class="card-title">Ekip Üyesi Düzenle</h3>
                        </div>
                        <form action="islem.php" method="post" enctype="multipart/form-data">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="ekip_resim">Mevcut Resim</label>
                                    <img src="resimler/ekip/<?php echo htmlspecialchars($ekipcek['ekip_resim']); ?>" alt="Ekip Resim" style="max-width:200px;">
                                </div>
                                <div class="form-group">
                                    <label for="resim">Yeni Resim (Opsiyonel)</label>
                                    <input type="file" name="resim" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="isim">İsim</label>
                                    <input type="text" name="isim" class="form-control" value="<?php echo htmlspecialchars($ekipcek['ekip_isim']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="aciklama">Açıklama</label>
                                    <textarea name="aciklama" class="form-control" rows="5"><?php echo htmlspecialchars($ekipcek['ekip_aciklama']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="twitter">Twitter</label>
                                    <input type="text" name="twitter" class="form-control" value="<?php echo htmlspecialchars($ekipcek['ekip_twitter']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="instagram">Instagram</label>
                                    <input type="text" name="instagram" class="form-control" value="<?php echo htmlspecialchars($ekipcek['ekip_instagram']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="linkedin">LinkedIn</label>
                                    <input type="text" name="linkedin" class="form-control" value="<?php echo htmlspecialchars($ekipcek['ekip_linkedin']); ?>">
                                </div>
                                <input type="hidden" name="id" value="<?php echo $ekip_id; ?>">
                                <input type="hidden" name="eskiresim" value="<?php echo htmlspecialchars($ekipcek['ekip_resim']); ?>">
                            </div>
                            <div class="card-footer">
                                <button type="submit" name="ekipduzenle" class="btn btn-primary">Güncelle</button>
                            </div>
                            <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
