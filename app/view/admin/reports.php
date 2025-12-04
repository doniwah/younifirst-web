<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Masuk</title>
    <link rel="stylesheet" href="/css/variable.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }

        .main-content {
            margin-left: 280px;
            padding: 30px;
            transition: margin-left 0.3s;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 80px;
        }

        .reports-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            gap: 20px;
            flex-wrap: wrap;
        }

        .search-filter-group {
            display: flex;
            gap: 12px;
            flex: 1;
            max-width: 500px;
        }

        .search-box {
            position: relative;
            flex: 1;
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .search-box input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .filter-btn {
            padding: 12px 20px;
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-btn:hover {
            border-color: #4f46e5;
            color: #4f46e5;
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .stat-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.pending {
            background: #fff3cd;
            color: #f59e0b;
        }

        .stat-icon.processing {
            background: #cfe2ff;
            color: #0d6efd;
        }

        .stat-icon.reviewed {
            background: #d1e7dd;
            color: #198754;
        }

        .stat-icon.rejected {
            background: #ffe5e5;
            color: #dc3545;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #212529;
        }

        /* Filter Tabs */
        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            padding: 8px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 10px 20px;
            background: transparent;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: #6c757d;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-tab:hover {
            background: #f8f9fa;
            color: #212529;
        }

        .filter-tab.active {
            background: #4f46e5;
            color: white;
        }

        .filter-tab .badge {
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            background: rgba(0,0,0,0.1);
        }

        /* Report Cards */
        .reports-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: all 0.3s;
            border-left: 4px solid #4f46e5;
        }

        .report-card[data-status="pending"] {
            border-left-color: #f59e0b;
        }

        .report-card[data-status="diproses"] {
            border-left-color: #0d6efd;
        }

        .report-card[data-status="ditinjau"] {
            border-left-color: #198754;
        }

        .report-card[data-status="ditolak"] {
            border-left-color: #dc3545;
        }

        .report-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            gap: 16px;
        }

        .report-title-section {
            flex: 1;
        }

        .report-title {
            font-size: 18px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
        }

        .report-id {
            font-size: 13px;
            color: #6c757d;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-badge.diproses {
            background: #cfe2ff;
            color: #084298;
        }

        .status-badge.ditinjau {
            background: #d1e7dd;
            color: #0f5132;
        }

        .status-badge.ditolak {
            background: #f8d7da;
            color: #842029;
        }

        .report-description {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .report-meta {
            display: flex;
            gap: 24px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #6c757d;
            flex-wrap: wrap;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .meta-item i {
            color: #999;
        }

        .report-reporter {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .reporter-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            flex-shrink: 0;
        }

        .reporter-info {
            flex: 1;
        }

        .reporter-name {
            font-size: 14px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 2px;
        }

        .reporter-email {
            font-size: 12px;
            color: #6c757d;
        }

        .report-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-outline {
            background: white;
            border: 1px solid #e0e0e0;
            color: #495057;
        }

        .btn-outline:hover {
            border-color: #4f46e5;
            color: #4f46e5;
        }

        .btn-primary {
            background: #4f46e5;
            color: white;
        }

        .btn-primary:hover {
            background: #4338ca;
        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            animation: fadeIn 0.3s;
        }

        .modal.active {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            animation: slideUp 0.3s;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .close-modal {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
            font-family: inherit;
        }

        .form-group textarea {
            min-height: 100px;
        }

        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 16px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .stats-row {
                grid-template-columns: 1fr;
            }

            .report-header {
                flex-direction: column;
            }

            .report-actions {
                width: 100%;
                justify-content: stretch;
            }

            .btn {
                flex: 1;
            }
        }
    </style>
</head>
<body>
    
    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="reports-container">
            
            <!-- Header & Search -->
            <div class="page-header">
                <div class="search-filter-group">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari laporan...">
                    </div>
                    <button class="filter-btn">
                        <i class="bi bi-filter-left"></i>
                        Filter
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon pending">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-label">Pending</div>
                            <div class="stat-value" id="statPending">0</div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon processing">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-label">Diproses</div>
                            <div class="stat-value" id="statProcessing">0</div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon reviewed">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-label">Ditinjau</div>
                            <div class="stat-value" id="statReviewed">0</div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon rejected">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-label">Ditolak</div>
                            <div class="stat-value" id="statRejected">0</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    <i class="bi bi-list-ul"></i>
                    Semua
                    <span class="badge" id="badgeAll">0</span>
                </button>
                <button class="filter-tab" data-filter="pending">
                    <i class="bi bi-clock"></i>
                    Pending
                    <span class="badge" id="badgePending">0</span>
                </button>
                <button class="filter-tab" data-filter="diproses">
                    <i class="bi bi-hourglass-split"></i>
                    Diproses
                    <span class="badge" id="badgeProcessing">0</span>
                </button>
                <button class="filter-tab" data-filter="ditinjau">
                    <i class="bi bi-check-circle"></i>
                    Ditinjau
                    <span class="badge" id="badgeReviewed">0</span>
                </button>
                <button class="filter-tab" data-filter="ditolak">
                    <i class="bi bi-x-circle"></i>
                    Ditolak
                    <span class="badge" id="badgeRejected">0</span>
                </button>
            </div>

            <!-- Reports List -->
            <div class="reports-list" id="reportsList">
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>Memuat laporan...</h3>
                    <p>Harap tunggu sebentar</p>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Update Status -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Ubah Status Laporan</h2>
                <button class="close-modal" onclick="closeModal('statusModal')">&times;</button>
            </div>
            <form id="statusForm" onsubmit="submitStatusChange(event)">
                <input type="hidden" name="report_id" id="statusReportId">
                
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" required>
                        <option value="">Pilih Status</option>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="ditinjau">Ditinjau</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Catatan</label>
                    <textarea name="catatan" placeholder="Tambahkan catatan..."></textarea>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal('statusModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Detail Laporan</h2>
                <button class="close-modal" onclick="closeModal('detailModal')">&times;</button>
            </div>
            <div id="detailContent" class="modal-body">
                <!-- Detail akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    <script>
        // Initialize Supabase
        const SUPABASE_URL = 'YOUR_SUPABASE_URL';
        const SUPABASE_KEY = 'YOUR_SUPABASE_KEY';
        const supabase = window.supabase.createClient(SUPABASE_URL, SUPABASE_KEY);

        let allReports = [];

        // Load reports on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadReports();
        });

        async function loadReports() {
            try {
                const { data, error } = await supabase
                    .from('reports')
                    .select(`
                        id,
                        judul,
                        deskripsi,
                        kategori,
                        status,
                        catatan,
                        created_at,
                        user_id,
                        users(nama, email)
                    `)
                    .order('created_at', { ascending: false });

                if (error) throw error;

                allReports = data || [];
                renderReports(allReports);
                updateStats();
            } catch (error) {
                console.error('Error loading reports:', error);
                showEmpty('Terjadi kesalahan memuat data');
            }
        }

        function renderReports(reports) {
            const reportsList = document.getElementById('reportsList');
            
            if (reports.length === 0) {
                showEmpty('Tidak ada laporan');
                return;
            }

            reportsList.innerHTML = reports.map(report => {
                const userData = report.users || {};
                const reportDate = new Date(report.created_at).toLocaleDateString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                const reportTime = new Date(report.created_at).toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return `
                    <div class="report-card" data-status="${report.status}">
                        <div class="report-header">
                            <div class="report-title-section">
                                <div class="report-title">${escapeHtml(report.judul)}</div>
                                <div class="report-id">#${report.id}</div>
                            </div>
                            <span class="status-badge ${report.status}">
                                ${capitalizeFirst(report.status)}
                            </span>
                        </div>

                        <div class="report-description">
                            ${escapeHtml(report.deskripsi.substring(0, 150))}${report.deskripsi.length > 150 ? '...' : ''}
                        </div>

                        <div class="report-meta">
                            <div class="meta-item">
                                <i class="bi bi-tag"></i>
                                <span>${capitalizeFirst(report.kategori || '-')}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-calendar"></i>
                                <span>${reportDate}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-clock"></i>
                                <span>${reportTime}</span>
                            </div>
                        </div>

                        <div class="report-reporter">
                            <div class="reporter-avatar">
                                ${userData.nama ? userData.nama.charAt(0).toUpperCase() : 'U'}
                            </div>
                            <div class="reporter-info">
                                <div class="reporter-name">${escapeHtml(userData.nama || 'Unknown')}</div>
                                <div class="reporter-email">${escapeHtml(userData.email || '-')}</div>
                            </div>
                        </div>

                        ${report.catatan ? `<div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 16px; border-left: 3px solid #4f46e5;"><strong>Catatan Admin:</strong> ${escapeHtml(report.catatan)}</div>` : ''}

                        <div class="report-actions">
                            <button class="btn btn-outline btn-sm" onclick="showDetailModal(${report.id})">
                                <i class="bi bi-eye"></i>
                                Detail
                            </button>
                            <button class="btn btn-primary btn-sm" onclick="openStatusModal(${report.id})">
                                <i class="bi bi-pencil"></i>
                                Ubah Status
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function showEmpty(message) {
            document.getElementById('reportsList').innerHTML = `
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>${message}</h3>
                    <p>Tidak ada laporan yang sesuai</p>
                </div>
            `;
        }

        function updateStats() {
            const pending = allReports.filter(r => r.status === 'pending').length;
            const diproses = allReports.filter(r => r.status === 'diproses').length;
            const ditinjau = allReports.filter(r => r.status === 'ditinjau').length;
            const ditolak = allReports.filter(r => r.status === 'ditolak').length;
            const total = allReports.length;

            document.getElementById('statPending').textContent = pending;
            document.getElementById('statProcessing').textContent = diproses;
            document.getElementById('statReviewed').textContent = ditinjau;
            document.getElementById('statRejected').textContent = ditolak;

            document.getElementById('badgeAll').textContent = total;
            document.getElementById('badgePending').textContent = pending;
            document.getElementById('badgeProcessing').textContent = diproses;
            document.getElementById('badgeReviewed').textContent = ditinjau;
            document.getElementById('badgeRejected').textContent = ditolak;
        }

        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const filtered = filter === 'all' 
                    ? allReports 
                    : allReports.filter(r => r.status === filter);
                
                renderReports(filtered);
            });
        });

        // Search
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filtered = allReports.filter(r => 
                r.judul.toLowerCase().includes(searchTerm) ||
                r.deskripsi.toLowerCase().includes(searchTerm) ||
                r.kategori?.toLowerCase().includes(searchTerm)
            );
            renderReports(filtered);
        });

        function openStatusModal(reportId) {
            document.getElementById('statusReportId').value = reportId;
            document.getElementById('statusModal').classList.add('active');
        }

        function showDetailModal(reportId) {
            const report = allReports.find(r => r.id === reportId);
            if (!report) return;

            const userData = report.users || {};
            const reportDate = new Date(report.created_at).toLocaleDateString('id-ID');

            const detailHtml = `
                <div style="padding: 20px 0;">
                    <h3>${escapeHtml(report.judul)}</h3>
                    <p style="color: #666; margin: 10px 0 20px; font-size: 13px;">
                        <strong>#${report.id}</strong> • ${reportDate}
                    </p>
                    
                    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <p style="margin: 0; line-height: 1.6; color: #333;">
                            ${escapeHtml(report.deskripsi)}
                        </p>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                        <div>
                            <p style="margin: 0 0 5px; font-weight: 600; color: #666;">Kategori</p>
                            <p style="margin: 0; color: #333;">${capitalizeFirst(report.kategori || '-')}</p>
                        </div>
                        <div>
                            <p style="margin: 0 0 5px; font-weight: 600; color: #666;">Status</p>
                            <p style="margin: 0;">
                                <span class="status-badge ${report.status}">
                                    ${capitalizeFirst(report.status)}
                                </span>
                            </p>
                        </div>
                    </div>

                    <div style="border-top: 1px solid #e0e0e0; padding-top: 15px; margin-bottom: 20px;">
                        <p style="margin: 0 0 10px; font-weight: 600; color: #666;">Pelapor</p>
                        <p style="margin: 0; color: #333;"><strong>${escapeHtml(userData.nama || 'Unknown')}</strong></p>
                        <p style="margin: 5px 0 0; color: #666; font-size: 13px;">${escapeHtml(userData.email || '-')}</p>
                    </div>

                    ${report.catatan ? `
                        <div style="background: #e7f5ff; padding: 15px; border-radius: 8px; border-left: 3px solid #0d6efd;">
                            <p style="margin: 0 0 5px; font-weight: 600; color: #0d6efd;">Catatan Admin</p>
                            <p style="margin: 0; color: #333;">${escapeHtml(report.catatan)}</p>
                        </div>
                    ` : ''}
                </div>
            `;

            document.getElementById('detailContent').innerHTML = detailHtml;
            document.getElementById('detailModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        async function submitStatusChange(event) {
            event.preventDefault();
            const formData = new FormData(event.target);
            const reportId = formData.get('report_id');
            const status = formData.get('status');
            const catatan = formData.get('catatan');

            try {
                const { error } = await supabase
                    .from('reports')
                    .update({ 
                        status, 
                        catatan,
                        updated_at: new Date().toISOString()
                    })
                    .eq('id', reportId);

                if (error) throw error;

                alert('Status berhasil diubah');
                closeModal('statusModal');
                loadReports();
            } catch (error) {
                console.error('Error updating status:', error);
                alert('Gagal mengubah status: ' + error.message);
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        });

        // Utility functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>

</body>
</html>