<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi() && !moderatorYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

// Yorum onaylama ve silme işlemleri
if (adminYetkisi()) {
    // Admin kullanıcılar için tüm yorumları ve ilgili blog başlıklarını çek
    $yorumsor = $baglanti->prepare("
        SELECT yorumlar.*, blog.blog_baslik 
        FROM yorumlar 
        JOIN blog ON yorumlar.blog_id = blog.blog_id 
        ORDER BY yorumlar.yorum_zaman DESC
    ");
} else {
    // Moderator kullanıcılar için kendi bloglarına ait yorumları çek
    $yorumsor = $baglanti->prepare("
        SELECT yorumlar.*, blog.blog_baslik 
        FROM yorumlar 
        JOIN blog ON yorumlar.blog_id = blog.blog_id 
        WHERE blog.kullanici_id = :kullanici_id 
        ORDER BY yorumlar.yorum_zaman DESC
    ");
    $yorumsor->execute(['kullanici_id' => $_SESSION['kullanici_id']]);
}

$yorumsor->execute();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Yorumlar</h3>
                <?php if (isset($_GET['durum'])) { 
                    $durum = $_GET['durum'];
                    $mesaj = $durum == "ok" ? "İşlem başarılı" : "İşlem başarısız";
                    $renk = $durum == "ok" ? "success" : "danger";
                ?>
                <div class="alert alert-<?php echo $renk; ?>" role="alert">
                    <?php echo $mesaj; ?>
                </div>
                <?php } ?>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>Blog Başlık</th>
                            <th>Yorum</th>
                            <th>Yazar</th>
                            <th>Tarih</th>
                            <th>Durum</th>
                            <th>İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($yorumcek = $yorumsor->fetch(PDO::FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($yorumcek['blog_baslik']); ?></td>
                            <td><?php echo htmlspecialchars($yorumcek['yorum_detay']); ?></td>
                            <td><?php echo htmlspecialchars($yorumcek['yorum_adsoyad']); ?></td>
                            <td><?php echo htmlspecialchars($yorumcek['yorum_zaman']); ?></td>
                            <td>
                                <?php if ($yorumcek['yorum_onay'] == 1) { ?>
                                <span class="badge badge-success">Onaylı</span>
                                <?php } else { ?>
                                <span class="badge badge-warning">Onay Bekliyor</span>
                                <?php } ?>
                            </td>
                            <td class="project-actions text-right">
                                <?php if ($yorumcek['yorum_onay'] == 1) { ?>
                                <form action="islem.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $yorumcek['yorum_id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                                    <button type="submit" name="yorumonaykaldır" class="btn btn-warning btn-sm">Onayı Kaldır</button>
                                </form>
                                <?php } else { ?>
                                <form action="islem.php" method="post" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $yorumcek['yorum_id']; ?>">
                                    <input type="hidden" name="csrf_token" value="<?php echo csrfTokenOlustur(); ?>">
                                    <button type="submit" name="yorumonayla" class="btn btn-success btn-sm">Onayla</button>
                                </form>
                                <?php } ?>
                                <a class="btn btn-danger btn-sm" href="#" onclick="yorumSil(<?php echo $yorumcek['yorum_id']; ?>)">
                                    <i class="fas fa-trash"></i> Sil
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
function yorumSil(id) {
    if (confirm('Bu yorumu silmek istediğinizden emin misiniz?')) {
        window.location.href = 'islem.php?yorumsil=' + id;
    }
}
</script>

<?php require_once '../partials/admin_footer.php'; ?>