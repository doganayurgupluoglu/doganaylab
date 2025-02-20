<?php
session_start();

// Kullanıcı giriş yapmamışsa login sayfasına yönlendir
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "config.php";
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog Yazıları - Admin Panel</title>
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

        .post-list {
            list-style: none;
            padding: 0;
        }

        .post-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post-item:last-child {
            border-bottom: none;
        }

        .post-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
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

        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border);
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
            <div class="header-actions">
                <h2>Blog Yazıları</h2>
                <a href="add_post.php" class="btn btn-primary"><i class="fas fa-plus"></i> Yeni Yazı Ekle</a>
            </div>

            <ul class="post-list">
                <?php
                // Blog yazılarını getir
                $sql = "SELECT p.*, c.name as category_name FROM posts p 
                        LEFT JOIN categories c ON p.category_id = c.id 
                        ORDER BY p.created_at DESC";
                $result = mysqli_query($conn, $sql);

                if($result && mysqli_num_rows($result) > 0){
                    while($post = mysqli_fetch_assoc($result)){
                        echo '<li class="post-item">
                            <div>
                                <h3>' . htmlspecialchars($post['title']) . '</h3>
                                <small>Kategori: ' . htmlspecialchars($post['category_name'] ?? 'Kategorisiz') . ' | 
                                       Durum: ' . htmlspecialchars($post['status']) . ' | 
                                       Tarih: ' . date('d.m.Y', strtotime($post['created_at'])) . '</small>
                            </div>
                            <div class="post-actions">
                                <a href="edit_post.php?id=' . $post['id'] . '" class="btn btn-secondary">
                                    <i class="fas fa-edit"></i> Düzenle
                                </a>
                                <a href="delete_post.php?id=' . $post['id'] . '" class="btn btn-secondary" 
                                   onclick="return confirm(\'Bu yazıyı silmek istediğinizden emin misiniz?\')">
                                    <i class="fas fa-trash"></i> Sil
                                </a>
                            </div>
                        </li>';
                    }
                } else {
                    echo '<li class="post-item">Henüz blog yazısı bulunmuyor.</li>';
                }
                ?>
            </ul>
        </main>
    </div>
</body>
</html> 