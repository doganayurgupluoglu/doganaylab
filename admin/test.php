<?php
require_once "config.php";

echo "<h2>Veritabanı Bağlantı Testi</h2>";

// Bağlantı durumunu kontrol et
if($conn) {
    echo "<p style='color: green;'>Veritabanı bağlantısı başarılı!</p>";
    
    // Users tablosunu kontrol et
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = 'admin'");
    
    if($result) {
        $user = mysqli_fetch_assoc($result);
        if($user) {
            echo "<p style='color: green;'>Admin kullanıcısı bulundu!</p>";
            echo "<pre>";
            print_r($user);
            echo "</pre>";
        } else {
            echo "<p style='color: red;'>Admin kullanıcısı bulunamadı!</p>";
        }
    } else {
        echo "<p style='color: red;'>Sorgu hatası: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p style='color: red;'>Veritabanı bağlantısı başarısız!</p>";
    echo "<p>Hata: " . mysqli_connect_error() . "</p>";
}
?> 