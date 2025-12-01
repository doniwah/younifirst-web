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
        <a href="/event" class="menu-item">
            <i class="bi bi-calendar4"></i>
            <span>Event Management</span>
        </a>
        <a href="/team" class="menu-item">
            <i class="bi bi-people"></i>
            <span>Team Search</span>
        </a>
        <a href="/forum" class="menu-item">
            <i class="bi bi-chat-left"></i>
            <span>Academic Forum</span>
        </a>
        <a href="/lost_found" class="menu-item">
            <i class="bi bi-search"></i>
            <span>Lost & Found</span>
        </a>
        <a href="/kompetisi" class="menu-item">
            <i class="bi bi-trophy"></i>
            <span>Kompetisi</span>
        </a>
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
        item.classList.remove('active');
        if (item.getAttribute('href') === currentPath) {
            item.classList.add('active');
        }
    });
});
</script>