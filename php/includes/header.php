<!-- includes/header.php -->
<header>
    <div class="container">
        <nav>
            <div class="broken-pixel-container screen-shake">
                <div class="broken-pixel-text">Born2Kill</div>
                <div class="pixel-overlay">
                    <div class="dead-pixel"></div>
                    <div class="dead-pixel"></div>
                    <div class="dead-pixel"></div>
                    <div class="dead-pixel"></div>
                    <div class="dead-pixel"></div>
                    <div class="dead-pixel"></div>
                    <div class="stuck-pixel"></div>
                    <div class="stuck-pixel"></div>
                    <div class="stuck-pixel"></div>
                    <div class="stuck-pixel"></div>
                    <div class="h-glitch-line"></div>
                    <div class="h-glitch-line"></div>
                    <div class="h-glitch-line"></div>
                    <div class="text-disrupt"></div>
                    <div class="text-corrupt">B0RN2K1LL.exe</div>
                    <div class="v-tear"></div>
                    <div class="v-tear"></div>
                    <div class="full-glitch"></div>
                </div>
            </div>
            <div class="nav-links">
                <a href="index.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'class="active"' : ''; ?>>Home</a>
                <a href="index.php#servers" <?php echo (isset($_GET['section']) && $_GET['section'] == 'servers') ? 'class="active"' : ''; ?>>Servers</a>
                <a href="forum.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'forum.php' || basename($_SERVER['PHP_SELF']) == 'category.php' || basename($_SERVER['PHP_SELF']) == 'topic.php' || basename($_SERVER['PHP_SELF']) == 'new_topic.php') ? 'class="active"' : ''; ?>>Forum</a>
                <a href="rules.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'rules.php') ? 'class="active"' : ''; ?>>Rules</a>
                <a href="pricing.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'pricing.php') ? 'class="active"' : ''; ?>>Pricing</a>
                <?php if (isLoggedIn() && isAdmin()): ?>
                <a href="admin/index.php" class="admin-link">Admin</a>
                <?php endif; ?>
            </div>
            <div class="auth-links">
                <?php if (isLoggedIn()): ?>
                <div class="user-dropdown">
                    <button class="dropdown-btn">
                        <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fas fa-caret-down"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
                <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="register.php" class="btn btn-primary">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>