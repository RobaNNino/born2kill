<!-- admin/index.php -->
<?php
require_once '../config.php';

// Check if user is admin
if (!isLoggedIn() || !isAdmin()) {
    redirect("../index.php");
    exit;
}

// Get statistics
$total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$total_topics = $conn->query("SELECT COUNT(*) as count FROM forum_topics")->fetch_assoc()['count'];
$total_posts = $conn->query("SELECT COUNT(*) as count FROM forum_posts")->fetch_assoc()['count'];
$new_users_today = $conn->query("SELECT COUNT(*) as count FROM users WHERE DATE(registration_date) = CURDATE()")->fetch_assoc()['count'];

// Get recent users
$recent_users = $conn->query("SELECT id, username, registration_date FROM users ORDER BY registration_date DESC LIMIT 5");

// Get recent posts
$recent_posts = $conn->query("SELECT p.id, p.created_at, t.title as topic_title, u.username 
                              FROM forum_posts p 
                              JOIN forum_topics t ON p.topic_id = t.id 
                              JOIN users u ON p.user_id = u.id 
                              ORDER BY p.created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Born2Kill</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Admin Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Dashboard Section -->
    <section class="admin-dashboard">
        <div class="container">
            <h2 class="admin-title">Admin Dashboard</h2>
            
            <!-- Stats Cards -->
            <div class="admin-stats">
                <div class="admin-stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-details">
                        <div class="stat-number"><?php echo $total_users; ?></div>
                        <div class="stat-title">Total Users</div>
                    </div>
                </div>
                
                <div class="admin-stat-card">
                    <div class="stat-icon"><i class="fas fa-comment-dots"></i></div>
                    <div class="stat-details">
                        <div class="stat-number"><?php echo $total_topics; ?></div>
                        <div class="stat-title">Total Topics</div>
                    </div>
                </div>
                
                <div class="admin-stat-card">
                    <div class="stat-icon"><i class="fas fa-comments"></i></div>
                    <div class="stat-details">
                        <div class="stat-number"><?php echo $total_posts; ?></div>
                        <div class="stat-title">Total Posts</div>
                    </div>
                </div>
                
                <div class="admin-stat-card">
                    <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
                    <div class="stat-details">
                        <div class="stat-number"><?php echo $new_users_today; ?></div>
                        <div class="stat-title">New Users Today</div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="admin-recent">
                <div class="admin-section">
                    <h3 class="admin-section-title">Recent Users</h3>
                    <div class="admin-table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($user = $recent_users->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($user['registration_date'])); ?></td>
                                    <td>
                                        <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="admin-btn-small">Edit</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="users.php" class="admin-view-all">View All Users</a>
                </div>
                
                <div class="admin-section">
                    <h3 class="admin-section-title">Recent Posts</h3>
                    <div class="admin-table-container">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Topic</th>
                                    <th>Author</th>
                                    <th>Posted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($post = $recent_posts->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($post['topic_title']); ?></td>
                                    <td><?php echo htmlspecialchars($post['username']); ?></td>
                                    <td><?php echo date('M d, Y', strtotime($post['created_at'])); ?></td>
                                    <td>
                                        <a href="../topic.php?id=<?php echo $post['id']; ?>" class="admin-btn-small">View</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <a href="posts.php" class="admin-view-all">View All Posts</a>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="admin-actions">
                <h3 class="admin-section-title">Quick Actions</h3>
                <div class="admin-actions-grid">
                    <a href="add_user.php" class="admin-action-btn">
                        <i class="fas fa-user-plus"></i>
                        <span>Add New User</span>
                    </a>
                    
                    <a href="add_category.php" class="admin-action-btn">
                        <i class="fas fa-folder-plus"></i>
                        <span>Add Forum Category</span>
                    </a>
                    
                    <a href="server_status.php" class="admin-action-btn">
                        <i class="fas fa-server"></i>
                        <span>Update Server Status</span>
                    </a>
                    
                    <a href="backup.php" class="admin-action-btn">
                        <i class="fas fa-database"></i>
                        <span>Backup Database</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="../js/main.js"></script>
    <script src="js/admin.js"></script>
</body>
</html>

<!-- admin/css/admin.css -->
/* Admin Panel Styles */
:root {
    --admin-primary: #ff0000;
    --admin-secondary: #333;
    --admin-accent: #ff5555;
    --admin-bg: #111;
    --admin-card: #222;
    --admin-text: #eee;
    --admin-border: #444;
}

.admin-dashboard {
    padding: 50px 0;
    background-color: var(--admin-bg);
}

.admin-title {
    font-size: 32px;
    text-align: center;
    margin-bottom: 40px;
    color: var(--admin-primary);
    position: relative;
}

.admin-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 3px;
    background-color: var(--admin-primary);
}

/* Stats Cards */
.admin-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.admin-stat-card {
    background-color: var(--admin-card);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease;
}

.admin-stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    background-color: var(--admin-primary);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    margin-right: 15px;
}

.stat-details {
    flex: 1;
}

.stat-number {
    font-size: 24px;
    font-weight: 600;
    color: white;
    margin-bottom: 5px;
}

.stat-title {
    font-size: 14px;
    color: var(--text-muted);
}

/* Recent Sections */
.admin-recent {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.admin-section {
    background-color: var(--admin-card);
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
}

.admin-section-title {
    font-size: 20px;
    margin-bottom: 20px;
    color: var(--admin-primary);
    border-bottom: 1px solid var(--admin-border);
    padding-bottom: 10px;
}

.admin-table-container {
    overflow-x: auto;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
}

.admin-table th, .admin-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid var(--admin-border);
}

.admin-table th {
    color: var(--admin-primary);
    font-weight: 600;
}

.admin-table tr:hover {
    background-color: rgba(255, 0, 0, 0.05);
}

.admin-btn-small {
    display: inline-block;
    padding: 5px 10px;
    background-color: var(--admin-primary);
    color: white;
    border-radius: 4px;
    font-size: 12px;
    text-align: center;
}

.admin-btn-small:hover {
    background-color: var(--admin-accent);
    color: white;
}

.admin-view-all {
    display: inline-block;
    margin-top: 15px;
    color: var(--admin-primary);
    font-size: 14px;
}

.admin-view-all:hover {
    text-decoration: underline;
}

/* Quick Actions */
.admin-actions {
    background-color: var(--admin-card);
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    margin-bottom: 40px;
}

.admin-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.admin-action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background-color: var(--admin-secondary);
    border-radius: 8px;
    color: var(--admin-text);
    transition: all 0.3s ease;
}

.admin-action-btn i {
    font-size: 24px;
    margin-bottom: 10px;
    color: var(--admin-primary);
}

.admin-action-btn:hover {
    background-color: var(--admin-primary);
    color: white;
    transform: translateY(-5px);
}

.admin-action-btn:hover i {
    color: white;
}

/* Responsive */
@media (max-width: 768px) {
    .admin-recent {
        grid-template-columns: 1fr;
    }
    
    .admin-actions-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .admin-actions-grid {
        grid-template-columns: 1fr;
    }
}

<!-- admin/includes/header.php -->
<header class="admin-header">
    <div class="container">
        <nav class="admin-nav">
            <div class="admin-logo">
                <a href="index.php">Born2Kill Admin</a>
            </div>
            <div class="admin-nav-links">
                <a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Dashboard</a>
                <a href="users.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'class="active"' : ''; ?>>Users</a>
                <a href="forum.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'forum.php') ? 'class="active"' : ''; ?>>Forum</a>
                <a href="servers.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'servers.php') ? 'class="active"' : ''; ?>>Servers</a>
                <a href="settings.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'class="active"' : ''; ?>>Settings</a>
            </div>
            <div class="admin-user-menu">
                <div class="user-dropdown">
                    <button class="dropdown-btn">
                        <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="../profile.php"><i class="fas fa-user"></i> Profile</a>
                        <a href="../index.php"><i class="fas fa-home"></i> Website</a>
                        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<!-- admin/includes/footer.php -->
<footer class="admin-footer">
    <div class="container">
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> Born2Kill Community Admin Panel.
        </div>
    </div>
</footer>

<!-- admin/js/admin.js -->
document.addEventListener('DOMContentLoaded', function() {
    // Confirmation for delete actions
    const deleteLinks = document.querySelectorAll('.delete-link');
    
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const confirmMessage = this.getAttribute('data-confirm') || 'Are you sure you want to delete this item?';
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        });
    });
});
