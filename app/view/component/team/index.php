<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Search Management - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        background: #f9fafb;
        display: flex;
    }

    /* Sidebar */
    .sidebar {
        width: 280px;
        height: 100vh;
        background: #ffffff;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
    }

    .logo-section {
        padding: 24px 20px;
        border-bottom: 1px solid #e5e7eb;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .logo-icon {
        width: 40px;
        height: 40px;
        background: #4F87FF;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
    }

    .logo-text h1 {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
    }

    .logo-text p {
        font-size: 12px;
        color: #6b7280;
    }

    .menu {
        flex: 1;
        padding: 20px 0;
        overflow-y: auto;
    }

    .menu-item {
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #4b5563;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 14px;
        font-weight: 500;
    }

    .menu-item:hover {
        background: #f3f4f6;
    }

    .menu-item.active {
        background: #EBF2FF;
        color: #4F87FF;
        border-right: 3px solid #4F87FF;
    }

    .menu-item i {
        width: 20px;
        text-align: center;
        font-size: 18px;
    }

    .user-section {
        padding: 16px 20px;
        border-top: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background: #4F87FF;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }

    .user-info {
        flex: 1;
    }

    .user-info h3 {
        font-size: 14px;
        font-weight: 600;
        color: #1f2937;
    }

    .user-info p {
        font-size: 12px;
        color: #6b7280;
    }

    .logout-icon {
        color: #6b7280;
        cursor: pointer;
        font-size: 18px;
    }

    /* Main Content */
    .main-content {
        margin-left: 280px;
        flex: 1;
        padding: 32px;
        min-height: 100vh;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 32px;
    }

    .header h1 {
        font-size: 32px;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .header p {
        font-size: 14px;
        color: #6b7280;
    }

    .btn-primary {
        background: #4F87FF;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-primary:hover {
        background: #3B6FDD;
    }

    /* Filters */
    .filters {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        margin-bottom: 24px;
        display: flex;
        gap: 16px;
        align-items: center;
    }

    .search-box {
        flex: 1;
        position: relative;
    }

    .search-box i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .search-box input {
        width: 100%;
        padding: 12px 16px 12px 44px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
    }

    .search-box input:focus {
        outline: none;
        border-color: #4F87FF;
    }

    .filter-tabs {
        display: flex;
        gap: 8px;
    }

    .filter-tab {
        padding: 10px 20px;
        border: none;
        background: transparent;
        color: #6b7280;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .filter-tab:hover {
        background: #f3f4f6;
    }

    .filter-tab.active {
        background: #4F87FF;
        color: white;
    }

    /* Team Cards */
    .team-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .team-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        transition: all 0.2s;
    }

    .team-card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .team-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }

    .team-title-section {
        flex: 1;
    }

    .team-title {
        font-size: 18px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 6px;
    }

    .team-creator {
        font-size: 14px;
        color: #6b7280;
    }

    .team-description {
        font-size: 14px;
        color: #4b5563;
        margin-bottom: 16px;
        line-height: 1.5;
    }

    .team-tags {
        display: flex;
        gap: 8px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .tag {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        background: #f3f4f6;
        color: #4b5563;
        font-weight: 500;
    }

    .team-meta {
        display: flex;
        gap: 24px;
        padding-top: 16px;
        border-top: 1px solid #f3f4f6;
        align-items: center;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6b7280;
    }

    .meta-item i {
        color: #9ca3af;
    }

    .team-contact {
        margin-top: 12px;
        font-size: 14px;
        color: #6b7280;
    }

    .team-contact strong {
        color: #4b5563;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .badge-aktif {
        background: #4F87FF;
        color: white;
    }

    .badge-need {
        background: #f3f4f6;
        color: #6b7280;
        margin-left: auto;
    }

    .team-actions {
        display: flex;
        gap: 12px;
        margin-top: 16px;
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border: 1px solid #e5e7eb;
        background: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.2s;
    }

    .action-btn:hover {
        background: #f3f4f6;
    }

    .action-btn.view:hover {
        color: #4F87FF;
        border-color: #4F87FF;
    }

    .action-btn.edit:hover {
        color: #4F87FF;
        border-color: #4F87FF;
    }

    .action-btn.delete:hover {
        color: #EF4444;
        border-color: #EF4444;
    }

    .header-badges {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div>
                <h1>Team Search Management</h1>
                <p>Kelola pencarian anggota tim untuk lomba dan kompetisi</p>
            </div>
            <button class="btn-primary">
                <i class="fas fa-plus"></i>
                Tambah Pencarian Tim
            </button>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari tim, creator, atau jurusan...">
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active">Semua</button>
                <button class="filter-tab">Aktif</button>
                <button class="filter-tab">Urgent</button>
                <button class="filter-tab">Selesai</button>
            </div>
        </div>

        <!-- Team List -->
        <div class="team-list">
            <!-- Team Card 1 -->
            <div class="team-card">
                <div class="team-header">
                    <div class="team-title-section">
                        <div class="team-title">Tim Lomba Mobile App Development</div>
                        <div class="team-creator">Ahmad Ridwan • Teknik Informatika • Semester 6</div>
                    </div>
                    <div class="header-badges">
                        <span class="badge badge-aktif">Aktif</span>
                        <span class="badge badge-need">Butuh 2 orang</span>
                    </div>
                </div>
                <div class="team-description">
                    Mencari anggota tim untuk lomba mobile app development tingkat nasional. Butuh developer Flutter dan
                    UI/UX designer.
                </div>
                <div class="team-tags">
                    <span class="tag">Flutter</span>
                    <span class="tag">UI/UX Design</span>
                    <span class="tag">Backend Development</span>
                </div>
                <div class="team-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>National Mobile App Competition 2024</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>Deadline: 2024-01-25</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>8 pelamar</span>
                    </div>
                </div>
                <div class="team-contact">
                    <strong>Kontak:</strong> ahmad.ridwan@student.univ.ac.id
                </div>
                <div class="team-actions">
                    <button class="action-btn view">
                        <i class="far fa-eye"></i>
                    </button>
                    <button class="action-btn edit">
                        <i class="far fa-edit"></i>
                    </button>
                    <button class="action-btn delete">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Team Card 2 -->
            <div class="team-card">
                <div class="team-header">
                    <div class="team-title-section">
                        <div class="team-title">Tim Business Plan Competition</div>
                        <div class="team-creator">Sarah Putri • Manajemen • Semester 4</div>
                    </div>
                    <div class="header-badges">
                        <span class="badge badge-aktif">Aktif</span>
                        <span class="badge badge-need">Butuh 1 orang</span>
                    </div>
                </div>
                <div class="team-description">
                    Tim bisnis plan sudah ada 2 orang, butuh 1 orang lagi yang ahli di financial modeling untuk
                    melengkapi tim.
                </div>
                <div class="team-tags">
                    <span class="tag">Business Analysis</span>
                    <span class="tag">Financial Modeling</span>
                    <span class="tag">Presentation</span>
                </div>
                <div class="team-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>Young Entrepreneur Challenge 2024</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>Deadline: 2024-01-30</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>5 pelamar</span>
                    </div>
                </div>
                <div class="team-contact">
                    <strong>Kontak:</strong> sarah.putri@student.univ.ac.id
                </div>
                <div class="team-actions">
                    <button class="action-btn view">
                        <i class="far fa-eye"></i>
                    </button>
                    <button class="action-btn edit">
                        <i class="far fa-edit"></i>
                    </button>
                    <button class="action-btn delete">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Team Card 3 -->
            <div class="team-card">
                <div class="team-header">
                    <div class="team-title-section">
                        <div class="team-title">Tim Hackathon Data Science</div>
                        <div class="team-creator">Budi Santoso • Teknik Informatika • Semester 5</div>
                    </div>
                    <div class="header-badges">
                        <span class="badge badge-aktif">Aktif</span>
                        <span class="badge badge-need">Butuh 3 orang</span>
                    </div>
                </div>
                <div class="team-description">
                    Mencari anggota tim untuk hackathon data science. Butuh yang bisa machine learning, data
                    visualization, dan web development.
                </div>
                <div class="team-tags">
                    <span class="tag">Machine Learning</span>
                    <span class="tag">Data Visualization</span>
                    <span class="tag">Python</span>
                    <span class="tag">Web Development</span>
                </div>
                <div class="team-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>National Data Science Hackathon 2024</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>Deadline: 2024-02-05</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>12 pelamar</span>
                    </div>
                </div>
                <div class="team-contact">
                    <strong>Kontak:</strong> budi.santoso@student.univ.ac.id
                </div>
                <div class="team-actions">
                    <button class="action-btn view">
                        <i class="far fa-eye"></i>
                    </button>
                    <button class="action-btn edit">
                        <i class="far fa-edit"></i>
                    </button>
                    <button class="action-btn delete">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>