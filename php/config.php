<?php
// Database configuration for Altervista
$db_host = "localhost";
$db_name = ""; // Fill with your Altervista database name
$db_user = ""; // Fill with your Altervista username
$db_pass = ""; // Fill with your Altervista password

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set character set
$conn->set_charset("utf8mb4");

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// Function to sanitize input data
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Function to redirect
function redirect($url) {
    header("Location: $url");
    exit();
}

// Function to display error message
function showError($message) {
    return "<div class='error-message'>$message</div>";
}

// Function to display success message
function showSuccess($message) {
    return "<div class='success-message'>$message</div>";
}

// Function to get user data
function getUserData($user_id) {
    global $conn;
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to get forum categories
function getForumCategories() {
    global $conn;
    $sql = "SELECT c.*, 
           (SELECT COUNT(*) FROM forum_topics WHERE category_id = c.id) as topics_count,
           (SELECT COUNT(*) FROM forum_posts p JOIN forum_topics t ON p.topic_id = t.id WHERE t.category_id = c.id) as posts_count
           FROM forum_categories c
           ORDER BY c.sort_order";
    $result = $conn->query($sql);
    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    return $categories;
}

// Function to get topics for a category
function getCategoryTopics($category_id) {
    global $conn;
    $sql = "SELECT t.*, u.username as author, 
           (SELECT COUNT(*) FROM forum_posts WHERE topic_id = t.id) as replies,
           (SELECT MAX(created_at) FROM forum_posts WHERE topic_id = t.id) as last_reply_date,
           (SELECT u2.username FROM forum_posts p JOIN users u2 ON p.user_id = u2.id 
            WHERE p.topic_id = t.id ORDER BY p.created_at DESC LIMIT 1) as last_reply_author
           FROM forum_topics t
           JOIN users u ON t.user_id = u.id
           WHERE t.category_id = ?
           ORDER BY t.is_pinned DESC, t.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $topics = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $topics[] = $row;
        }
    }
    return $topics;
}

// Function to get posts for a topic
function getTopicPosts($topic_id) {
    global $conn;
    $sql = "SELECT p.*, u.username, u.avatar, u.registration_date, u.level, u.vip_status
           FROM forum_posts p
           JOIN users u ON p.user_id = u.id
           WHERE p.topic_id = ?
           ORDER BY p.created_at ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $topic_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $posts[] = $row;
        }
    }
    return $posts;
}

// Function to get topic details
function getTopicDetails($topic_id) {
    global $conn;
    $sql = "SELECT t.*, c.name as category_name, u.username as author
           FROM forum_topics t
           JOIN forum_categories c ON t.category_id = c.id
           JOIN users u ON t.user_id = u.id
           WHERE t.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $topic_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Function to update topic views
function incrementTopicViews($topic_id) {
    global $conn;
    $sql = "UPDATE forum_topics SET views = views + 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $topic_id);
    $stmt->execute();
}

// Function to get server status
function getServers() {
    global $conn;
    $sql = "SELECT * FROM servers ORDER BY id";
    $result = $conn->query($sql);
    $servers = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $servers[] = $row;
        }
    }
    return $servers;
}

// Function to format date
function formatDate($date) {
    $timestamp = strtotime($date);
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) {
        return 'Just now';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' minutes ago';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' hours ago';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' days ago';
    } else {
        return date('M j, Y', $timestamp);
    }
}
