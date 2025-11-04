<?php

use App\Service\SessionService;

require_once __DIR__ . '/../../../vendor/autoload.php';

$sessionService = new SessionService();
$user = $sessionService->current();

$currentPage = $_SERVER['REQUEST_URI'];

?>

<nav class="navbar">
    <div class="logo">
        <span>YouNiFirst</span>
    </div>

    <?php if ($user): ?>
    <div class="btn_nav">
        <a href="/dashboard" class="<?= ($currentPage == '/dashboard') ? 'active' : '' ?>"><i
                class=" bi bi-house"></i>Dashboard</a>
        <a href="/kompetisi" class="<?= ($currentPage == '/kompetisi') ? 'active' : '' ?>"><i
                class="bi bi-trophy"></i>Kompetisi</a>
        <a href="/lost_found" class="<?= ($currentPage == '/lost_found') ? 'active' : '' ?>"><i
                class="bi bi-box-seam"></i>Lost & Found</a>
        <a href="/event" class="<?= ($currentPage == '/event') ? 'active' : '' ?>"><i
                class="bi bi-calendar4"></i>Event</a>
        <a href="/forum" class="<?= ($currentPage == '/forum') ? 'active' : '' ?>"><i
                class="bi bi-chat-left"></i>Forum</a>
    </div>

    <div style="display: flex; align-items: center; gap: 15px;">
        <a href="/users/logout" class="logout_btn">
            <i class="bi bi-arrow-bar-right"></i>Logout
        </a>
    </div>

    <?php else: ?>
    <a href="/users/login" class="login-btn">Login</a>
    <?php endif; ?>
</nav>