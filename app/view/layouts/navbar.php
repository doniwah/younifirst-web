    <?php if (!isset($_SESSION)) session_start(); ?>
    <nav class="navbar">
        <div class="logo">
            <span>YouNiFirst</span>
        </div>
        <div class="btn_nav">
            <?php if (isset($_SESSION['user'])): ?>
                <a href=""><i class="bi bi-house"></i>Dashboard</a>
                <a href=""><i class="bi bi-trophy"></i>Kompetisi</a>
                <a href=""><i class="bi bi-box-seam"></i>Lost & Found</a>
                <a href=""><i class="bi bi-calendar4"></i>Event</a>
                <a href=""><i class="bi bi-chat-left"></i>Forum</a>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="/logout" class="logout_btn"><i class="bi bi-arrow-bar-right"></i>Logout</a>
        <?php else: ?>
            <a href="/login" class="login-btn">Login</button></a>
        <?php endif; ?>
    </nav>