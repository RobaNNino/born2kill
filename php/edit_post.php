<?php
require_once 'config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect("login.php");
}

$error = "";
$success = "";

// Check if post ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    redirect("forum.php");
}

$post_id = (int)$_GET['id'];

// Get post details
$sql = "SELECT p.*, t.title as topic_title, t.id as topic_id, t.is_locked 
        FROM forum_posts p 
        JOIN forum_topics t ON p.topic_id = t.id 
        WHERE p.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    redirect("forum.php");
}

$post = $result->fetch_assoc();

// Check if user has permission to edit this post
if ($post['user_id'] != $_SESSION['user_id'] && !isAdmin()) {
    redirect("topic.php?id=" . $post['topic_id']);
}

// Check if topic is locked and user is not admin
if ($post['is_locked'] && !isAdmin()) {
    redirect("topic.php?id=" . $post['topic_id']);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = trim($_POST["content"]);
    
    if (empty($content)) {
        $error = "Post content cannot be empty";
    } else {
        // Update post
        $sql = "UPDATE forum_posts SET content = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $content, $post_id);
        
        if ($stmt->execute()) {
            $success = "Post updated successfully";
            // Redirect back to topic after 2 seconds
            header("refresh:2;url=topic.php?id=" . $post['topic_id'] . "#post-" . $post_id);
        } else {
            $error = "Error updating post: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post - Born2Kill Forum</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- Edit Post Section -->
    <section class="edit-post">
        <div class="container">
            <h2 class="section-title">Edit Post</h2>
            
            <div class="topic-info">
                <p>Topic: <a href="topic.php?id=<?php echo $post['topic_id']; ?>"><?php echo htmlspecialchars($post['topic_title']); ?></a></p>
            </div>
            
            <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="edit_post.php?id=<?php echo $post_id; ?>">
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="12" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="topic.php?id=<?php echo $post['topic_id']; ?>#post-<?php echo $post_id; ?>" class="btn btn-outline">Cancel</a>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <?php include 'includes/footer.php'; ?>

    <!-- Scripts -->
    <script src="js/main.js"></script>
</body>
</html>