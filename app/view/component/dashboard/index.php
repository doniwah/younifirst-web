<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Beranda - YouniFirst' ?></title>
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="dashboard-container">
            <!-- Header -->
            <?php
            $page_title = 'Beranda';
            require_once __DIR__ . "/../../layouts/page-header.php";
            ?>

            <div class="dashboard-layout">
                <!-- Main Content -->
                <main class="dashboard-main">
                    <!-- Welcome Banner -->
                    <div class="welcome-banner">
                        <div class="welcome-text">
                            <h2>Selamat datang <?= htmlspecialchars($user_name) ?>!</h2>
                            <p>Saatnya terhubung dan seru-seruan bareng teman kampus di YouniFirst 👋</p>
                        </div>
                        <img src="/images/welcome-illustration.svg" alt="Welcome" onerror="this.style.display='none'">
                    </div>
                    <div class="feed-section">
                        <?php if (!empty($feed_posts)): ?>
                            <?php foreach ($feed_posts as $post): ?>
                                <div class="feed-card">
                                    <div class="post-header">
                                        <img src="<?= $post['user_avatar'] ?? '/images/avatar-default.png' ?>" alt="Avatar"
                                            onerror="this.style.display='none'">
                                        <div>
                                            <strong><?= htmlspecialchars($post['user_name'] ?? 'Admin') ?></strong>
                                            <span
                                                class="post-time"><?= htmlspecialchars($post['time_ago'] ?? 'Baru saja') ?></span>
                                        </div>
                                    </div>
                                    <div class="post-content">
                                        <?php if (!empty($post['image'])): ?>
                                            <img src="<?= htmlspecialchars($post['image']) ?>" alt="Post image"
                                                onerror="this.style.display='none'">
                                        <?php endif; ?>
                                        <?php if (!empty($post['title'])): ?>
                                            <h3><?= htmlspecialchars($post['title']) ?></h3>
                                        <?php endif; ?>
                                        <?php if (!empty($post['content'])): ?>
                                            <p><?= htmlspecialchars($post['content']) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($post['category'])): ?>
                                            <span class="event-badge competition">
                                                <?= htmlspecialchars($post['category']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="feed-card">
                                <div class="post-content text-center">
                                    <p style="color: var(--text-secondary);">Belum ada postingan terbaru</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </main>

                <!-- Sidebar -->
                <aside class="dashboard-sidebar">
                    <!-- Quick Search -->
                    <div class="search-box">
                        <h3>Pencarian cepat</h3>
                        <div class="search-input-wrapper">
                            <input type="text" placeholder="Cari event, kompetisi, forum..." id="quickSearch">
                            <button onclick="performSearch()">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- User Forums -->
                    <div class="sidebar-section">
                        <h3>Forum Anda</h3>
                        <?php if (!empty($user_forums)): ?>
                            <?php foreach ($user_forums as $forum): ?>
                                <div class="forum-item">
                                    <img src="<?= $forum['image'] ?? '/images/forum-placeholder.jpg' ?>" alt="Forum"
                                        onerror="this.style.display='none'">
                                    <div>
                                        <strong><?= htmlspecialchars($forum['name']) ?></strong>
                                        <?php if (isset($forum['code'])): ?>
                                            <span><?= htmlspecialchars($forum['code']) ?></span>
                                        <?php endif; ?>
                                        <div class="forum-meta">
                                            <span><i class="bi bi-people"></i> <?= $forum['members'] ?? 0 ?></span>
                                            <?php if (isset($forum['posts'])): ?>
                                                <span><i class="bi bi-chat"></i> <?= $forum['posts'] ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: var(--text-secondary); font-size: 14px; text-align: center; padding: 20px 0;">
                                Belum ada forum
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Upcoming Events -->
                    <div class="sidebar-section">
                        <h3>Event mendatang</h3>
                        <?php if (!empty($upcoming_events)): ?>
                            <?php foreach ($upcoming_events as $event): ?>
                                <div class="event-card">
                                    <img src="<?= $event['image'] ?? '/images/event-placeholder.jpg' ?>" alt="Event"
                                        onerror="this.style.display='none'">
                                    <div class="event-info">
                                        <strong><?= htmlspecialchars($event['title']) ?></strong>
                                        <span><i class="bi bi-calendar"></i> <?= htmlspecialchars($event['date']) ?></span>
                                        <span><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($event['location']) ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: var(--text-secondary); font-size: 14px; text-align: center; padding: 20px 0;">
                                Belum ada event mendatang
                            </p>
                        <?php endif; ?>
                    </div>

                    <!-- Upcoming Competitions -->
                    <div class="sidebar-section">
                        <h3>Kompetisi mendatang</h3>
                        <?php if (!empty($upcoming_competitions)): ?>
                            <?php foreach ($upcoming_competitions as $comp): ?>
                                <div class="competition-card">
                                    <img src="<?= $comp['image'] ?? '/images/competition-placeholder.jpg' ?>" alt="Competition"
                                        onerror="this.style.display='none'">
                                    <div class="comp-info">
                                        <strong><?= htmlspecialchars($comp['title']) ?></strong>
                                        <span><i class="bi bi-clock"></i> Deadline:
                                            <?= htmlspecialchars($comp['deadline']) ?></span>
                                        <?php if (isset($comp['category'])): ?>
                                            <span style="color: var(--success-color); font-size: 12px;">
                                                <?= htmlspecialchars($comp['category']) ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="color: var(--text-secondary); font-size: 14px; text-align: center; padding: 20px 0;">
                                Belum ada kompetisi mendatang
                            </p>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
        // Notification Dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('active');
        }

        function switchTab(event, tabName) {
            // Remove active from all tabs and contents
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

            // Add active to clicked tab and corresponding content
            event.target.classList.add('active');
            document.getElementById('tab-' + tabName).classList.add('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('notificationDropdown');
            const notifWrapper = document.querySelector('.notification-wrapper');

            if (dropdown && notifWrapper && !notifWrapper.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Dark Mode Toggle
        function toggleDarkMode() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);

            // Update button text
            const modeToggle = document.querySelector('.mode-toggle span');
            const modeIcon = document.querySelector('.mode-toggle i');

            if (newTheme === 'dark') {
                modeToggle.textContent = 'MODE GELAP';
                modeIcon.className = 'bi bi-moon';
            } else {
                modeToggle.textContent = 'MODE SIANG';
                modeIcon.className = 'bi bi-sun';
            }
        }

        // Load saved theme
        window.addEventListener('DOMContentLoaded', () => {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);

            if (savedTheme === 'dark') {
                const modeToggle = document.querySelector('.mode-toggle span');
                const modeIcon = document.querySelector('.mode-toggle i');
                modeToggle.textContent = 'MODE GELAP';
                modeIcon.className = 'bi bi-moon';
            }
        });

        // Quick Search
        function performSearch() {
            const searchTerm = document.getElementById('quickSearch').value;
            if (searchTerm.trim()) {
                alert('Mencari: ' + searchTerm + '\n(Fitur pencarian akan segera ditambahkan)');
            }
        }

        // Enter key for search
        document.getElementById('quickSearch')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    </script>
</body>

</html>