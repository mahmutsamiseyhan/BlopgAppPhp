<?php
require_once 'auth.php';
require_once 'baglanti.php';

// CSRF token kontrolü
$csrf_token = csrfTokenOlustur();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BlogApp - Giriş Yap</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="" class="h1"><b>Blog</b> Sitesi</a>
    </div>
    <div class="card-body login-card-body">
      <p class="login-box-msg">Giriş bilgilerinizi giriniz</p>

      <?php if (isset($_GET['durum']) && $_GET['durum'] == "hata") { ?>
        <div class="alert alert-danger">Giriş bilgileri hatalı. Lütfen e-posta ve şifrenizi kontrol edin.</div>
      <?php } ?>

      <form method="post" action="islem.php">
        <div class="input-group mb-3">
          <input name="email" type="email" class="form-control" placeholder="Email adresinizi giriniz" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="sifre" type="password" class="form-control" placeholder="Şifrenizi giriniz" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-4">
            <button name="girisyap" type="submit" class="btn btn-primary btn-block">Giriş Yap</button>
          </div>
        </div>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
      </form>
    </div>
  </div>
</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>