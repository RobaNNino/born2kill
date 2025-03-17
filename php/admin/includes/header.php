<!-- admin/includes/header.php -->
<header class="admin-header">
    <div class="container">
        <nav class="admin-nav">
            <div class="admin-logo">
                <a href="index.php">Born2Kill Admin</a>
            </div>
            <div class="admin-nav-links">
                <a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Dashboard</a>
                <a href="users.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'class="active"' : ''; ?>>Users</a>
                <a href="forum.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'forum.php') ? 'class="active"' : ''; ?>>Forum</a>
                <a href="servers.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'servers.php') ? 'class="active"' : ''; ?>>Servers</a>
                <a href="settings.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'class="active"' : ''; ?>>Settings</a>
            </div>
            <div class="admin-user-menu">
                <div class="user-dropdown">
                    <button class="dropdown-btn">
                        <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="../profile.php"><i class="fas fa-user"></i> Profile</a>
                        <a href="../index.php"><i class="fas fa-home"></i> Website</a>
                        <a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>