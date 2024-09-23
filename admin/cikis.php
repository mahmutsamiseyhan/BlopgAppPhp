<?php
session_start();

// Tüm oturum verilerini temizleyelim
$_SESSION = array();

// Oturum çerezini yok et
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Oturumu sonlandır
session_destroy();

// Güvenli bir şekilde kullanıcıyı giriş sayfasına yönlendirelim
header("Location: giris.php");
exit();