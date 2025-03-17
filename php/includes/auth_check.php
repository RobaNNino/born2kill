<!-- includes/auth_check.php -->
<?php
// This file can be included on pages that require authentication
if (!isLoggedIn()) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    redirect("login.php");
    exit;
}