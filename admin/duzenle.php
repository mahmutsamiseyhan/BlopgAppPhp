<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

$sayfa = isset($_GET['sayfa']) ? filter_var($_GET['sayfa'], FILTER_SANITIZE_STRING) : '';
$id = isset($_GET['id']) ? filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT) : 0;

if (!yetkiKontrol($sayfa)) {
    header("Location: yetkisiz.php");
    exit;
}

switch($sayfa) {
    case 'ekip':
        $ekipsor = $baglanti->prepare("SELECT * FROM ekip WHERE ekip_id = :ekip_id");
        $ekipsor->execute(['ekip_id' => $id]);
        $ekipcek = $ekipsor->fetch(PDO::FETCH_ASSOC);
        if (!$ekipcek) {
            header("Location: hata.php");
            exit;
        }
        include 'duzenle/ekip_duzenle.php';
        break;

    case 'galeri':
        $galerisor = $baglanti->prepare("SELECT * FROM galeri WHERE galeri_id = :galeri_id");
        $galerisor->execute(['galeri_id' => $id]);
        $galericek = $galerisor->fetch(PDO::FETCH_ASSOC);
        if (!$galericek) {
            header("Location: hata.php");
            exit;
        }
        include 'duzenle/galeri_duzenle.php';
        break;

    case 'blog':
        $blogsor = $baglanti->prepare("SELECT * FROM blog WHERE blog_id = :blog_id");
        $blogsor->execute(['blog_id' => $id]);
        $blogcek = $blogsor->fetch(PDO::FETCH_ASSOC);
        if (!$blogcek) {
            header("Location: hata.php");
            exit;
        }
        if (moderatorYetkisi() && $blogcek['blog_yazar_id'] != $_SESSION['kullanici_id']) {
            header("Location: yetkisiz.php");
            exit;
        }
        include 'duzenle/blog_duzenle.php';
        break;

    case 'kategori':
        $kategorisor = $baglanti->prepare("SELECT * FROM kategori WHERE kategori_id = :kategori_id");
        $kategorisor->execute(['kategori_id' => $id]);
        $kategoricek = $kategorisor->fetch(PDO::FETCH_ASSOC);
        if (!$kategoricek) {
            header("Location: hata.php");
            exit;
        }
        include 'duzenle/kategori_duzenle.php';
        break;

    default:
        header("Location: hata.php");
        exit;
}

require_once '../partials/admin_footer.php';
?>