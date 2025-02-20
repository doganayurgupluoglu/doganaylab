<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u426745395_doganaylab');
define('DB_PASSWORD', '4G9eqoqxNSIE0l');
define('DB_NAME', 'u426745395_doganaylab_db');

// Veritabanı bağlantısını oluştur
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Bağlantıyı kontrol et
if($conn === false){
    die("HATA: Veritabanına bağlanılamadı. " . mysqli_connect_error());
}

// Bağlantı başarılı mı test et
$test_query = "SELECT * FROM users WHERE username = 'admin'";
$result = mysqli_query($conn, $test_query);

if($result){
    $row = mysqli_fetch_assoc($result);
    if($row){
        // echo "Admin kullanıcısı bulundu!";
    } else {
        // echo "Admin kullanıcısı bulunamadı!";
    }
} else {
    die("HATA: Sorgu çalıştırılamadı. " . mysqli_error($conn));
}

// UTF-8 karakter setini ayarla
mysqli_set_charset($conn, "utf8");
?> 