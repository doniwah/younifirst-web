    <?php if (!isset($_SESSION)) session_start(); ?>
    <nav class="navbar">
        <div class="logo">
            <span>YouNiFirst</span>
        </div>
        <div class="btn_nav">
            <?php
            $current_route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            ?>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="/dashboard" class="<?= ($current_route == '/dashboard') ? 'active' : '' ?>"><i
                        class="bi bi-house"></i>Dashboard</a>
                <a href="/kompetisi" class="<?= ($current_route == '/kompetisi') ? 'active' : '' ?>"><i
                        class="bi bi-trophy"></i>Kompetisi</a>
                <a href="/lost_found" class="<?= ($current_route == '/lost_found') ? 'active' : '' ?>"><i
                        class="bi bi-box-seam"></i>Lost & Found</a>
                <a href="/event" class="<?= ($current_route == '/event') ? 'active' : '' ?>"><i
                        class="bi bi-calendar4"></i>Event</a>
                <a href="/forum" class="<?= ($current_route == '/forum') ? 'active' : '' ?>"><i
                        class="bi bi-chat-left"></i>Forum</a>
            <?php endif; ?>
        </div>
        <?php if (isset($_SESSION['user'])): ?>
            <a href="/logout" class="logout_btn"><i class="bi bi-arrow-bar-right"></i>Logout</a>
        <?php else: ?>
            <a href="/login" class="login-btn">Login</button></a>
        <?php endif; ?>
    </nav>