<?php
require_once "config.php";

// Yeni şifre
$new_password = "admin123";
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Admin kullanıcısının şifresini güncelle
$sql = "UPDATE users SET password = ? WHERE username = 'admin'";

if($stmt = mysqli_prepare($conn, $sql)){
    mysqli_stmt_bind_param($stmt, "s", $hashed_password);
    
    if(mysqli_stmt_execute($stmt)){
        echo "<p style='color: green;'>Şifre başarıyla güncellendi!</p>";
        echo "<p>Yeni şifre: " . $new_password . "</p>";
        echo "<p>Hash'lenmiş şifre: " . $hashed_password . "</p>";
        
        // Kontrol için kullanıcı bilgilerini göster
        $check_sql = "SELECT * FROM users WHERE username = 'admin'";
        $result = mysqli_query($conn, $check_sql);
        if($user = mysqli_fetch_assoc($result)){
            echo "<pre>";
            print_r($user);
            echo "</pre>";
        }
    } else{
        echo "<p style='color: red;'>Hata: Şifre güncellenemedi!</p>";
        echo "<p>MySQL Hatası: " . mysqli_error($conn) . "</p>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "<p style='color: red;'>Hata: Sorgu hazırlanamadı!</p>";
    echo "<p>MySQL Hatası: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?> 