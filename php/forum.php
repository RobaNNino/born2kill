<!-- forum.php - Main forum page with categories -->
<?php
require_once 'config.php';

// Get all forum categories
$categories = getForumCategories();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forum - Born2Kill Community</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Forum Section -->
    <section class="forum-main">
        <div class="container">
            <div class="forum-header-main">
                <h2 class="section-title">Forum</h2>
                <?php if (isLoggedIn()): ?>
                    <a href="new_topic.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Topic</a>
                <?php endif; ?>
            </div>
            
            <div class="forum-grid">
                <?php foreach ($categories as $category): ?>
                <div class="forum-card">
                    <div class="forum-header">
                        <div class="forum-title">
                            <a href="category.php?id=<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></a>
                        </div>
                        <div class="forum-stats">
                            <div class="forum-stat">
                                <span>Topics:</span>
                                <strong><?php echo $category['topics_count']; ?></strong>
                            </div>
                            <div class="forum-stat">
                                <span>Posts:</span>
                                <strong><?php echo $category['posts_count']; ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="forum-description">
                        <?php echo htmlspecialchars($category['description']); ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Forum Stats Section -->
    <section class="forum-stats-section">
        <div class="container">
            <h3>Forum Statistics</h3>
            <div class="stats-flex">
                <?php
                // Get stats
                $totalTopics = $conn->query("SELECT COUNT(*) as count FROM forum_topics")->fetch_assoc()['count'];
                $totalPosts = $conn->query("SELECT COUNT(*) as count FROM forum_posts")->fetch_assoc()['count'];
                $totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
                $newestUser = $conn->query("SELECT username FROM users ORDER BY registration_date DESC LIMIT 1")->fetch_assoc()['username'];
                ?>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalTopics; ?></span>
                    <span class="stat-label">Topics</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalPosts; ?></span>
                    <span class="stat-label">Posts</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo $totalUsers; ?></span>
                    <span class="stat-label">Members</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value"><?php echo htmlspecialchars($newestUser); ?></span>
                    <span class="stat-label">Newest Member</span>
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
