<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
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

        /* Event Cards */
        .event-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .event-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            transition: all 0.2s;
        }

        .event-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .event-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }

        .event-title-section {
            flex: 1;
        }

        .event-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 6px;
        }

        .event-organizer {
            font-size: 14px;
            color: #6b7280;
        }

        .event-description {
            font-size: 14px;
            color: #4b5563;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .event-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
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

        .badge {
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-upcoming {
            background: #4F87FF;
            color: white;
        }

        .badge-registration {
            background: #f3f4f6;
            color: #6b7280;
        }

        .event-actions {
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

        .action-btn.edit:hover {
            color: #4F87FF;
            border-color: #4F87FF;
        }

        .action-btn.delete:hover {
            color: #EF4444;
            border-color: #EF4444;
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div>
                <h1>Event Management</h1>
                <p>Kelola semua event kampus dalam satu tempat</p>
            </div>
            <button class="btn-primary">
                <i class="fas fa-plus"></i>
                Tambah Event Baru
            </button>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari event atau organizer...">
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active">Semua</button>
                <button class="filter-tab">Upcoming</button>
                <button class="filter-tab">Registrasi</button>
                <button class="filter-tab">Selesai</button>
            </div>
        </div>

        <!-- Event List -->
        <div class="event-list">
            <div class="event-card">
                <div class="event-header">
                    <div class="event-title-section">
                        <div class="event-title">Workshop React JS untuk Pemula</div>
                        <div class="event-organizer">Himpunan Informatika</div>
                    </div>
                    <span class="badge badge-upcoming">Upcoming</span>
                </div>
                <div class="event-description">
                    Belajar dasar-dasar React JS untuk pengembangan web modern
                </div>
                <div class="event-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>2024-01-15 • 14:00 - 17:00</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Lab Komputer A.3.1</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>45/50 peserta</span>
                    </div>
                </div>
                <div class="event-actions">
                    <button class="action-btn">
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

            <div class="event-card">
                <div class="event-header">
                    <div class="event-title-section">
                        <div class="event-title">Seminar Digital Marketing</div>
                        <div class="event-organizer">BEM Fakultas Ekonomi</div>
                    </div>
                    <span class="badge badge-upcoming">Upcoming</span>
                </div>
                <div class="event-description">
                    Strategi pemasaran digital untuk era modern
                </div>
                <div class="event-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>2024-01-18 • 09:00 - 12:00</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Auditorium Utama</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>67/100 peserta</span>
                    </div>
                </div>
                <div class="event-actions">
                    <button class="action-btn">
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

            <div class="event-card">
                <div class="event-header">
                    <div class="event-title-section">
                        <div class="event-title">Lomba Programming Competition</div>
                        <div class="event-organizer">UKM Programming Club</div>
                    </div>
                    <span class="badge badge-registration">Registrasi</span>
                </div>
                <div class="event-description">
                    Kompetisi programming untuk mahasiswa se-universitas
                </div>
                <div class="event-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>2024-01-20 • 08:00</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Lab Programming</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>89 peserta</span>
                    </div>
                </div>
                <div class="event-actions">
                    <button class="action-btn">
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