<?php
require_once 'config.php';

// Check if category ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect("forum.php");
}

$category_id = (int)$_GET['id'];

// Get category details
$sql = "SELECT * FROM forum_categories WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $category_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirect("forum.php");
}

$category = $result->fetch_assoc();

// Get topics for this category
$topics = getCategoryTopics($category_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - Born2Kill Forum</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Category Section -->
    <section class="forum-category">
        <div class="container">
            <div class="category-header">
                <div class="breadcrumb">
                    <a href="forum.php">Forum</a> &raquo; 
                    <span><?php echo htmlspecialchars($category['name']); ?></span>
                </div>
                
                <?php if (isLoggedIn()): ?>
                <a href="new_topic.php?category_id=<?php echo $category_id; ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> New Topic
                </a>
                <?php endif; ?>
            </div>
            
            <div class="category-description">
                <?php echo htmlspecialchars($category['description']); ?>
            </div>
            
            <div class="topics-list">
                <?php if (empty($topics)): ?>
                <div class="no-topics">
                    <p>No topics in this category yet. <?php echo isLoggedIn() ? 'Be the first to create one!' : 'Login to create a topic.'; ?></p>
                </div>
                <?php else: ?>
                <div class="topic-header">
                    <div class="topic-title-header">Topic</div>
                    <div class="topic-stats-header">
                        <div>Replies</div>
                        <div>Views</div>
                    </div>
                    <div class="topic-last-post-header">Last Post</div>
                </div>
                
                <?php foreach ($topics as $topic): ?>
                <div class="topic-row <?php echo $topic['is_pinned'] ? 'pinned' : ''; ?>">
                    <div class="topic-title-col">
                        <?php if ($topic['is_pinned']): ?>
                            <i class="fas fa-thumbtack pin-icon"></i>
                        <?php endif; ?>
                        
                        <?php if ($topic['is_locked']): ?>
                            <i class="fas fa-lock lock-icon"></i>
                        <?php endif; ?>
                        
                        <a href="topic.php?id=<?php echo $topic['id']; ?>"><?php echo htmlspecialchars($topic['title']); ?></a>
                        <div class="topic-author">
                            Started by <?php echo htmlspecialchars($topic['author']); ?>, <?php echo formatDate($topic['created_at']); ?>
                        </div>
                    </div>
                    
                    <div class="topic-stats">
                        <div><?php echo $topic['replies']; ?></div>
                        <div><?php echo $topic['views']; ?></div>
                    </div>
                    
                    <div class="topic-last-post">
                        <?php if ($topic['last_reply_date']): ?>
                            <div><?php echo formatDate($topic['last_reply_date']); ?></div>
                            <div>by <?php echo htmlspecialchars($topic['last_reply_author']); ?></div>
                        <?php else: ?>
                            <div>No replies yet</div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>