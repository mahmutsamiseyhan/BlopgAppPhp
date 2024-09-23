<?php 
require_once '../partials/admin_header.php';
require_once '../partials/admin_sidebar.php';
require_once 'auth.php';

girisKontrol();

$sayfa = isset($_GET['sayfa']) ? filter_var($_GET['sayfa'], FILTER_SANITIZE_STRING) : '';

if (!yetkiKontrol($sayfa)) {
    header("Location: yetkisiz.php");
    exit;
}

switch($sayfa) {
    case 'ekip':
        if (!adminYetkisi()) {
            header("Location: yetkisiz.php");
            exit;
        }
        include 'ekle/ekip_ekle.php';
        break;

    case 'galeri':
        if (!adminYetkisi()) {
            header("Location: yetkisiz.php");
            exit;
        }
        include 'ekle/galeri_ekle.php';
        break;

    case 'blog':
        include 'ekle/blog_ekle.php';
        break;

    case 'kategori':
        if (!adminYetkisi()) {
            header("Location: yetkisiz.php");
            exit;
        }
        include 'ekle/kategori_ekle.php';
        break;

    default:
        header("Location: hata.php");
        exit;
}

require_once '../partials/admin_footer.php';
?>