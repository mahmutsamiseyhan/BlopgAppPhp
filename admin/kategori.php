<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

if (!adminYetkisi() && !moderatorYetkisi()) {
    header("Location: yetkisiz.php");
    exit;
}

?>

<div class="content-wrapper">
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Kategoriler</h3>
                
                <?php if (isset($_GET['durum'])) { 
                    $durum = $_GET['durum'];
                    $mesaj = $durum == "ok" ? "İşlem başarılı" : "İşlem başarısız";
                    $renk = $durum == "ok" ? "success" : "danger";
                ?>
                    <p class="alert alert-<?php echo $renk; ?>"><?php echo $mesaj; ?></p>
                <?php } ?>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                    <a href="ekle.php?sayfa=kategori" class="btn btn-info float-right">Yeni kategori ekle</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped projects">
                    <thead>
                        <tr>
                            <th>Sıra</th>
                            <th>Başlık</th>
                            <th>Durum</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $kategorisor = $baglanti->prepare("SELECT * FROM kategori ORDER BY kategori_sira ASC");
                        $kategorisor->execute();
                        while ($kategoricek = $kategorisor->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($kategoricek['kategori_sira']); ?></td>
                                <td><?php echo htmlspecialchars($kategoricek['kategori_baslik']); ?></td>
                                <td>
                                    <?php if ($kategoricek['kategori_durum'] == 1) { ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php } else { ?>
                                        <span class="badge badge-danger">Pasif</span>
                                    <?php } ?>
                                </td>
                                <td class="project-actions text-right">
                                    <a class="btn btn-primary btn-sm" href="blog.php?katid=<?php echo $kategoricek['kategori_id']; ?>">
                                        <i class="fas fa-folder"></i> Görüntüle
                                    </a>
                                    <a class="btn btn-info btn-sm" href="duzenle.php?sayfa=kategori&id=<?php echo $kategoricek['kategori_id']; ?>">
                                        <i class="fas fa-pencil-alt"></i> Düzenle
                                    </a>
                                    <a class="btn btn-danger btn-sm" href="#" onclick="kategoriSil(<?php echo $kategoricek['kategori_id']; ?>)">
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
function kategoriSil(id) {
    if (confirm('Bu kategoriyi silmek istediğinizden emin misiniz?')) {
        window.location.href = 'islem.php?kategorisil=' + id;
    }
}
</script>

<?php require_once '../partials/admin_footer.php'; ?>