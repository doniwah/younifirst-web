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
                
                <a href="/team/recruit" class="menu-item">
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

                        <!-- Barang Hilang Dropdown -->
                        <div class="menu-item-wrapper">
                            <div class="menu-item dropdown-toggle" onclick="toggleDropdown(this)">
                                <div class="menu-link">
                                    <span>Info Barang Hilang</span>
                                </div>
                                <i class="bi bi-chevron-down dropdown-arrow"></i>
                            </div>
                            <div class="dropdown-content">
                                <a href="/lost_found/found" class="menu-item">
                                    <span>Menemukan</span>
                                </a>
                                <a href="/lost_found/lost" class="menu-item">
                                    <span>Kehilangan</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="/settings" class="menu-item">
            <i class="bi bi-gear"></i>
            <span>Settings</span>
        </a>
    </div>

    <div class="user-section">
        <div class="user-avatar">
            <?= strtoupper(substr($_SESSION['nama'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-info">
            <h3><?= htmlspecialchars($_SESSION['nama'] ?? 'User') ?></h3>
            <p><?= htmlspecialchars($_SESSION['email'] ?? 'user@campus.edu') ?></p>
        </div>
        <a href="/logout" class="logout-icon" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</div>

<script>
// Sidebar Toggle
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