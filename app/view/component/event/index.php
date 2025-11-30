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

    .loading {
        text-align: center;
        padding: 2rem;
        color: #666;
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
            <button class="btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Tambah Event Baru
            </button>
        </div>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari event atau organizer..." onkeyup="filterEvents()">
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterByStatus('all')">Semua</button>
                <button class="filter-tab" onclick="filterByStatus('upcoming')">Upcoming</button>
                <button class="filter-tab" onclick="filterByStatus('registration')">Registrasi</button>
                <button class="filter-tab" onclick="filterByStatus('completed')">Selesai</button>
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
                            <?= date('Y-m-d • H:i', strtotime($event['tanggal_mulai'])) ?>
                            <?php if (!empty($event['tanggal_selesai'])): ?>
                            - <?= date('H:i', strtotime($event['tanggal_selesai'])) ?>
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
                <div class="event-actions">
                    <button class="action-btn" onclick="viewEvent(<?= $event['event_id'] ?>)">
                        <i class="far fa-eye"></i>
                    </button>
                    <button class="action-btn edit" onclick="editEvent(<?= $event['event_id'] ?>)">
                        <i class="far fa-edit"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteEvent(<?= $event['event_id'] ?>)">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </div>
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

    function viewEvent(id) {
        alert('View event: ' + id);
    }

    function editEvent(id) {
        alert('Edit event: ' + id);
    }

    function deleteEvent(id) {
        if (confirm('Apakah Anda yakin ingin menghapus event ini?')) {
            alert('Delete event: ' + id);
        }
    }

    function openCreateModal() {
        alert('Open create modal');
    }
    </script>
</body>

</html>