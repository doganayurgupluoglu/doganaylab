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
    <title>Admin Panel - Doganay Lab</title>
    <link rel="stylesheet" href="../style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .dashboard-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            margin-top: 80px;
            min-height: calc(100vh - 80px);
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
            padding: 2rem;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border);
            text-align: center;
        }

        .stat-card i {
            font-size: 2rem;
            color: var(--primary);
            margin-bottom: 1rem;
        }

        .stat-card h3 {
            font-size: 1.8rem;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .stat-card p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .recent-posts {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid var(--border);
        }

        .post-list {
            list-style: none;
            padding: 0;
        }

        .post-list li {
            padding: 1rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .post-list li:last-child {
            border-bottom: none;
        }

        .post-actions {
            display: flex;
            gap: 1rem;
        }

        .btn-action {
            padding: 0.5rem;
            border-radius: 8px;
            color: var(--text);
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background: var(--hover);
            color: var(--primary);
        }

        .btn-logout {
            background: var(--surface);
            color: var(--text);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: var(--hover);
            color: var(--primary);
        }
    </style>
</head>
<body>
    <header class="header">
        <nav class="menu">
            <ul>
                <li><a href="../index.html"><i class="fas fa-globe"></i>Siteyi Görüntüle</a></li>
                <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i>Dashboard</a></li>
                <li><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i>Çıkış Yap</a></li>
            </ul>
        </nav>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="dashboard.php" class="active"><i class="fas fa-home"></i>Ana Sayfa</a></li>
                <li><a href="posts.php"><i class="fas fa-file-alt"></i>Blog Yazıları</a></li>
                <li><a href="categories.php"><i class="fas fa-folder"></i>Kategoriler</a></li>
                <li><a href="comments.php"><i class="fas fa-comments"></i>Yorumlar</a></li>
                <li><a href="users.php"><i class="fas fa-users"></i>Kullanıcılar</a></li>
                <li><a href="settings.php"><i class="fas fa-cog"></i>Ayarlar</a></li>
            </ul>
        </aside>

        <main class="main-content">
            <div class="dashboard-header">
                <h2>Hoş Geldin, <?php echo htmlspecialchars($_SESSION["username"]); ?></h2>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <i class="fas fa-file-alt"></i>
                    <h3>
                        <?php
                        $sql = "SELECT COUNT(*) as total FROM posts";
                        $result = mysqli_query($conn, $sql);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['total'];
                        ?>
                    </h3>
                    <p>Blog Yazısı</p>
                </div>

                <div class="stat-card">
                    <i class="fas fa-folder"></i>
                    <h3>
                        <?php
                        $sql = "SELECT COUNT(*) as total FROM categories";
                        $result = mysqli_query($conn, $sql);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['total'];
                        ?>
                    </h3>
                    <p>Kategori</p>
                </div>

                <div class="stat-card">
                    <i class="fas fa-comments"></i>
                    <h3>
                        <?php
                        $sql = "SELECT COUNT(*) as total FROM comments";
                        $result = mysqli_query($conn, $sql);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['total'];
                        ?>
                    </h3>
                    <p>Yorum</p>
                </div>

                <div class="stat-card">
                    <i class="fas fa-eye"></i>
                    <h3>
                        <?php
                        $sql = "SELECT SUM(views) as total FROM posts";
                        $result = mysqli_query($conn, $sql);
                        $data = mysqli_fetch_assoc($result);
                        echo $data['total'] ? $data['total'] : '0';
                        ?>
                    </h3>
                    <p>Toplam Görüntülenme</p>
                </div>
            </div>

            <div class="recent-posts">
                <h3>Son Blog Yazıları</h3>
                <ul class="post-list">
                    <?php
                    $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 5";
                    $result = mysqli_query($conn, $sql);

                    while($row = mysqli_fetch_assoc($result)) {
                        echo '<li>
                            <div>
                                <h4>' . htmlspecialchars($row['title']) . '</h4>
                                <small>' . date('d.m.Y', strtotime($row['created_at'])) . '</small>
                            </div>
                            <div class="post-actions">
                                <a href="edit_post.php?id=' . $row['id'] . '" class="btn-action"><i class="fas fa-edit"></i></a>
                                <a href="delete_post.php?id=' . $row['id'] . '" class="btn-action"><i class="fas fa-trash"></i></a>
                            </div>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
        </main>
    </div>

    <script>
        window.addEventListener('scroll', () => {
            const header = document.querySelector('.header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html> 