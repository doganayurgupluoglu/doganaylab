<?php
define('DB_SERVER', 'your_hostinger_mysql_host');
define('DB_USERNAME', 'your_hostinger_db_username');
define('DB_PASSWORD', 'your_hostinger_db_password');
define('DB_NAME', 'your_hostinger_db_name');

// Veritabanı bağlantısını oluştur
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Bağlantıyı kontrol et
if($conn === false){
    die("HATA: Veritabanına bağlanılamadı. " . mysqli_connect_error());
}

// UTF-8 karakter setini ayarla
mysqli_set_charset($conn, "utf8");
?> 