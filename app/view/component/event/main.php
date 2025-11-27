<?php
// Check if user is admin
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

// Sample events data (replace with database query)
$events = [
  [
    'id' => 1,
    'title' => 'Workshop React JS untuk Pemula',
    'organizer' => 'Himpunan Informatika',
    'description' => 'Belajar dasar-dasar React JS untuk pengembangan web modern',
    'date' => '2024-01-15',
    'time' => '14:00 - 17:00',
    'location' => 'Lab Komputer A.3.1',
    'participants' => 45,
    'capacity' => 50,
    'status' => 'Upcoming'
  ],
  [
    'id' => 2,
    'title' => 'Seminar Digital Marketing',
    'organizer' => 'BEM Fakultas Ekonomi',
    'description' => 'Strategi pemasaran digital untuk era modern',
    'date' => '2024-01-18',
    'time' => '09:00 - 12:00',
    'location' => 'Auditorium Utama',
    'participants' => 67,
    'capacity' => 100,
    'status' => 'Upcoming'
  ],
  [
    'id' => 3,
    'title' => 'Lomba Programming Competition',
    'organizer' => 'UKM Programming Club',
    'description' => 'Kompetisi programming untuk mahasiswa se-universitas',
    'date' => '2024-01-20',
    'time' => '08:00 - 16:00',
    'location' => 'Lab Komputer B.2.1',
    'participants' => 89,
    'capacity' => 75,
    'status' => 'Registrasi'
  ]
];
?>

<section class="event-section">
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title-group">
            <h1><?= $isAdmin ? 'Event Management' : 'Event' ?></h1>
            <p><?= $isAdmin ? 'Kelola semua event kampus dalam satu tempat' : 'Temukan dan ikuti event menarik di kampus' ?>
            </p>
        </div>
        <?php if ($isAdmin): ?>
        <button class="btn-add-event">
            <i class="bi bi-plus-lg"></i>
            Tambah Event Baru
        </button>
        <?php endif; ?>
    </div>

    <!-- Search and Filter Bar -->
    <div class="search-filter-bar">
        <div class="search-input-group">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari event atau organizer..." class="search-input">
        </div>

        <div class="filter-tabs">
            <button class="filter-tab active" data-status="all">Semua</button>
            <button class="filter-tab" data-status="upcoming">Upcoming</button>
            <button class="filter-tab" data-status="registrasi">Registrasi</button>
            <button class="filter-tab" data-status="selesai">Selesai</button>
        </div>
    </div>

    <!-- Events List (Admin Style) -->
    <div class="events-list">
        <?php foreach ($events as $event): ?>
        <div class="event-list-item" data-id="<?= $event['id'] ?>"
            data-status="<?= htmlspecialchars($event['status']) ?>">
            <div class="event-list-header">
                <div class="event-list-title-section">
                    <h3 class="event-list-title">
                        <?= htmlspecialchars($event['title']) ?>
                    </h3>
                    <span class="status-badge-inline badge-<?= strtolower($event['status']) ?>">
                        <?= htmlspecialchars($event['status']) ?>
                    </span>
                </div>

                <?php if ($isAdmin): ?>
                <div class="event-actions-inline">
                    <button class="action-btn-icon view-btn" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </button>
                    <button class="action-btn-icon edit-btn" title="Edit Event">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="action-btn-icon delete-btn" title="Hapus Event">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <div class="event-list-organizer"><?= htmlspecialchars($event['organizer']) ?></div>
            <p class="event-list-description"><?= htmlspecialchars($event['description']) ?></p>

            <div class="event-list-details">
                <div class="detail-item-inline">
                    <i class="bi bi-calendar4"></i>
                    <span><?= htmlspecialchars($event['date']) ?> • <?= htmlspecialchars($event['time']) ?></span>
                </div>
                <div class="detail-item-inline">
                    <i class="bi bi-geo-alt"></i>
                    <span><?= htmlspecialchars($event['location']) ?></span>
                </div>
                <div class="detail-item-inline">
                    <i class="bi bi-people"></i>
                    <span><?= htmlspecialchars($event['participants']) ?>/<?= htmlspecialchars($event['capacity']) ?>
                        peserta</span>
                </div>
            </div>

            <?php if (!$isAdmin): ?>
            <button class="btn-register-inline">Daftar Sekarang</button>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
</section>