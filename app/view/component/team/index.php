<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Search Management - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/team.css">

    <style>
    /* Force light theme */
    body {
        background: #f9fafb !important;
        color: #1f2937 !important;
    }

    .main-content {
        background: #f9fafb !important;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #666;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #ccc;
    }

    .loading {
        text-align: center;
        padding: 2rem;
        color: #666;
    }

    .stats-bar {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
    }

    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: white;
        border-radius: 0.375rem;
        border: 1px solid #e9ecef;
    }

    .stat-item i {
        color: #1976d2;
    }

    .stat-value {
        font-weight: bold;
        font-size: 1.2rem;
        color: #1976d2;
    }

    /* Style untuk badge status */
    .badge-aktif {
        background-color: #e8f5e8;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }

    .badge-urgent {
        background-color: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }

    .badge-need {
        background-color: #fff3e0;
        color: #ef6c00;
        border: 1px solid #ffe0b2;
    }

    /* Style untuk filter tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
    }

    .filter-tab {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        background: white;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-tab.active {
        background: #1976d2;
        color: white;
        border-color: #1976d2;
    }

    .filter-tab:hover:not(.active) {
        background: #f5f5f5;
    }

    /* Style untuk tags */
    .team-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin: 1rem 0;
    }

    .tag {
        background: #e3f2fd;
        color: #1976d2;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.875rem;
        border: 1px solid #bbdefb;
    }

    /* Style untuk team card */
    .team-card {
        background: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        transition: all 0.2s;
    }

    .team-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .team-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .team-title-section {
        flex: 1;
    }

    .team-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 0.25rem;
    }

    .team-creator {
        color: #6c757d;
        font-size: 0.875rem;
    }

    .header-badges {
        display: flex;
        gap: 0.5rem;
    }

    .team-description {
        color: #495057;
        line-height: 1.5;
        margin-bottom: 1rem;
    }

    .team-meta {
        display: flex;
        gap: 1.5rem;
        margin: 1rem 0;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .team-contact {
        margin: 1rem 0;
        padding: 0.75rem;
        background: #f8f9fa;
        border-radius: 0.375rem;
        font-size: 0.875rem;
    }

    .team-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        padding: 0.5rem;
        border: 1px solid #ddd;
        background: white;
        border-radius: 0.375rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #f8f9fa;
    }

    .action-btn.view {
        color: #1976d2;
        border-color: #1976d2;
    }

    .action-btn.edit {
        color: #ffa000;
        border-color: #ffa000;
    }

    .action-btn.delete {
        color: #d32f2f;
        border-color: #d32f2f;
    }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>
    <div class="main-content">
        <div class="header">
            <div>
                <h1>Team Search Management</h1>
                <p>Kelola pencarian anggota tim untuk lomba dan kompetisi</p>
            </div>
            <button class="btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Tambah Pencarian Tim
            </button>
        </div>

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-item">
                <i class="fas fa-users"></i>
                <div>
                    <div class="stat-value"><?= $total_teams ?? 0 ?></div>
                    <div>Total Tim</div>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-check-circle"></i>
                <div>
                    <div class="stat-value"><?= $active_teams ?? 0 ?></div>
                    <div>Tim Aktif</div>
                </div>
            </div>
            <div class="stat-item">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <div class="stat-value"><?= $urgent_teams ?? 0 ?></div>
                    <div>Butuh Segera</div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari tim, creator, atau jurusan..."
                    onkeyup="filterTeams()">
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterByStatus('all')">Semua</button>
                <button class="filter-tab" onclick="filterByStatus('active')">Aktif</button>
                <button class="filter-tab" onclick="filterByStatus('urgent')">Urgent</button>
                <button class="filter-tab" onclick="filterByStatus('completed')">Selesai</button>
            </div>
        </div>

        <!-- Team List -->
        <div class="team-list" id="teamList">
            <?php if (empty($teams)): ?>
            <div class="empty-state">
                <i class="fas fa-users-slash"></i>
                <h3>Tidak ada tim yang aktif</h3>
                <p>Belum ada tim yang sedang mencari anggota. Mulai dengan membuat pencarian tim pertama Anda.</p>
            </div>
            <?php else: ?>
            <?php foreach ($teams as $team): ?>
            <div class="team-card" data-priority="<?= htmlspecialchars($team['priority_status']) ?>">
                <div class="team-header">
                    <div class="team-title-section">
                        <div class="team-title"><?= htmlspecialchars($team['nama_team']) ?></div>
                        <div class="team-creator">
                            <?= htmlspecialchars($team['creator_name']) ?> •
                            <?= htmlspecialchars($team['creator_jurusan']) ?> •
                            Semester <?= htmlspecialchars($team['creator_semester']) ?>
                        </div>
                    </div>
                    <div class="header-badges">
                        <span class="badge badge-<?= $team['priority_status'] === 'urgent' ? 'urgent' : 'aktif' ?>">
                            <?= $team['priority_status'] === 'urgent' ? 'Urgent' : 'Aktif' ?>
                        </span>
                        <span class="badge badge-need">
                            Butuh <?= $team['members_needed'] ?> orang
                        </span>
                    </div>
                </div>
                <div class="team-description">
                    <?= htmlspecialchars($team['deskripsi_anggota'] ?? '') ?>
                </div>
                <div class="team-tags">
                    <?php if (!empty($team['role_required'])): ?>
                        <?php 
                        $roles = json_decode($team['role_required'], true);
                        if (is_array($roles)):
                            foreach ($roles as $role): 
                        ?>
                            <span class="tag"><?= htmlspecialchars($role['nama']) ?> (<?= $role['jumlah'] ?>)</span>
                        <?php 
                            endforeach;
                        elseif (is_string($team['role_required'])):
                             // Fallback for old data or if not JSON
                             $skills = explode(',', $team['role_required']);
                             foreach ($skills as $skill):
                        ?>
                            <span class="tag"><?= htmlspecialchars(trim($skill)) ?></span>
                        <?php 
                             endforeach;
                        endif;
                        ?>
                    <?php endif; ?>
                </div>
                <div class="team-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span><?= htmlspecialchars($team['competition_name'] ?? 'Tidak ada lomba') ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>Deadline: <?= date('Y-m-d', strtotime($team['tenggat_join'])) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span><?= $team['total_applicants'] ?> pelamar</span>
                    </div>
                </div>
                <div class="team-contact">
                    <strong>Kontak:</strong> <?= htmlspecialchars($team['creator_email']) ?>
                </div>
                <?php if (isset($userRole) && $userRole === 'admin'): ?>
                <div class="team-actions">
                    <button class="action-btn view" onclick="viewTeam(<?= $team['id'] ?>)">
                        <i class="far fa-eye"></i>
                    </button>
                    <button class="action-btn edit" onclick="editTeam(<?= $team['id'] ?>)">
                        <i class="far fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteTeam(<?= $team['id'] ?>)">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- JavaScript untuk filtering dan modal -->
    <script>
    function filterTeams() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const teamCards = document.querySelectorAll('.team-card');

        teamCards.forEach(card => {
            const title = card.querySelector('.team-title').textContent.toLowerCase();
            const creator = card.querySelector('.team-creator').textContent.toLowerCase();
            const description = card.querySelector('.team-description').textContent.toLowerCase();

            if (title.includes(searchTerm) || creator.includes(searchTerm) || description.includes(
                    searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterByStatus(status) {
        const teamCards = document.querySelectorAll('.team-card');
        const tabs = document.querySelectorAll('.filter-tab');

        // Update active tab
        tabs.forEach(tab => tab.classList.remove('active'));
        event.currentTarget.classList.add('active');

        teamCards.forEach(card => {
            const priority = card.getAttribute('data-priority');

            if (status === 'all') {
                card.style.display = 'block';
            } else if (status === 'active') {
                card.style.display = priority === 'active' ? 'block' : 'none';
            } else if (status === 'urgent') {
                card.style.display = priority === 'urgent' ? 'block' : 'none';
            } else if (status === 'completed') {
                // Logic untuk tim yang sudah selesai
                card.style.display = 'none'; // Sesuaikan dengan data yang ada
            }
        });
    }

    function viewTeam(id) {
        // Implement view team functionality
        window.location.href = `/teams/${id}`;
    }

    function editTeam(id) {
        // Implement edit team functionality
        window.location.href = `/teams/edit/${id}`;
    }

    function deleteTeam(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pencarian tim ini?')) {
            // Implement delete team functionality
            fetch(`/api/teams/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Tim berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Gagal menghapus tim: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus tim');
                });
        }
    }

    function openCreateModal() {
        window.location.href = '/team/create';
    }

    // Real-time search dengan debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTeams, 300);
    });
    </script>
</body>

</html>