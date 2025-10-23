<nav class="navbar">
    <div class="logo">
        <span>YouNiFirst</span>
    </div>
    <?php
    session_start();
    ?>
    <?php if (isset($_SESSION['user_id'])): { ?>
    <div class="btn_nav">
        <a href="/dashboard">
            <i class="bi bi-house"></i>Dashboard
        </a>
        <a href="/kompetisi">
            <i class="bi bi-trophy"></i>Kompetisi
        </a>
        <a href="/lost_found">
            <i class="bi bi-box-seam"></i>Lost & Found
        </a>
        <a href="/event">
            <i class="bi bi-calendar4"></i>Event
        </a>
        <a href="/forum">
            <i class="bi bi-chat-left"></i>Forum
        </a>
    </div>
    <?php } ?>
    <?php endif; ?>

    <div style="display: flex; align-items: center; gap: 15px;">
        <!-- <a href="/users/logout" class="logout_btn">
            <i class="bi bi-arrow-bar-right"></i>Logout
        </a> -->
    </div>

    <a href="/users/login" class="login-btn">Login</a>

</nav>