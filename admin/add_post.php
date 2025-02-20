<?php
session_start();

// Kullanıcı giriş yapmamışsa login sayfasına yönlendir
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "config.php";

// Blog yazısı ekleme işlemi
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = trim($_POST["title"]);
    $slug = strtolower(str_replace(" ", "-", $title));
    $content = trim($_POST["content"]);
    $excerpt = trim($_POST["excerpt"]);
    $category_id = $_POST["category_id"];
    $status = $_POST["status"];
    $author_id = $_SESSION["id"];
    
    if(!empty($title) && !empty($content)){
        $sql = "INSERT INTO posts (title, slug, content, excerpt, category_id, author_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "ssssiss", $title, $slug, $content, $excerpt, $category_id, $author_id, $status);
            
            if(mysqli_stmt_execute($stmt)){
                header("location: posts.php");
                exit;
            } else{
                echo "Bir hata oluştu. Lütfen daha sonra tekrar deneyin.";
            }
            
            mysqli_stmt_close($stmt);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeni Blog Yazısı - Admin Panel</title>
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

        .main-content {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            border: 1px solid var(--border);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text);
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--surface);
            color: var(--text);
        }

        .form-group textarea {
            min-height: 200px;
            resize: vertical;
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

        .btn-secondary {
            background: var(--surface);
            color: var(--text);
            text-decoration: none;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i>Ana Sayfa</a></li>
                <li><a href="posts.php" class="active"><i class="fas fa-file-alt"></i>Blog Yazıları</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i>Kategoriler</a></li>
                <li><a href="comments.php"><i class="fas fa-comments"></i>Yorumlar</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i>Ayarlar</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Çıkış Yap</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2>Yeni Blog Yazısı</h2>

            <form method="post">
                <div class="form-group">
                    <label>Başlık</label>
                    <input type="text" name="title" required>
                </div>

                <div class="form-group">
                    <label>Özet</label>
                    <textarea name="excerpt" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label>İçerik</label>
                    <textarea name="content" required></textarea>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category_id">
                        <option value="">Kategori Seçin</option>
                        <?php
                        $sql = "SELECT * FROM categories ORDER BY name";
                        $result = mysqli_query($conn, $sql);
                        
                        if($result && mysqli_num_rows($result) > 0){
                            while($category = mysqli_fetch_assoc($result)){
                                echo '<option value="' . $category['id'] . '">' . 
                                     htmlspecialchars($category['name']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Durum</label>
                    <select name="status" required>
                        <option value="draft">Taslak</option>
                        <option value="published">Yayınlandı</option>
                    </select>
                </div>

                <div class="action-buttons">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Kaydet
                    </button>
                    <a href="posts.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> İptal
                    </a>
                </div>
            </form>
        </main>
    </div>
</body>
</html> 