<?php
if (!isset($_SESSION)) session_start();

// Support both session structures for compatibility
$isLoggedIn = isset($_SESSION['user']) || isset($_SESSION['user_id']);
$userEmail = $_SESSION['user']['email'] ?? $_SESSION['email'] ?? '';
?>
<nav class="navbar">
    <div class="logo">
        <span>YouNiFirst</span>
    </div>
    <div class="btn_nav">
        <?php
        $current_route = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        ?>
        <?php if ($isLoggedIn): ?>
        <a href="/dashboard" class="<?= ($current_route == '/dashboard') ? 'active' : '' ?>">
            <i class="bi bi-house"></i>Dashboard
        </a>
        <a href="/kompetisi" class="<?= ($current_route == '/kompetisi') ? 'active' : '' ?>">
            <i class="bi bi-trophy"></i>Kompetisi
        </a>
        <a href="/lost_found" class="<?= ($current_route == '/lost_found') ? 'active' : '' ?>">
            <i class="bi bi-box-seam"></i>Lost & Found
        </a>
        <a href="/event" class="<?= ($current_route == '/event') ? 'active' : '' ?>">
            <i class="bi bi-calendar4"></i>Event
        </a>
        <a href="/forum" class="<?= ($current_route == '/forum') ? 'active' : '' ?>">
            <i class="bi bi-chat-left"></i>Forum
        </a>
        <?php endif; ?>
    </div>
    <?php if ($isLoggedIn): ?>
    <div style="display: flex; align-items: center; gap: 15px;">
        <?php if ($userEmail): ?>
        <span style="color: #666; font-size: 14px;">
            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($userEmail) ?>
        </span>
        <?php endif; ?>
        <a href="/logout" class="logout_btn">
            <i class="bi bi-arrow-bar-right"></i>Logout
        </a>
    </div>
    <?php else: ?>
    <a href="/login" class="login-btn">Login</a>
    <?php endif; ?>
</nav>