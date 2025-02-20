<?php
require_once "config.php";

// Önce admin kullanıcısını sil
$delete_sql = "DELETE FROM users WHERE username = 'admin'";
if(mysqli_query($conn, $delete_sql)){
    echo "<p style='color: green;'>Eski admin kullanıcısı silindi.</p>";
} else {
    echo "<p style='color: red;'>Hata: Admin kullanıcısı silinemedi!</p>";
    echo "<p>MySQL Hatası: " . mysqli_error($conn) . "</p>";
}

// Yeni admin kullanıcısı oluştur
$username = "admin";
$password = "admin123";
$email = "admin@example.com";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";

if($stmt = mysqli_prepare($conn, $sql)){
    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $email);
    
    if(mysqli_stmt_execute($stmt)){
        echo "<p style='color: green;'>Yeni admin kullanıcısı oluşturuldu!</p>";
        echo "<p>Kullanıcı adı: " . $username . "</p>";
        echo "<p>Şifre: " . $password . "</p>";
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
        echo "<p style='color: red;'>Hata: Admin kullanıcısı oluşturulamadı!</p>";
        echo "<p>MySQL Hatası: " . mysqli_error($conn) . "</p>";
    }
    
    mysqli_stmt_close($stmt);
} else {
    echo "<p style='color: red;'>Hata: Sorgu hazırlanamadı!</p>";
    echo "<p>MySQL Hatası: " . mysqli_error($conn) . "</p>";
}

mysqli_close($conn);
?> 