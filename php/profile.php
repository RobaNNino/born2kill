<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect("login.php");
}

// Get user data
$user = getUserData($_SESSION['user_id']);

// Get user stats
$posts_count = 0;
$topics_count = 0;

$sql = "SELECT COUNT(*) as count FROM forum_posts WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $posts_count = $row['count'];
}

$sql = "SELECT COUNT(*) as count FROM forum_topics WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $topics_count = $row['count'];
}

// Get recent posts
$sql = "SELECT p.*, t.title as topic_title, t.id as topic_id 
        FROM forum_posts p 
        JOIN forum_topics t ON p.topic_id = t.id 
        WHERE p.user_id = ? 
        ORDER BY p.created_at DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$recent_posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Born2Kill Community</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Profile Section -->
    <section class="profile-section">
        <div class="container">
            <div class="profile-container">
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <img src="images/avatars/<?php echo htmlspecialchars($user['avatar']); ?>" alt="<?php echo htmlspecialchars($user['username']); ?>">
                    </div>
                    <h2 class="profile-username"><?php echo htmlspecialchars($user['username']); ?></h2>
                    
                    <?php if ($user['vip_status'] != 'none'): ?>
                    <div class="profile-status"><?php echo ucfirst($user['vip_status']); ?> VIP</div>
                    <?php endif; ?>
                    
                    <p>Joined: <?php echo date('M d, Y', strtotime($user['registration_date'])); ?></p>
                    <p>Last active: <?php echo $user['last_login'] ? date('M d, Y', strtotime($user['last_login'])) : 'Never'; ?></p>
                    
                    <div class="profile-stats">
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?php echo $user['level']; ?></div>
                            <div class="profile-stat-label">Level</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?php echo $posts_count; ?></div>
                            <div class="profile-stat-label">Posts</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?php echo $user['points']; ?></div>
                            <div class="profile-stat-label">Points</div>
                        </div>
                        <div class="profile-stat">
                            <div class="profile-stat-value"><?php echo $user['packs']; ?></div>
                            <div class="profile-stat-label">Packs</div>
                        </div>
                    </div>
                </div>
                
                <div class="profile-content">
                    <div class="profile-tabs">
                        <div class="profile-tab active" data-tab="activity">Activity</div>
                        <div class="profile-tab" data-tab="settings">Settings</div>
                    </div>
                    
                    <div class="profile-tab-content active" id="activity-tab">
                        <h3>Recent Activity</h3>
                        
                        <?php if ($recent_posts->num_rows == 0): ?>
                        <p>No recent activity.</p>
                        <?php else: ?>
                            <div class="recent-posts">
                                <?php while ($post = $recent_posts->fetch_assoc()): ?>
                                <div class="recent-post">
                                    <div class="recent-post-title">
                                        <a href="topic.php?id=<?php echo $post['topic_id']; ?>#post-<?php echo $post['id']; ?>">
                                            <?php echo htmlspecialchars($post['topic_title']); ?>
                                        </a>
                                    </div>
                                    <div class="recent-post-date"><?php echo formatDate($post['created_at']); ?></div>
                                    <div class="recent-post-content">
                                        <?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))); ?>
                                        <?php if (strlen($post['content']) > 200): ?>...<?php endif; ?>
                                    </div>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="profile-tab-content" id="settings-tab">
                        <h3>Account Settings</h3>
                        <form method="POST" action="update_profile.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="avatar">Avatar</label>
                                <input type="file" id="avatar" name="avatar">
                                <p class="form-hint">Max size: 2MB. Recommended size: 200x200 pixels.</p>
                            </div>
                            
                            <h4>Change Password</h4>
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" id="current_password" name="current_password">
                            </div>
                            
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password">
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password">
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>