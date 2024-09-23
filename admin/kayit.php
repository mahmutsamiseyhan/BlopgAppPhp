<?php
session_start();
require_once 'baglanti.php';
require_once 'auth.php';

// CSRF token kontrolü
$csrf_token = csrfTokenOlustur();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BlogApp - Kayıt Ol</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="" class="h1"><b>Blog</b> Sitesi - Kayıt Ol</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Yeni üyelik oluştur</p>
      <?php if (isset($_GET['durum']) && $_GET['durum'] == "hata") { ?>
        <p class="text-danger">Kayıt işlemi başarısız oldu. Lütfen bilgilerinizi kontrol edin.</p>
      <?php } ?>
      <form action="islem.php" method="post">
        <div class="input-group mb-3">
          <input type="text" name="adsoyad" class="form-control" placeholder="Ad Soyad" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" name="email" class="form-control" placeholder="Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="sifre" class="form-control" placeholder="Şifre" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="sifre_tekrar" class="form-control" placeholder="Şifreni Tekrar Gir" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
              <label for="agreeTerms">
               <a href="#">Şartları</a> kabul ediyorum
              </label>
            </div>
          </div>
          <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <button type="submit" name="kayitol" class="btn btn-primary btn-block">Kayıt Ol</button>
      </form>
      <a href="giris.php" class="text-center">Zaten üyeliğim var</a>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>