<?php

/**
 * SEO dostu URL oluşturma fonksiyonu
 *
 * @param string $s Dönüştürülecek metin
 * @return string SEO dostu URL
 */
function seo($s) {
    $tr = array('ş','Ş','ı','I','İ','ğ','Ğ','ü','Ü','ö','Ö','Ç','ç','(',')','/',' ',',','?');
    $eng = array('s','s','i','i','i','g','g','u','u','o','o','c','c','','','-','-','','');
    $s = str_replace($tr, $eng, $s);
    $s = strtolower($s);
    $s = preg_replace('/[^a-z0-9\-]/', '', $s);
    $s = preg_replace('/-+/', '-', $s);
    $s = trim($s, '-');
    return $s;
}

/**
 * Güvenli giriş kontrolü
 *
 * @param PDO $baglanti Veritabanı bağlantısı
 * @param string $email Kullanıcı e-posta adresi
 * @param string $password Kullanıcı şifresi
 * @return array|bool Giriş başarılıysa kullanıcı bilgileri, değilse false
 */
function guvenliGiris($baglanti, $email, $password) {
    $stmt = $baglanti->prepare("SELECT * FROM kullanicilar WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $kullanici = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($kullanici && password_verify($password, $kullanici['sifre'])) {
        return $kullanici;
    }
    return false;
}

/**
 * CSRF token oluşturma
 *
 * @return string CSRF token
 */
function csrfTknOlustur() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * CSRF token doğrulama
 *
 * @param string $token Kontrol edilecek token
 * @return bool Token geçerliyse true, değilse false
 */
function csrfTokenDogrula($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

/**
 * Kullanıcı yetkisini kontrol etme
 *
 * @param string $yetki Kontrol edilecek yetki
 * @return bool Kullanıcı yetkiye sahipse true, değilse false
 */
function yetkiKntrl($yetki) {
    if (!isset($_SESSION['kullanici_yetki'])) {
        return false;
    }
    return $_SESSION['kullanici_yetki'] === $yetki;
}

/**
 * Güvenli çıktı oluşturma
 *
 * @param string $data Güvenli hale getirilecek veri
 * @return string Güvenli hale getirilmiş veri
 */
// Güvenli çıktı fonksiyonu
function guvenliCikti($data) {
    // HTML entity'lerini normal karakterlere dönüştür
    $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');
    
    // HTML etiketlerini temizle
    $data = strip_tags($data);
    
    // HTML özel karakterlerini güvenli hale getir
    return nl2br(htmlspecialchars($data, ENT_QUOTES, 'UTF-8'));
}


?>