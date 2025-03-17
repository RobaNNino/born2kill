<?php
require_once 'config.php';

// Get server status
$servers = getServers();

// Get recent forum topics
$sql = "SELECT t.id, t.title, t.created_at, u.username, 
        (SELECT COUNT(*) FROM forum_posts WHERE topic_id = t.id) as replies,
        (SELECT MAX(created_at) FROM forum_posts WHERE topic_id = t.id) as last_reply
        FROM forum_topics t 
        JOIN users u ON t.user_id = u.id 
        ORDER BY t.created_at DESC LIMIT 6";
$result = $conn->query($sql);
$recentTopics = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recentTopics[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Born2Kill - CS 1.6 Zombie Plague Mod Community</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Born2Kill</h1>
            <p>The best CS 1.6 Zombie Plague Mod community</p>
            <a href="register.php" class="btn btn-primary">Join Now</a>
        </div>
    </section>

    <!-- Server Stats -->
    <section id="servers" class="server-stats">
        <div class="container">
            <h2 class="section-title">Server Status</h2>
            <div class="stats-grid">
                <?php foreach ($servers as $server): ?>
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="server-name"><?php echo htmlspecialchars($server['name']); ?></div>
                        <div class="server-status <?php echo $server['status']; ?>"><?php echo ucfirst($server['status']); ?></div>
                    </div>
                    <div class="stat-details">
                        <div class="stat-item">
                            <span class="stat-label">Mode</span>
                            <span class="stat-value"><?php echo htmlspecialchars($server['mode']); ?></span>
                        </div>
                    </div>
                    <div class="gametracker-widget">
                        <a href="https://www.gametracker.com/server_info/<?php echo $server['ip']; ?>:<?php echo $server['port']; ?>/" target="_blank">
                            <img src="https://cache.gametracker.com/server_info/<?php echo $server['ip']; ?>:<?php echo $server['port']; ?>/b_560_95_1.png" border="0" width="560" height="95" alt="Server banner"/>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Forum Preview -->
    <section id="forum-preview" class="forum">
        <div class="container">
            <h2 class="section-title">Recent Forum Activity</h2>
            <div class="forum-grid">
                <div class="forum-card">
                    <div class="forum-header">
                        <div class="forum-title">Recent Topics</div>
                        <a href="forum.php" class="btn btn-outline">View All</a>
                    </div>
                    <ul class="forum-topics">
                        <?php foreach ($recentTopics as $topic): ?>
                        <li class="topic-item">
                            <div class="topic-info">
                                <h3 class="topic-title"><a href="topic.php?id=<?php echo $topic['id']; ?>"><?php echo htmlspecialchars($topic['title']); ?></a></h3>
                                <div class="topic-meta">
                                    <div class="topic-author">
                                        <span>by <?php echo htmlspecialchars($topic['username']); ?></span>
                                    </div>
                                    <div><?php echo formatDate($topic['created_at']); ?></div>
                                </div>
                            </div>
                            <div class="topic-activity">
                                <div><?php echo $topic['replies']; ?> replies</div>
                                <div class="activity-time"><?php echo $topic['last_reply'] ? formatDate($topic['last_reply']) : 'No replies yet'; ?></div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Features -->
    <section class="features">
        <div class="container">
            <h2 class="section-title">Features</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-skull"></i></div>
                    <h3 class="feature-title">Zombie Plague Mod</h3>
                    <p class="feature-desc">Enjoy the ultimate Zombie Plague experience with custom zombie classes, unique game modes, and exclusive maps.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-trophy"></i></div>
                    <h3 class="feature-title">Level System</h3>
                    <p class="feature-desc">Earn XP, level up, and unlock new abilities and power-ups for your character.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon"><i class="fas fa-gamepad"></i></div>
                    <h3 class="feature-title">Special Events</h3>
                    <p class="feature-desc">Participate in weekly events with exclusive rewards, unique challenges, and limited-time modes.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Rules Section Preview -->
    <section class="rules-preview">
        <div class="container">
            <h2 class="section-title">Server Rules</h2>
            <div class="rules-preview-content">
                <p>We maintain a fair and enjoyable environment for all players. Make sure to check our complete rules before playing.</p>
                <a href="rules.php" class="btn btn-primary">View Complete Rules</a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>