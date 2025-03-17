<?php
require_once 'config.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect("login.php");
}

$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitize($_POST["email"]);
    $current_password = isset($_POST["current_password"]) ? $_POST["current_password"] : "";
    $new_password = isset($_POST["new_password"]) ? $_POST["new_password"] : "";
    $confirm_password = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";
    
    // Get current user data
    $user = getUserData($_SESSION['user_id']);
    
    // Validate email
    if (empty($email)) {
        $error = "Email cannot be empty";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } else {
        // Check if email already exists for another user
        $sql = "SELECT id FROM users WHERE email = ? AND id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $email, $_SESSION['user_id']);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "Email already exists";
        }
    }
    
    // Check if password change is requested
    $update_password = false;
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        // All password fields must be filled
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "All password fields must be filled to change password";
        } elseif (!password_verify($current_password, $user['password'])) {
            $error = "Current password is incorrect";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match";
        } elseif (strlen($new_password) < 6) {
            $error = "New password must be at least 6 characters long";
        } else {
            $update_password = true;
        }
    }
    
    // Handle avatar upload
    $update_avatar = false;
    $new_avatar = $user['avatar'];
    
    if (isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) {
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
        $file_name = $_FILES['avatar']['name'];
        $file_size = $_FILES['avatar']['size'];
        $file_tmp = $_FILES['avatar']['tmp_name'];
        $file_type = $_FILES['avatar']['type'];
        $file_ext_array = explode('.', $file_name);
        $file_ext = strtolower(end($file_ext_array));
        
        if (!in_array($file_ext, $allowed_extensions)) {
            $error = "Extension not allowed. Please upload a JPG, JPEG, PNG or GIF file.";
        } elseif ($file_size > 2097152) { // 2MB
            $error = "File size must be less than 2 MB";
        } else {
            $new_avatar = $_SESSION['user_id'] . '_' . time() . '.' . $file_ext;
            $upload_path = 'images/avatars/' . $new_avatar;
            $update_avatar = true;
        }
    }
    
    // Update user data if no errors
    if (empty($error)) {
        // Start transaction
        $conn->begin_transaction();
        
        try {
            // Update email (and avatar if changed)
            if ($update_avatar) {
                $sql = "UPDATE users SET email = ?, avatar = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $email, $new_avatar, $_SESSION['user_id']);
            } else {
                $sql = "UPDATE users SET email = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $email, $_SESSION['user_id']);
            }
            
            $stmt->execute();
            
            // Update password if requested
            if ($update_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $hashed_password, $_SESSION['user_id']);
                $stmt->execute();
            }
            
            // Upload avatar file if changed
            if ($update_avatar) {
                // Create avatars directory if it doesn't exist
                if (!file_exists('images/avatars')) {
                    mkdir('images/avatars', 0777, true);
                }
                
                // Move uploaded file
                move_uploaded_file($file_tmp, $upload_path);
                
                // Delete old avatar if it's not the default
                if ($user['avatar'] != 'default.jpg' && file_exists('images/avatars/' . $user['avatar'])) {
                    unlink('images/avatars/' . $user['avatar']);
                }
            }
            
            // Commit transaction
            $conn->commit();
            
            $success = "Profile updated successfully";
        } catch (Exception $e) {
            // Rollback on error
            $conn->rollback();
            $error = "Error updating profile: " . $e->getMessage();
        }
    }
}

// Redirect back to profile page with message
if (!empty($success)) {
    $_SESSION['success_message'] = $success;
} elseif (!empty($error)) {
    $_SESSION['error_message'] = $error;
}

redirect("profile.php");
