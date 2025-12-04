<div class="sidebar" id="sidebar">
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <div class="logo-section">
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">
                <h1>Younifirst</h1>
            </div>
        </div>
    </div>

    <div class="menu">
        <?php 
        $userRole = (new \App\Service\SessionService())->getRole();
        
        if ($userRole === 'satpam'): 
        ?>
            <!-- Satpam Menu -->
            <a href="/lost_found" class="menu-item">
                <i class="bi bi-search"></i>
                <span>Lost & Found</span>
            </a>
            
            <a href="/lost_found/create" class="menu-item">
                <i class="bi bi-plus-circle"></i>
                <span>Info Barang Hilang</span>
            </a>
        <?php elseif ($userRole === 'admin'): ?>
            <!-- Admin Menu -->
            <a href="/dashboard" class="menu-item active">
                <i class="bi bi-columns-gap"></i>
                <span>Dashboard</span>
            </a>

            <a href="/admin/moderation" class="menu-item <?= $current_path === '/admin/moderation' ? 'active' : '' ?>">
                <i class="bi bi-shield-check"></i>
                <span>Moderasi Konten</span>
            </a>

            <!-- Manajemen Pengguna Dropdown -->
            <div class="menu-item-wrapper">
                <div class="menu-item dropdown-toggle" onclick="toggleDropdown(this)">
                    <div class="menu-link">
                        <i class="bi bi-people"></i>
                        <span>Manajemen Pengguna</span>
                    </div>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <a href="/admin/users" class="menu-item">
                        <span>Daftar Pengguna</span>
                    </a>
                    <a href="/admin/activity-log" class="menu-item">
                        <span>Log Aktivitas</span>
                    </a>
                </div>
            </div>

            <!-- Call Center Dropdown -->
            <div class="menu-item-wrapper">
                <div class="menu-item dropdown-toggle" onclick="toggleDropdown(this)">
                    <div class="menu-link">
                        <i class="bi bi-headset"></i>
                        <span>Call Center</span>
                    </div>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <a href="/admin/call-requests" class="menu-item">
                        <span>Call Request</span>
                    </a>
                    <!-- <a href="/admin/call-history" class="menu-item">
                        <span>Riwayat Call</span>
                    </a> -->
                </div>
            </div>

            <!-- Manajemen Laporan Dropdown -->
            <div class="menu-item-wrapper">
                <div class="menu-item dropdown-toggle" onclick="toggleDropdown(this)">
                    <div class="menu-link">
                        <i class="bi bi-file-earmark-text"></i>
                        <span>Manajemen Laporan</span>
                    </div>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <!-- <a href="/admin/reports" class="menu-item">
                        <span>Laporan Masuk</span>
                    </a>
                    <a href="/admin/reports/history" class="menu-item">
                        <span>Riwayat Penanganan</span>
                    </a> -->
                </div>
            </div>

            <!-- Konten Aplikasi Dropdown -->
            <div class="menu-item-wrapper">
                <div class="menu-item dropdown-toggle" onclick="toggleDropdown(this)">
                    <div class="menu-link">
                        <i class="bi bi-app-indicator"></i>
                        <span>Konten Aplikasi</span>
                    </div>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <a href="/kompetisi" class="menu-item">
                        <span>Kompetisi dan Team</span>
                    </a>
                    <a href="/lost_found" class="menu-item">
                        <span>Lost & Found</span>
                    </a>
                    <a href="/event" class="menu-item">
                        <span>Event</span>
                    </a>
                    <a href="/forum" class="menu-item">
                        <span>Forum</span>
                    </a>
                </div>
            </div>

            <a href="/help" class="menu-item">
                <i class="bi bi-question-circle"></i>
                <span>Bantuan</span>
            </a>

        <?php else: ?>
            <!-- Regular User Menu -->
            <a href="/dashboard" class="menu-item active">
                <i class="bi bi-columns-gap"></i>
                <span>Dashboard</span>
            </a>
            <a href="/kompetisi" class="menu-item">
                <i class="bi bi-trophy"></i>
                <span>Kompetisi dan Team</span>
            </a>
            <a href="/lost_found" class="menu-item">
                <i class="bi bi-search"></i>
                <span>Lost & Found</span>
            </a>
            <a href="/event" class="menu-item">
                <i class="bi bi-calendar4"></i>
                <span>Event</span>
            </a>
            <a href="/forum" class="menu-item">
                <i class="bi bi-chat-left"></i>
                <span>Forum</span>
            </a>

            <!-- Create Dropdown -->
            <div class="menu-item-wrapper">
                <div class="menu-item dropdown-toggle" onclick="toggleDropdown(this)">
                    <div class="menu-link">
                        <i class="bi bi-plus-circle"></i>
                        <span>Create</span>
                    </div>
                    <i class="bi bi-chevron-down dropdown-arrow"></i>
                </div>
                <div class="dropdown-content">
                    <a href="/forum/create" class="menu-item">
                        <span>Buat Forum</span>
                    </a>
                    
                    <a href="/team/create" class="menu-item">
                        <span>Rekrut Team</span>
                    </a>

                    <!-- Posting Dropdown -->
                    <div class="menu-item-wrapper">
                        <div class="menu-item dropdown-toggle" onclick="toggleDropdown(this)">
                            <div class="menu-link">
                                <span>Posting</span>
                            </div>
                            <i class="bi bi-chevron-down dropdown-arrow"></i>
                        </div>
                        <div class="dropdown-content">
                            <a href="/event/create" class="menu-item">
                                <span>Posting Event</span>
                            </a>
                            
                            <a href="/kompetisi/create" class="menu-item">
                                <span>Posting Lomba</span>
                            </a>

                            <a href="/lost_found/create" class="menu-item">
                                <span>Info Barang Hilang</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="/settings" class="menu-item">
                <i class="bi bi-gear"></i>
                <span>Settings</span>
            </a>
        <?php endif; ?>
    </div>

    <div class="user-section">
        <div class="user-avatar">
            <?= strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-info">
            <h3><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></h3>
            <p><?= htmlspecialchars($_SESSION['email'] ?? 'user@campus.edu') ?></p>
        </div>
        <a href="users/logout" class="logout-icon" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</div>
<script>
function toggleDropdown(element) {
    const wrapper = element.parentElement;
    const content = wrapper.querySelector('.dropdown-content');
    const isExpanded = wrapper.classList.contains('expanded');
    
    if (isExpanded) {
        wrapper.classList.remove('expanded');
        content.style.maxHeight = null;
    } else {
        wrapper.classList.add('expanded');
        content.style.maxHeight = content.scrollHeight + "px";
        
        // Update parent heights for nested dropdowns
        let parentContent = wrapper.closest('.dropdown-content');
        while (parentContent) {
            parentContent.style.maxHeight = (parentContent.scrollHeight + content.scrollHeight) + "px";
            parentContent = parentContent.parentElement.closest('.dropdown-content');
        }
    }
}

function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
    localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed'));
}

// Load sidebar state
window.addEventListener('DOMContentLoaded', () => {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    if (isCollapsed) {
        document.getElementById('sidebar').classList.add('collapsed');
    }
    
    // Set active menu item based on current path
    const currentPath = window.location.pathname;
    document.querySelectorAll('.menu-item').forEach(item => {
        // Only remove active from links, not toggles
        if (item.tagName === 'A') {
            item.classList.remove('active');
            if (item.getAttribute('href') === currentPath) {
                item.classList.add('active');
                
                // Expand parent dropdowns if active item is inside
                let parentContent = item.closest('.dropdown-content');
                while (parentContent) {
                    const wrapper = parentContent.parentElement;
                    wrapper.classList.add('expanded');
                    parentContent.style.maxHeight = parentContent.scrollHeight + "px"; // Initial height
                    parentContent = wrapper.closest('.dropdown-content');
                }
                
                // Re-calculate heights from top down to ensure full expansion
                // This is a bit complex, simpler to just let them be expanded
            }
        }
    });
});
</script>