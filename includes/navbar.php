<header class="navbar">
    <div class="navbar-content">
        <div class="logo">
            <a href="homepage.php"><img src="images/logo3.png" alt="BusEase Logo"></a>
        </div>
        <div class="nav-right">
            <?php if(isset($_SESSION['user'])): ?>
                <a href="about_us.php" class="nav-link">About Us</a>
                
                <span class="user-email">Hi, <?= htmlspecialchars($_SESSION['user']) ?></span>
                <a href="history.php" class="nav-link">History</a>
                <a href="logout.php" class="logout-btn">Logout</a>
            <?php else: ?>
                <a href="about_us.php" class="nav-link">About Us</a>
                <a href="index.php" class="nav-link">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header>