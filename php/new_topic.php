<?php
require_once 'config.php';

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect("login.php");
}

$error = "";
$success = "";
$categories = getForumCategories();
$selected_category = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = sanitize($_POST["title"]);
    $content = trim($_POST["content"]);
    $category_id = (int)$_POST["category_id"];
    
    // Validate form data
    if (empty($title) || empty($content) || $category_id <= 0) {
        $error = "Please fill in all fields and select a category";
    } else {
        // Check if category exists
        $categoryExists = false;
        foreach ($categories as $cat) {
            if ($cat['id'] == $category_id) {
                $categoryExists = true;
                break;
            }
        }
        
        if (!$categoryExists) {
            $error = "Invalid category selected";
        } else {
            // Start transaction
            $conn->begin_transaction();
            
            try {
                // Insert new topic
                $sql = "INSERT INTO forum_topics (category_id, user_id, title) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $user_id = $_SESSION['user_id'];
                $stmt->bind_param("iis", $category_id, $user_id, $title);
                $stmt->execute();
                
                $topic_id = $stmt->insert_id;
                
                // Insert first post
                $sql = "INSERT INTO forum_posts (topic_id, user_id, content) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iis", $topic_id, $user_id, $content);
                $stmt->execute();
                
                // Commit transaction
                $conn->commit();
                
                // Redirect to new topic
                redirect("topic.php?id=$topic_id");
                
            } catch (Exception $e) {
                // Rollback on error
                $conn->rollback();
                $error = "Error creating topic: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Topic - Born2Kill Forum</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'includes/header.php'; ?>

    <!-- New Topic Section -->
    <section class="new-topic">
        <div class="container">
            <h2 class="section-title">Create New Topic</h2>
            
            <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="new_topic.php">
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($selected_category == $category['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="title">Topic Title</label>
                    <input type="text" id="title" name="title" maxlength="255" required>
                </div>
                
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" rows="12" required></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Topic</button>
                    <a href="forum.php" class="btn btn-outline">Cancel</a>
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