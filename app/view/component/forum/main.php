<!-- Hero Banner -->
<div class="forum-hero">
    <div class="forum-hero-content">
        <h2>Selamat datang di forum! Ayo mulai berdiskusi dan berbagi ide dengan komunitasmu.</h2>
    </div>
    <img src="https://img.freepik.com/free-vector/flat-design-business-people-working-together_23-2148972580.jpg" alt="Forum Illustration" class="forum-hero-image">
</div>

<div class="forum-layout">
    <!-- Main Content: Trending -->
    <div class="forum-main">
        <h3 class="section-title">Sedang Tren</h3>
        
        <div class="trending-list">
            <?php if (isset($trending_topics) && !empty($trending_topics)): ?>
                <?php foreach ($trending_topics as $topic): ?>
                <div class="trending-card">
                    <div class="trending-content">
                        <h4 class="trending-title"><?= htmlspecialchars($topic['title']) ?></h4>
                        <p class="trending-excerpt"><?= htmlspecialchars($topic['excerpt']) ?></p>
                        
                        <div class="trending-meta">
                            <div class="user-info">
                                <img src="<?= htmlspecialchars($topic['user_avatar']) ?>" alt="User" class="user-avatar">
                                <span class="user-name"><?= htmlspecialchars($topic['user_name']) ?></span>
                            </div>
                            
                            <div class="post-stats">
                                <div class="stat-item">
                                    <i class="bi bi-people"></i>
                                    <span><?= htmlspecialchars($topic['views']) ?></span>
                                </div>
                                <div class="stat-item">
                                    <i class="bi bi-chat"></i>
                                    <span><?= htmlspecialchars($topic['comments']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <img src="<?= htmlspecialchars($topic['thumbnail']) ?>" alt="Thumbnail" class="trending-thumbnail">
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada topik trending saat ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="forum-sidebar">
        <!-- Search -->
        <div class="search-section">
            <h3 class="section-title">Pencarian</h3>
            <div class="search-box">
                <input type="text" placeholder="Cari" class="search-input">
                <i class="bi bi-search search-icon"></i>
            </div>
        </div>

        <!-- User Forums -->
        <div class="user-forums-section">
            <h3 class="section-title">Forum Anda</h3>
            
            <div class="user-forums-list">
                <?php if (isset($user_forums) && !empty($user_forums)): ?>
                    <?php foreach ($user_forums as $forum): ?>
                    <div class="forum-card-small">
                        <div class="forum-info-small">
                            <a href="/forum/chat?id=<?= $forum['id'] ?>" class="forum-name-link">
                                <h4 class="forum-name-small"><?= htmlspecialchars($forum['name']) ?></h4>
                            </a>
                            <p class="forum-code"><?= htmlspecialchars($forum['code']) ?></p>
                            
                            <div class="forum-meta-small">
                                <div class="user-info">
                                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=<?= htmlspecialchars($forum['user_handle']) ?>" alt="User" class="user-avatar" style="width: 20px; height: 20px;">
                                    <span class="user-name" style="font-size: 0.75rem;"><?= htmlspecialchars($forum['user_handle']) ?></span>
                                </div>
                                <div class="post-stats" style="margin-left: 0; gap: 8px;">
                                    <div class="stat-item">
                                        <i class="bi bi-people"></i>
                                        <span><?= htmlspecialchars($forum['members']) ?></span>
                                    </div>
                                    <div class="stat-item">
                                        <i class="bi bi-chat"></i>
                                        <span><?= htmlspecialchars($forum['messages']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <img src="<?= htmlspecialchars($forum['thumbnail']) ?>" alt="Thumbnail" class="forum-thumbnail-small">
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Anda belum bergabung dengan forum apapun.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>