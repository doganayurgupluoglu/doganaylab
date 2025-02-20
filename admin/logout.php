<?php
// Oturumu başlat
session_start();

// Tüm oturum değişkenlerini temizle
$_SESSION = array();

// Oturum çerezini yok et
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-42000, '/');
}

// Oturumu sonlandır
session_destroy();

// Login sayfasına yönlendir
header("location: index.php");
exit;
?> 