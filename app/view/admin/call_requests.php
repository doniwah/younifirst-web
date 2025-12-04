<?php
// Admin Call Requests View
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Call Request' ?></title>
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

        .call-requests-container {
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

        .stat-icon.in-progress {
            background: #cfe2ff;
            color: #0d6efd;
        }

        .stat-icon.completed {
            background: #d1e7dd;
            color: #198754;
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

        /* Request Cards */
        .requests-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .request-card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }

        .request-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        }

        .request-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            gap: 16px;
        }

        .user-info {
            display: flex;
            gap: 16px;
            align-items: flex-start;
            flex: 1;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 18px;
            flex-shrink: 0;
        }

        .user-details {
            flex: 1;
        }

        .user-name {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 4px;
        }

        .user-email {
            font-size: 13px;
            color: #6c757d;
        }

        .priority-badge {
            padding: 6px 14px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .priority-badge.urgent {
            background: #fee;
            color: #dc3545;
        }

        .priority-badge.medium {
            background: #fff3cd;
            color: #f59e0b;
        }

        .priority-badge.low {
            background: #e7f5ff;
            color: #0d6efd;
        }

        .request-content {
            margin-bottom: 16px;
        }

        .request-title {
            font-size: 18px;
            font-weight: 600;
            color: #212529;
            margin-bottom: 8px;
        }

        .request-description {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.6;
        }

        .request-meta {
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

        .request-actions {
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

        .btn-success {
            background: #198754;
            color: white;
        }

        .btn-success:hover {
            background: #157347;
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

        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
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

            .request-header {
                flex-direction: column;
            }

            .request-actions {
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
        <div class="call-requests-container">
            
            <!-- Header & Search -->
            <div class="page-header">
                <div class="search-filter-group">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" id="searchInput" placeholder="Cari...">
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
                            <div class="stat-label">Menunggu</div>
                            <div class="stat-value"><?= $stats['pending'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon in-progress">
                            <i class="bi bi-telephone-fill"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-label">Diproses</div>
                            <div class="stat-value"><?= $stats['in_progress'] ?></div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-header">
                        <div class="stat-icon completed">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-label">Selesai Hari Ini</div>
                            <div class="stat-value"><?= $stats['completed_today'] ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="filter-tabs">
                <button class="filter-tab active" data-filter="all">
                    <i class="bi bi-list-ul"></i>
                    Semua
                    <span class="badge"><?= $stats['total'] ?></span>
                </button>
                <button class="filter-tab" data-filter="pending">
                    <i class="bi bi-clock"></i>
                    Pending
                    <span class="badge"><?= $stats['pending'] ?></span>
                </button>
                <button class="filter-tab" data-filter="in_progress">
                    <i class="bi bi-hourglass-split"></i>
                    In Progress
                    <span class="badge"><?= $stats['in_progress'] ?></span>
                </button>
                <button class="filter-tab" data-filter="completed">
                    <i class="bi bi-check-circle"></i>
                    Completed
                    <span class="badge"><?= $stats['completed'] ?></span>
                </button>
            </div>

            <!-- Requests List -->
            <div class="requests-list">
                <?php if (empty($requests)): ?>
                <div class="empty-state">
                    <i class="bi bi-inbox"></i>
                    <h3>Tidak ada call request</h3>
                    <p>Belum ada permintaan panggilan yang masuk</p>
                </div>
                <?php else: ?>
                <?php foreach ($requests as $request): ?>
                <div class="request-card" data-status="<?= $request['status'] ?>">
                    <div class="request-header">
                        <div class="user-info">
                            <div class="user-avatar">
                                <?= strtoupper(substr($request['user_name'], 0, 1)) ?>
                            </div>
                            <div class="user-details">
                                <div class="user-name"><?= htmlspecialchars($request['user_name']) ?></div>
                                <div class="user-email"><?= htmlspecialchars($request['user_email']) ?></div>
                            </div>
                        </div>
                        <span class="priority-badge <?= strtolower($request['priority']) ?>">
                            <?= ucfirst($request['priority']) ?>
                        </span>
                    </div>

                    <div class="request-content">
                        <div class="request-title"><?= htmlspecialchars($request['subject']) ?></div>
                        <div class="request-description"><?= htmlspecialchars($request['description']) ?></div>
                    </div>

                    <div class="request-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <?= $request['created_at'] ?>
                        </div>
                        <?php if (!empty($request['admin_name'])): ?>
                        <div class="meta-item">
                            <i class="bi bi-person"></i>
                            Admin: <?= htmlspecialchars($request['admin_name']) ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="request-actions">
                        <?php if ($request['status'] === 'pending'): ?>
                        <button class="btn btn-outline btn-sm" onclick="openDisposeModal('<?= $request['id'] ?>')">
                            <i class="bi bi-x-circle"></i>
                            Diproses
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="handleCall('<?= $request['id'] ?>')">
                            <i class="bi bi-telephone"></i>
                            Hubungi
                        </button>
                        <?php elseif ($request['status'] === 'in_progress'): ?>
                        <button class="btn btn-success btn-sm" onclick="completeCall('<?= $request['id'] ?>')">
                            <i class="bi bi-check-circle"></i>
                            Selesai
                        </button>
                        <?php else: ?>
                        <button class="btn btn-outline btn-sm" disabled>
                            <i class="bi bi-check-circle"></i>
                            Selesai
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>
    </div>

    <!-- Dispose Modal -->
    <div id="disposeModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Diproses</h2>
                <button class="close-modal" onclick="closeModal('disposeModal')">&times;</button>
            </div>
            <form id="disposeForm" onsubmit="submitDispose(event)">
                <input type="hidden" name="request_id" id="dispose_request_id">
                <div class="form-group">
                    <label>Catatan (Opsional)</label>
                    <textarea name="notes" placeholder="Tambahkan catatan..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-outline" onclick="closeModal('disposeModal')">Batal</button>
                    <button type="submit" class="btn btn-primary">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active from all tabs
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');

                const filter = this.dataset.filter;
                const cards = document.querySelectorAll('.request-card');

                cards.forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Search
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cards = document.querySelectorAll('.request-card');

            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });

        function openDisposeModal(requestId) {
            document.getElementById('dispose_request_id').value = requestId;
            document.getElementById('disposeModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        async function submitDispose(event) {
            event.preventDefault();
            const formData = new FormData(event.target);

            try {
                const response = await fetch('/admin/call-requests/dispose', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alert('Request berhasil diproses');
                    location.reload();
                } else {
                    alert('Gagal memproses request: ' + result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        async function handleCall(requestId) {
            if (!confirm('Mulai menghubungi user ini?')) return;

            try {
                const response = await fetch('/admin/call-requests/call', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'request_id=' + requestId
                });

                const result = await response.json();

                if (result.success) {
                    alert('Status diupdate ke In Progress');
                    location.reload();
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        async function completeCall(requestId) {
            if (!confirm('Tandai panggilan ini sebagai selesai?')) return;

            try {
                const response = await fetch('/admin/call-requests/complete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'request_id=' + requestId
                });

                const result = await response.json();

                if (result.success) {
                    alert('Panggilan selesai!');
                    location.reload();
                } else {
                    alert('Gagal: ' + result.message);
                }
            } catch (error) {
                alert('Terjadi kesalahan: ' + error.message);
            }
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        });
    </script>

</body>
</html>