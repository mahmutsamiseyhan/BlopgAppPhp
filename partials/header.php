<?php 
require_once 'admin/baglanti.php';
require_once 'function.php';

session_start(); // Oturum başlat

$ayarsor=$baglanti->prepare("SELECT * FROM ayarlar where ayar_id=?");
$ayarsor->execute(array(1));
$ayarcek=$ayarsor->fetch(PDO::FETCH_ASSOC);

$hakkimizdasor=$baglanti->prepare("SELECT * FROM hakkimizda where hakkimizda_id=?");
$hakkimizdasor->execute(array(1));
$hakkimizdacek=$hakkimizdasor->fetch(PDO::FETCH_ASSOC);

$slidersor=$baglanti->prepare("SELECT * FROM slider where slider_id=?");
$slidersor->execute(array(1));
$slidercek=$slidersor->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?php echo $ayarcek['ayar_baslik'] ?></title>
    <meta content="<?php echo $ayarcek['ayar_aciklama'] ?>" name="descriptison">
    <meta content="<?php echo $ayarcek['ayar_anahtar'] ?>" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
     <!-- SİTE İKONU -->
     <link rel="shortcut icon" href="admin/resimler/site-ico.ico">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/icofont/icofont.min.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/owl.carousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="assets/vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <style>
    .header-social-links a {
        color: #333;
        padding: 4px 6px;
        display: inline-block;
        line-height: 1px;
        transition: 0.3s;
    }

    .header-social-links a:hover {
        color: #5fcf80;
    }
    </style>
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top">
        <div class="container d-flex align-items-center">
            <h1 class="logo mr-auto"><a href="index.php">Blog App</a></h1>

            <nav class="nav-menu d-none d-lg-block">
                <ul>
                    <li class="active"><a href="index.php">Anasayfa</a></li>
                    <li><a href="hakkimizda.php">Hakkımızda</a></li>
                    <li><a href="ekip.php">Ekibimiz</a></li>
                    <li><a href="blog.php">Blog</a></li>

                    <li class="drop-down"><a href="">Kategoriler</a>
                        <ul>
                            <?php 
                            $kategorisor=$baglanti->prepare("SELECT * FROM kategori ORDER BY kategori_sira ASC");
                            $kategorisor->execute();
                            while ($kategoricek=$kategorisor->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                            <li><a
                                    href="kategori-detay-<?=seo($kategoricek['kategori_baslik']).'-'.$kategoricek['kategori_id'] ?>"><?php echo $kategoricek['kategori_baslik'] ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <!-- <li><a href="iletisim.php">İletişim</a></li> -->
                </ul>
            </nav><!-- .nav-menu -->

            <div class="header-social-links ml-auto">
                <?php if(isset($_SESSION['kullanici_id'])): ?>
                <a href="admin/index.php" title="Profilim"><i class="fas fa-user"></i></a>
                <a href="cikis.php" title="Çıkış Yap"><i class="fas fa-sign-out-alt"></i></a>
                <?php else: ?>
                <a href="admin/giris.php" title="Giriş Yap"><i class="fas fa-sign-in-alt"></i></a>
                <a href="admin/kayit.php" title="Kayıt Ol"><i class="fas fa-user-plus"></i></a>
                <?php endif; ?>
            </div>

        </div>
    </header><!-- End Header -->