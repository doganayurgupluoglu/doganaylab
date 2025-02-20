<?php
session_start();

// Kullanıcı giriş yapmamışsa login sayfasına yönlendir
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

require_once "config.php";

// Yorum durumunu güncelle
if(isset($_GET["approve"])) {
    $sql = "UPDATE comments SET status = 'approved' WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $_GET["approve"]);
        mysqli_stmt_execute($stmt);
    }
    header("location: comments.php");
    exit;
}

// Yorum sil
if(isset($_GET["delete"])) {
    $sql = "DELETE FROM comments WHERE id = ?";
    if($stmt = mysqli_prepare($conn, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $_GET["delete"]);
        mysqli_stmt_execute($stmt);
    }
    header("location: comments.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yorumlar - Admin Panel</title>
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

        .comments-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .comment-item {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: start;
        }

        .comment-item:last-child {
            border-bottom: none;
        }

        .comment-content {
            flex: 1;
        }

        .comment-meta {
            font-size: 0.9rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .comment-text {
            margin-bottom: 0.5rem;
        }

        .comment-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-approve {
            background: var(--primary);
            color: var(--bg);
        }

        .btn-delete {
            background: var(--surface);
            color: var(--text);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background: var(--surface);
            color: var(--text);
        }

        .status-approved {
            background: var(--primary);
            color: var(--bg);
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
                <li><a href="posts.php"><i class="fas fa-file-alt"></i>Blog Yazıları</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i>Kategoriler</a></li>
                <li><a href="comments.php" class="active"><i class="fas fa-comments"></i>Yorumlar</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i>Ayarlar</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Çıkış Yap</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="header-actions">
                <h2>Yorumlar</h2>
            </div>

            <ul class="comments-list">
                <?php
                $sql = "SELECT c.*, p.title as post_title FROM comments c 
                        LEFT JOIN posts p ON c.post_id = p.id 
                        ORDER BY c.created_at DESC";
                $result = mysqli_query($conn, $sql);

                if($result && mysqli_num_rows($result) > 0){
                    while($comment = mysqli_fetch_assoc($result)){
                        echo '<li class="comment-item">
                            <div class="comment-content">
                                <div class="comment-meta">
                                    <strong>' . htmlspecialchars($comment['name']) . '</strong> - 
                                    <span>' . htmlspecialchars($comment['email']) . '</span><br>
                                    <span>Yazı: ' . htmlspecialchars($comment['post_title']) . '</span><br>
                                    <span>' . date('d.m.Y H:i', strtotime($comment['created_at'])) . '</span>
                                    <span class="status-badge status-' . $comment['status'] . '">' . 
                                    ($comment['status'] == 'approved' ? 'Onaylandı' : 'Beklemede') . '</span>
                                </div>
                                <div class="comment-text">' . htmlspecialchars($comment['comment']) . '</div>
                            </div>
                            <div class="comment-actions">';
                        if($comment['status'] != 'approved'){
                            echo '<a href="?approve=' . $comment['id'] . '" class="btn btn-approve">
                                    <i class="fas fa-check"></i> Onayla
                                </a>';
                        }
                        echo '<a href="?delete=' . $comment['id'] . '" class="btn btn-delete" 
                                onclick="return confirm(\'Bu yorumu silmek istediğinizden emin misiniz?\')">
                                <i class="fas fa-trash"></i> Sil
                            </a>
                            </div>
                        </li>';
                    }
                } else {
                    echo '<li class="comment-item">Henüz yorum bulunmuyor.</li>';
                }
                ?>
            </ul>
        </main>
    </div>
</body>
</html> 