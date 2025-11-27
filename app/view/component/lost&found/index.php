<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Campus Nexus</title>
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

        .filter-dropdowns {
            display: flex;
            gap: 12px;
        }

        .dropdown {
            position: relative;
        }

        .dropdown select {
            padding: 10px 36px 10px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            color: #4b5563;
            background: white;
            cursor: pointer;
            appearance: none;
            min-width: 150px;
        }

        .dropdown select:focus {
            outline: none;
            border-color: #4F87FF;
        }

        .dropdown::after {
            content: '\f078';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #6b7280;
            font-size: 12px;
        }

        /* Item Cards */
        .item-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .item-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            transition: all 0.2s;
        }

        .item-card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 16px;
        }

        .item-title-section {
            flex: 1;
        }

        .item-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .item-description {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
        }

        .item-badges {
            display: flex;
            gap: 8px;
            align-items: flex-start;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-hilang {
            background: #FEE2E2;
            color: #DC2626;
        }

        .badge-ditemukan {
            background: #DBEAFE;
            color: #2563EB;
        }

        .badge-aktif {
            background: #f3f4f6;
            color: #6b7280;
        }

        .item-meta {
            display: grid;
            grid-template-columns: auto auto;
            gap: 16px;
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
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
            width: 16px;
        }

        .item-contact {
            margin-top: 16px;
        }

        .contact-title {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #6b7280;
        }

        .contact-item i {
            color: #9ca3af;
            width: 16px;
        }

        .item-status {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #f3f4f6;
        }

        .status-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        .status-icon.pending {
            background: #FEF3C7;
            color: #F59E0B;
        }

        .status-text {
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <div>
                <h1>Lost & Found</h1>
                <p>Pusat informasi barang hilang dan ditemukan di kampus</p>
            </div>
            <button class="btn-primary">
                <i class="fas fa-plus"></i>
                Tambah Item
            </button>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Cari barang, lokasi, atau deskripsi...">
            </div>
            <div class="filter-dropdowns">
                <div class="dropdown">
                    <select>
                        <option>Semua</option>
                        <option>Hilang</option>
                        <option>Ditemukan</option>
                    </select>
                </div>
                <div class="dropdown">
                    <select>
                        <option>Semua</option>
                        <option>Aktif</option>
                        <option>Selesai</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Item List -->
        <div class="item-list">
            <!-- Item Card 1 -->
            <div class="item-card">
                <div class="item-header">
                    <div class="item-title-section">
                        <div class="item-title">Dompet Kulit Coklat</div>
                        <div class="item-description">
                            Dompet kulit coklat merk Fossil, berisi KTM dan kartu ATM. Hilang di sekitar perpustakaan
                            pusat.
                        </div>
                    </div>
                    <div class="item-badges">
                        <span class="badge badge-hilang">Hilang</span>
                        <span class="badge badge-aktif">Aktif</span>
                    </div>
                </div>

                <div class="item-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Perpustakaan Pusat</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>2 jam yang lalu</span>
                    </div>
                </div>

                <div class="item-contact">
                    <div class="contact-title">Informasi Kontak:</div>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="far fa-envelope"></i>
                            <span>ahmad.ridwan@student.univ.ac.id</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>081234567890</span>
                        </div>
                    </div>
                </div>

                <div class="item-status">
                    <div class="status-icon pending">
                        <i class="far fa-clock"></i>
                    </div>
                    <span class="status-text">Tandai Selesai</span>
                </div>
            </div>

            <!-- Item Card 2 -->
            <div class="item-card">
                <div class="item-header">
                    <div class="item-title-section">
                        <div class="item-title">Power Bank Xiaomi Hitam</div>
                        <div class="item-description">
                            Ditemukan power bank Xiaomi warna hitam 10000mAh di meja kantin fakultas teknik.
                        </div>
                    </div>
                    <div class="item-badges">
                        <span class="badge badge-ditemukan">Ditemukan</span>
                        <span class="badge badge-aktif">Aktif</span>
                    </div>
                </div>

                <div class="item-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Kantin Fakultas Teknik</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>5 jam yang lalu</span>
                    </div>
                </div>

                <div class="item-contact">
                    <div class="contact-title">Informasi Kontak:</div>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="far fa-envelope"></i>
                            <span>sarah.putri@student.univ.ac.id</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>082345678901</span>
                        </div>
                    </div>
                </div>

                <div class="item-status">
                    <div class="status-icon pending">
                        <i class="far fa-clock"></i>
                    </div>
                    <span class="status-text">Tandai Selesai</span>
                </div>
            </div>

            <!-- Item Card 3 -->
            <div class="item-card">
                <div class="item-header">
                    <div class="item-title-section">
                        <div class="item-title">Buku Kalkulus II Edisi 8</div>
                        <div class="item-description">
                            Hilang buku Kalkulus II edisi 8 warna biru, ada nama dan catatan di dalamnya. Terakhir
                            dibawa ke ruang kelas A.2.3.
                        </div>
                    </div>
                    <div class="item-badges">
                        <span class="badge badge-hilang">Hilang</span>
                        <span class="badge badge-aktif">Aktif</span>
                    </div>
                </div>

                <div class="item-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Ruang Kelas A.2.3</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>1 hari yang lalu</span>
                    </div>
                </div>

                <div class="item-contact">
                    <div class="contact-title">Informasi Kontak:</div>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="far fa-envelope"></i>
                            <span>budi.santoso@student.univ.ac.id</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>083456789012</span>
                        </div>
                    </div>
                </div>

                <div class="item-status">
                    <div class="status-icon pending">
                        <i class="far fa-clock"></i>
                    </div>
                    <span class="status-text">Tandai Selesai</span>
                </div>
            </div>

            <!-- Item Card 4 -->
            <div class="item-card">
                <div class="item-header">
                    <div class="item-title-section">
                        <div class="item-title">Kunci Motor Honda Vario</div>
                        <div class="item-description">
                            Ditemukan kunci motor Honda Vario dengan gantungan boneka beruang kecil di parkiran gedung
                            B.
                        </div>
                    </div>
                    <div class="item-badges">
                        <span class="badge badge-ditemukan">Ditemukan</span>
                        <span class="badge badge-aktif">Aktif</span>
                    </div>
                </div>

                <div class="item-meta">
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Parkiran Gedung B</span>
                    </div>
                    <div class="meta-item">
                        <i class="far fa-clock"></i>
                        <span>3 jam yang lalu</span>
                    </div>
                </div>

                <div class="item-contact">
                    <div class="contact-title">Informasi Kontak:</div>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="far fa-envelope"></i>
                            <span>lisa.amanda@student.univ.ac.id</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>084567890123</span>
                        </div>
                    </div>
                </div>

                <div class="item-status">
                    <div class="status-icon pending">
                        <i class="far fa-clock"></i>
                    </div>
                    <span class="status-text">Tandai Selesai</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>