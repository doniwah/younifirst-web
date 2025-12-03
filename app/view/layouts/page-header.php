<!-- Page Header with Notifications and Theme Toggle -->
<header class="dashboard-header">
    <h1><?= $page_title ?? 'Beranda' ?></h1>
    <div class="header-actions">
        <div class="notification-wrapper">
            <button class="notification-btn" onclick="toggleNotifications()">
                <i class="bi bi-bell"></i>
                <?php if (isset($notifications_count) && $notifications_count > 0): ?>
                    <span class="badge" id="notificationBadge"><?= $notifications_count ?></span>
                <?php endif; ?>
            </button>
            
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-tabs">
                    <button class="tab-btn active" onclick="switchNotifTab(event, 'new')">Baru</button>
                    <button class="tab-btn" onclick="switchNotifTab(event, 'today')">Hari ini</button>
                </div>
                <div class="tab-content active" id="tab-new">
                    <div class="notification-list">
                        <?php 
                        $notifications = $new_notifications ?? $user_forums ?? [];
                        if (!empty($notifications)): 
                        ?>
                            <?php foreach ($notifications as $notif): ?>
                                <div class="notification-item" onclick="window.location.href='<?= 
                                    $notif['type'] === 'event' ? '/event' : 
                                    ($notif['type'] === 'lost_found' ? '/lost_found' : '/forum') 
                                ?>'" style="cursor: pointer;">
                                    <img src="<?= $notif['image'] ?? '/images/avatar-default.png' ?>" 
                                         alt="Icon"
                                         style="<?= ($notif['type'] ?? 'forum') === 'event' ? 'border-radius: 8px;' : '' ?>"
                                         onerror="this.style.display='none'">
                                    <div class="notification-content">
                                        <p class="notification-text">
                                            <?php if (($notif['type'] ?? 'forum') === 'event'): ?>
                                                <strong>Event Baru:</strong> <?= htmlspecialchars($notif['name']) ?> telah ditambahkan.
                                            <?php elseif (($notif['type'] ?? 'forum') === 'lost_found'): ?>
                                                <strong>Info Kehilangan:</strong> <?= htmlspecialchars($notif['name']) ?> baru saja dilaporkan.
                                            <?php else: ?>
                                                <strong><?= htmlspecialchars($notif['name']) ?></strong> 
                                                menyetujui permintaan Anda bergabung ke forum
                                            <?php endif; ?>
                                        </p>
                                        <div class="notification-meta">
                                            <?php if (($notif['type'] ?? 'forum') === 'event'): ?>
                                                <span><i class="bi bi-calendar-event"></i> Event</span>
                                            <?php elseif (($notif['type'] ?? 'forum') === 'lost_found'): ?>
                                                <span><i class="bi bi-search"></i> Lost & Found</span>
                                            <?php else: ?>
                                                <span><?= $notif['code'] ?? 'Forum' ?></span>
                                                <span><i class="bi bi-people"></i> <?= $notif['members'] ?? 0 ?></span>
                                            <?php endif; ?>
                                            <span style="margin-left: 5px; font-size: 0.8em; color: #888;">
                                                <?= isset($notif['created_at']) ? date('d M H:i', strtotime($notif['created_at'])) : '' ?>
                                            </span>
                                        </div>
                                    </div>
                                    <?php if (isset($notif['image']) && ($notif['type'] ?? 'forum') === 'forum'): ?>
                                        <img src="<?= $notif['image'] ?>" 
                                             class="notification-thumb"
                                             alt="Thumbnail"
                                             onerror="this.style.display='none'">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                            <button class="btn-view-all" onclick="window.location.href='/forum'">
                                Lihat Forum
                            </button>
                        <?php else: ?>
                            <p class="no-notifications">Belum ada notifikasi baru</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Tab: Hari ini (Today's Activities) -->
                <div class="tab-content" id="tab-today">
                    <div class="notification-list">
                        <?php if (!empty($feed_posts)): ?>
                            <?php foreach (array_slice($feed_posts, 0, 3) as $post): ?>
                                <div class="notification-item">
                                    <img src="<?= $post['user_avatar'] ?? '/images/avatar-default.png' ?>" 
                                         alt="Avatar"
                                         onerror="this.style.display='none'">
                                    <div class="notification-content">
                                        <p class="notification-text">
                                            <strong><?= htmlspecialchars($post['user_name']) ?></strong> 
                                            membalas komentar Anda di postingan: 
                                            "<?= htmlspecialchars(substr($post['title'] ?? '', 0, 30)) ?>..."
                                        </p>
                                        <span class="notification-time"><?= $post['time_ago'] ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <button class="btn-view-all" onclick="window.location.href='/kompetisi'">
                                Lihat Lomba
                            </button>
                        <?php else: ?>
                            <p class="no-notifications">Belum ada aktivitas hari ini</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <button class="mode-toggle" onclick="toggleDarkMode()">
            <i class="bi bi-sun"></i>
            <span>MODE SIANG</span>
        </button>
    </div>
</header>

<script>
// Notification Dropdown
function toggleNotifications() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('active');
    
    // Hide badge when dropdown is opened
    if (dropdown.classList.contains('active')) {
        const badge = document.getElementById('notificationBadge');
        if (badge) {
            badge.style.display = 'none';
        }
        
        // Set cookie to remember last check time
        document.cookie = "last_notif_check=" + new Date().toISOString() + "; path=/; max-age=31536000"; // 1 year
    }
}

function switchNotifTab(event, tabName) {
    // Remove active from all tabs and contents
    document.querySelectorAll('.notification-tabs .tab-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.notification-dropdown .tab-content').forEach(content => content.classList.remove('active'));
    
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
        if (modeToggle) modeToggle.textContent = 'MODE GELAP';
        if (modeIcon) modeIcon.className = 'bi bi-moon';
    }
});
</script>
