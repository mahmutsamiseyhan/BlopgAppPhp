<?php
session_start();

// Tüm session değişkenlerini temizle
$_SESSION = array();

// Oturumu sonlandır
session_destroy();

// Ana sayfaya yönlendir
header("Location: index.php");
exit;
?>