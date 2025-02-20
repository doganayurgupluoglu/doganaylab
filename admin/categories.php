<?php
session_start();

// Kullanıcı giriş yapmamışsa login sayfasına yönlendir
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "config.php";

// Kategori ekleme işlemi
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_category"])){
    $name = trim($_POST["name"]);
    $slug = strtolower(str_replace(" ", "-", $name));
    $description = trim($_POST["description"]);
    
    if(!empty($name)){
        $sql = "INSERT INTO categories (name, slug, description) VALUES (?, ?, ?)";
        if($stmt = mysqli_prepare($conn, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $name, $slug, $description);
            mysqli_stmt_execute($stmt);
            header("location: categories.php");
            exit;
        }
    }
}

// Kategori silme işlemi
if(isset($_GET["delete"]) && !empty($_GET["delete"])){
    $sql = "DELETE FROM categories WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $_GET["delete"]);
        mysqli_stmt_execute($stmt);
        header("location: categories.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategoriler - Admin Panel</title>
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

        .category-form {
            margin-bottom: 2rem;
            padding: 1rem;
            background: var(--surface);
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid var(--border);
            border-radius: 4px;
            background: var(--bg);
            color: var(--text);
        }

        .category-list {
            list-style: none;
            padding: 0;
        }

        .category-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--bg);
        }

        .btn-secondary {
            background: var(--surface);
            color: var(--text);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php"><i class="fas fa-home"></i>Ana Sayfa</a></li>
                <li><a href="posts.php"><i class="fas fa-file-alt"></i>Blog Yazıları</a></li>
                <li><a href="categories.php" class="active"><i class="fas fa-folder"></i>Kategoriler</a></li>
                <li><a href="comments.php"><i class="fas fa-comments"></i>Yorumlar</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i>Ayarlar</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Çıkış Yap</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <h2>Kategoriler</h2>

            <form class="category-form" method="post">
                <div class="form-group">
                    <label>Kategori Adı</label>
                    <input type="text" name="name" required>
                </div>
                <div class="form-group">
                    <label>Açıklama</label>
                    <textarea name="description" rows="3"></textarea>
                </div>
                <button type="submit" name="add_category" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Kategori Ekle
                </button>
            </form>

            <ul class="category-list">
                <?php
                $sql = "SELECT * FROM categories ORDER BY name";
                $result = mysqli_query($conn, $sql);

                if($result && mysqli_num_rows($result) > 0){
                    while($category = mysqli_fetch_assoc($result)){
                        echo '<li class="category-item">
                            <div>
                                <h3>' . htmlspecialchars($category['name']) . '</h3>
                                <small>' . htmlspecialchars($category['description']) . '</small>
                            </div>
                            <div class="post-actions">
                                <a href="edit_category.php?id=' . $category['id'] . '" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Düzenle
                                </a>
                                <a href="?delete=' . $category['id'] . '" class="btn btn-secondary" 
                                   onclick="return confirm(\'Bu kategoriyi silmek istediğinizden emin misiniz?\')">
                                    <i class="fas fa-trash"></i> Sil
                                </a>
                            </div>
                        </li>';
                    }
                } else {
                    echo '<li class="category-item">Henüz kategori bulunmuyor.</li>';
                }
                ?>
            </ul>
        </main>
    </div>
</body>
</html> 