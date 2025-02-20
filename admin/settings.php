<?php
session_start();

// Kullanıcı giriş yapmamışsa login sayfasına yönlendir
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "config.php";

// Şifre değiştirme işlemi
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["change_password"])){
    $current_password = trim($_POST["current_password"]);
    $new_password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);
    $password_err = "";
    
    // Mevcut şifreyi kontrol et
    $sql = "SELECT password FROM users WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $hashed_password);
                if(mysqli_stmt_fetch($stmt)){
                    if(!password_verify($current_password, $hashed_password)){
                        $password_err = "Mevcut şifre yanlış.";
                    }
                }
            }
        }
        mysqli_stmt_close($stmt);
    }
    
    // Yeni şifreleri kontrol et
    if(empty($password_err) && $new_password != $confirm_password){
        $password_err = "Yeni şifreler eşleşmiyor.";
    }
    
    // Şifreyi güncelle
    if(empty($password_err)){
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "si", $hashed_password, $_SESSION["id"]);
            if(mysqli_stmt_execute($stmt)){
                $success_message = "Şifreniz başarıyla güncellendi.";
            } else{
                $password_err = "Bir hata oluştu.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Profil güncelleme işlemi
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_profile"])){
    $email = trim($_POST["email"]);
    $profile_err = "";
    
    if(empty($profile_err)){
        $sql = "UPDATE users SET email = ? WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "si", $email, $_SESSION["id"]);
            if(mysqli_stmt_execute($stmt)){
                $success_message = "Profiliniz başarıyla güncellendi.";
            } else{
                $profile_err = "Bir hata oluştu.";
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Kullanıcı bilgilerini getir
$sql = "SELECT * FROM users WHERE id = ?";
if($stmt = mysqli_prepare($conn, $sql)){
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["id"]);
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    }
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayarlar - Admin Panel</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
        }
        
        .dashboard-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            margin-top: 80px;
            min-height: calc(100vh - 80px);
            padding: 2rem;
            max-width: 1800px;
            margin-left: auto;
            margin-right: auto;
        }

        .sidebar {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text);
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar-menu a:hover {
            background: var(--hover);
            color: var(--primary);
        }

        .sidebar-menu a.active {
            background: var(--gradient-primary);
            color: var(--bg);
        }

        .sidebar-menu i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .main-content {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border);
        }

        .settings-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--surface);
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .settings-section h3 {
            margin-top: 0;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg);
            color: var(--text);
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--bg);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: var(--primary);
            color: var(--bg);
        }

        .alert-danger {
            background: #dc2626;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i>Ana Sayfa</a></li>
                <li><a href="posts.php"><i class="fas fa-file-alt"></i>Blog Yazıları</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i>Kategoriler</a></li>
                <li><a href="comments.php"><i class="fas fa-comments"></i>Yorumlar</a></li>
                <li><a href="settings.php" class="active"><i class="fas fa-cog"></i>Ayarlar</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Çıkış Yap</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2>Ayarlar</h2>

            <?php 
            if(isset($success_message)){
                echo '<div class="alert alert-success">' . $success_message . '</div>';
            }
            if(isset($password_err) && !empty($password_err)){
                echo '<div class="alert alert-danger">' . $password_err . '</div>';
            }
            if(isset($profile_err) && !empty($profile_err)){
                echo '<div class="alert alert-danger">' . $profile_err . '</div>';
            }
            ?>

            <div class="settings-section">
                <h3>Profil Bilgileri</h3>
                <form method="post">
                    <div class="form-group">
                        <label>Kullanıcı Adı</label>
                        <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label>E-posta</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary">
                        <i class="fas fa-save"></i> Profili Güncelle
                    </button>
                </form>
            </div>

            <div class="settings-section">
                <h3>Şifre Değiştir</h3>
                <form method="post">
                    <div class="form-group">
                        <label>Mevcut Şifre</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>Yeni Şifre</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Yeni Şifre (Tekrar)</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" name="change_password" class="btn btn-primary">
                        <i class="fas fa-key"></i> Şifreyi Değiştir
                    </button>
                </form>
            </div>
        </main>
    </div>
</body>
</html> 