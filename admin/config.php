<?php
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

// UTF-8 karakter setini ayarla
mysqli_set_charset($conn, "utf8");
?> 