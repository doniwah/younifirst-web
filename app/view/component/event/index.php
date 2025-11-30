<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/event.css">
    <style>
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

    .event-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        transition: all 0.2s;
    }

    .event-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .event-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }

    .event-poster {
        width: 150px;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 20px;
        float: left;
    }

    .badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .badge-waiting {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-confirm {
        background: #d1fae5;
        color: #065f46;
    }

    .event-actions {
        display: flex;
        gap: 8px;
        margin-top: 12px;
    }

    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
    }

    .action-btn.confirm {
        background: #10b981;
        color: white;
    }

    .action-btn.confirm:hover {
        background: #059669;
    }

    .action-btn.edit {
        background: #3b82f6;
        color: white;
    }

    .action-btn.edit:hover {
        background: #2563eb;
    }

    .action-btn.delete {
        background: #ef4444;
        color: white;
    }

    .action-btn.delete:hover {
        background: #dc2626;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
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
            <a href="/event/create" class="btn-primary" style="text-decoration: none;">
                <i class="fas fa-plus"></i>
                Tambah Event Baru
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($_GET['error']) ?>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari event atau organizer..." onkeyup="filterEvents()">
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterByStatus('all')">Semua</button>
                <button class="filter-tab" onclick="filterByStatus('confirm')">Confirmed</button>
                <?php if ($userRole === 'admin'): ?>
                <button class="filter-tab" onclick="filterByStatus('waiting')">Waiting</button>
                <?php endif; ?>
            </div>
        </div>

        <!-- Event List -->
        <div class="event-list" id="eventList">
            <?php if (empty($events)): ?>
            <div class="empty-state">
                <i class="far fa-calendar-times"></i>
                <h3>Tidak ada event</h3>
                <p>Belum ada event yang dibuat. Mulai dengan membuat event pertama Anda.</p>
            </div>
            <?php else: ?>
            <?php foreach ($events as $event): ?>
            <div class="event-card" data-status="<?= htmlspecialchars($event['status']) ?>">
                <?php if ($event['poster_event']): ?>
                <img src="<?= htmlspecialchars($event['poster_event']) ?>" alt="Poster" class="event-poster">
                <?php endif; ?>
                
                <div class="event-header">
                    <div class="event-title-section">
                        <div class="event-title"><?= htmlspecialchars($event['nama_event']) ?></div>
                        <div class="event-organizer"><?= htmlspecialchars($event['organizer']) ?></div>
                    </div>
                    <span class="badge badge-<?= htmlspecialchars($event['status']) ?>">
                        <?= ucfirst(htmlspecialchars($event['status'])) ?>
                    </span>
                </div>
                <div class="event-description">
                    <?= htmlspecialchars($event['deskripsi']) ?>
                </div>
                <div class="event-meta">
                    <div class="meta-item">
                        <i class="far fa-calendar"></i>
                        <span>
                            <?= date('d M Y, H:i', strtotime($event['tanggal_mulai'])) ?>
                            <?php if (!empty($event['tanggal_selsai'])): ?>
                            - <?= date('H:i', strtotime($event['tanggal_selsai'])) ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?= htmlspecialchars($event['lokasi']) ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-users"></i>
                        <span>
                            <?= $event['peserta_terdaftar'] ?? 0 ?>/<?= $event['kapasitas'] ?> peserta
                        </span>
                    </div>
                </div>
                
                <?php if ($userRole === 'admin'): ?>
                <div class="event-actions">
                    <?php if ($event['status'] === 'waiting'): ?>
                    <button class="action-btn confirm" onclick="confirmEvent(<?= $event['event_id'] ?>)">
                        <i class="fas fa-check"></i> Konfirmasi
                    </button>
                    <?php endif; ?>
                    <button class="action-btn edit" onclick="window.location.href='/event/edit/<?= $event['event_id'] ?>'">
                        <i class="far fa-edit"></i> Edit
                    </button>
                    <button class="action-btn delete" onclick="deleteEvent(<?= $event['event_id'] ?>)">
                        <i class="far fa-trash-alt"></i> Hapus
                    </button>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function filterEvents() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const eventCards = document.querySelectorAll('.event-card');

        eventCards.forEach(card => {
            const title = card.querySelector('.event-title').textContent.toLowerCase();
            const organizer = card.querySelector('.event-organizer').textContent.toLowerCase();

            if (title.includes(searchTerm) || organizer.includes(searchTerm)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterByStatus(status) {
        const eventCards = document.querySelectorAll('.event-card');
        const tabs = document.querySelectorAll('.filter-tab');

        tabs.forEach(tab => tab.classList.remove('active'));
        event.currentTarget.classList.add('active');

        eventCards.forEach(card => {
            if (status === 'all' || card.getAttribute('data-status') === status) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function confirmEvent(id) {
        if (confirm('Apakah Anda yakin ingin mengkonfirmasi event ini?')) {
            fetch('/event/confirm/' + id, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal mengkonfirmasi event: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error);
            });
        }
    }

    function deleteEvent(id) {
        if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
            fetch('/event/delete/' + id, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal menghapus event: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error);
            });
        }
    }
    </script>
</body>

</html>