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
                <div class="trending-card" onclick="window.location.href='/forum/chat?id=<?= $topic['id'] ?>'" style="cursor: pointer;">
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

        <!-- User Forums (Joined) -->
        <div class="user-forums-section">
            <h3 class="section-title">Forum Anda</h3>
            
            <div class="user-forums-list">
                <?php if (isset($user_forums) && !empty($user_forums)): ?>
                    <?php foreach ($user_forums as $forum): ?>
                    <div class="forum-card-small" onclick="window.location.href='/forum/chat?id=<?= $forum['id'] ?>'" style="cursor: pointer;">
                        <div class="forum-info-small">
                            <h4 class="forum-name-small"><?= htmlspecialchars($forum['name']) ?></h4>
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
                    <p class="text-muted" style="font-size: 0.9rem;">Anda belum bergabung dengan forum apapun.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Available Forums (Discovery) -->
        <?php if (isset($available_forums) && !empty($available_forums)): ?>
        <div class="user-forums-section" style="margin-top: 30px;">
            <h3 class="section-title">Eksplor Forum</h3>
            
            <div class="user-forums-list">
                <?php foreach ($available_forums as $forum): ?>
                <div class="forum-card-small" onclick="window.location.href='/forum/chat?id=<?= $forum['id'] ?>'" style="cursor: pointer; border-left: 4px solid #4A90E2;">
                    <div class="forum-info-small">
                        <div style="display: flex; justify-content: space-between; align-items: start;">
                            <h4 class="forum-name-small"><?= htmlspecialchars($forum['name']) ?></h4>
                            <span class="badge bg-primary" style="font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; background: #e0e7ff; color: #4A90E2;">Join</span>
                        </div>
                        <p class="forum-code"><?= htmlspecialchars($forum['code']) ?></p>
                        
                        <div class="forum-meta-small">
                            <div class="post-stats" style="margin-left: 0; gap: 8px;">
                                <div class="stat-item">
                                    <i class="bi bi-people"></i>
                                    <span><?= htmlspecialchars($forum['members']) ?> anggota</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <img src="<?= htmlspecialchars($forum['thumbnail']) ?>" alt="Thumbnail" class="forum-thumbnail-small">
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>