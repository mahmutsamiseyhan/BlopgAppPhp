<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function adminYetkisi() {
    return isset($_SESSION['kullanici_rol']) && $_SESSION['kullanici_rol'] == 'admin';
}

function moderatorYetkisi() {
    return isset($_SESSION['kullanici_rol']) && $_SESSION['kullanici_rol'] == 'moderator';
}

function yetkiKontrol($sayfa) {
    if (adminYetkisi()) {
        return true;
    } elseif (moderatorYetkisi()) {
        $izinliSayfalar = ['blog', 'yorum'];
        return in_array($sayfa, $izinliSayfalar);
    }
    return false;
}

function girisKontrol() {
    if (!isset($_SESSION['kullanici_id'])) {
        header("Location: giris.php");
        exit;
    }
}

function csrfTokenOlustur() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfTokenKontrol($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}