<?php
require_once 'config.php';

// Check if topic ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect("forum.php");
}

$topic_id = (int)$_GET['id'];

// Get topic details
$topic = getTopicDetails($topic_id);

if (!$topic) {
    redirect("forum.php");
}

// Increment view count
incrementTopicViews($topic_id);

// Get posts for this topic
$posts = getTopicPosts($topic_id);

// Handle new reply submission
$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isLoggedIn()) {
    $content = trim($_POST["content"]);
    
    if (empty($content)) {
        $error = "Reply content cannot be empty";
    } else {
        // Insert new post
        $sql = "INSERT INTO forum_posts (topic_id, user_id, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $user_id = $_SESSION['user_id'];
        $stmt->bind_param("iis", $topic_id, $user_id, $content);
        
        if ($stmt->execute()) {
            $success = "Reply posted successfully";
            // Reload the page to show the new post
            redirect("topic.php?id=$topic_id#post-" . $stmt->insert_id);
        } else {
            $error = "Error posting reply: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($topic['title']); ?> - Born2Kill Forum</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Topic Section -->
    <section class="forum-topic">
        <div class="container">
            <div class="topic-header">
                <div class="breadcrumb">
                    <a href="forum.php">Forum</a> &raquo; 
                    <a href="category.php?id=<?php echo $topic['category_id']; ?>"><?php echo htmlspecialchars($topic['category_name']); ?></a> &raquo; 
                    <span><?php echo htmlspecialchars($topic['title']); ?></span>
                </div>
                
                <?php if (isLoggedIn() && !$topic['is_locked']): ?>
                <a href="#reply-form" class="btn btn-primary">
                    <i class="fas fa-reply"></i> Reply
                </a>
                <?php endif; ?>
            </div>
            
            <?php if ($topic['is_locked']): ?>
            <div class="topic-closed-notice">
                <i class="fas fa-lock"></i> This topic is locked and no longer accepts new replies.
            </div>
            <?php endif; ?>
            
            <div class="posts-container">
                <?php foreach ($posts as $index => $post): ?>
                <div class="post-card" id="post-<?php echo $post['id']; ?>">
                    <div class="post-sidebar">
                        <div class="post-author">
                            <div class="author-avatar">
                                <img src="images/avatars/<?php echo htmlspecialchars($post['avatar']); ?>" alt="<?php echo htmlspecialchars($post['username']); ?>">
                            </div>
                            <div class="author-name"><?php echo htmlspecialchars($post['username']); ?></div>
                            <?php if ($post['vip_status'] != 'none'): ?>
                            <div class="author-vip"><?php echo ucfirst($post['vip_status']); ?> VIP</div>
                            <?php endif; ?>
                            <div class="author-level">Level: <?php echo $post['level']; ?></div>
                            <div class="author-joined">Joined: <?php echo date('M Y', strtotime($post['registration_date'])); ?></div>
                        </div>
                    </div>
                    
                    <div class="post-content">
                        <div class="post-header">
                            <div class="post-date">
                                <?php echo date('M d, Y g:i A', strtotime($post['created_at'])); ?>
                                <?php if ($post['created_at'] != $post['updated_at'] && $post['updated_at']): ?>
                                <span class="post-edited">(Edited: <?php echo date('M d, Y g:i A', strtotime($post['updated_at'])); ?>)</span>
                                <?php endif; ?>
                            </div>
                            <div class="post-actions">
                                <a href="#post-<?php echo $post['id']; ?>" class="post-link">#<?php echo $index + 1; ?></a>
                                <?php if (isLoggedIn() && ($post['user_id'] == $_SESSION['user_id'] || isAdmin())): ?>
                                <a href="edit_post.php?id=<?php echo $post['id']; ?>" class="post-edit-link"><i class="fas fa-edit"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="post-text">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Reply Form -->
            <?php if (isLoggedIn() && !$topic['is_locked']): ?>
            <div class="reply-form-container" id="reply-form">
                <h3>Post Reply</h3>
                
                <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                <div class="success-message"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="topic.php?id=<?php echo $topic_id; ?>#reply-form">
                    <div class="form-group">
                        <textarea name="content" rows="8" required></textarea>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit Reply</button>
                    </div>
                </form>
            </div>
            <?php elseif (!isLoggedIn()): ?>
            <div class="login-to-reply">
                <p>You must be <a href="login.php">logged in</a> to post a reply.</p>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>