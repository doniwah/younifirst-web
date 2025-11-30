<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Campus Nexus</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <p>Selamat datang di Campus Nexus Grid Admin Panel</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Events</span>
                    <div class="stat-icon icon-blue">
                        <i class="far fa-calendar"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $stat_event ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% dari bulan lalu</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Active Teams</span>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $stat_kompetisi ?></div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5% dari bulan lalu</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Forum Posts</span>
                    <div class="stat-icon icon-orange">
                        <i class="far fa-comment"></i>
                    </div>
                </div>
                <div class="stat-value">156</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+18% dari bulan lalu</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Lost Items</span>
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="stat-value"><?= $stat_lost ?></div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>-2% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Event Terbaru -->
            <div class="content-card">
                <div class="card-header">
                    <i class="far fa-calendar"></i>
                    <div>
                        <h2>Event Terbaru</h2>
                        <p class="card-subtitle">Event yang akan datang dan sedang berlangsung</p>
                    </div>
                </div>

                <?php if (!empty($events_latest)): ?>
                <?php foreach ($events_latest as $event): ?>
                <div class="event-item">
                    <div class="event-header">
                        <div>
                            <div class="event-title"><?= htmlspecialchars($event['nama_event']) ?></div>
                            <div class="event-organizer"><?= htmlspecialchars($event['lokasi']) ?></div>
                        </div>
                        <span class="badge badge-upcoming">Upcoming</span>
                    </div>
                    <div class="event-details">
                        <div class="event-detail">
                            <i class="far fa-clock"></i>
                            <span><?= date('Y-m-d', strtotime($event['tanggal_mulai'])) ?></span>
                        </div>
                        <div class="event-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($event['lokasi']) ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="event-item">
                    <div class="event-header">
                        <div>
                            <div class="event-title">Tidak ada event yang akan datang</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="view-all">
                    <i class="fas fa-eye"></i>
                    <span>Lihat Semua Event</span>
                </div>
            </div>

            <!-- Pencarian Tim Aktif -->
            <div class="content-card">
                <div class="card-header">
                    <i class="fas fa-users"></i>
                    <div>
                        <h2>Kompetisi Terbaru</h2>
                        <p class="card-subtitle">Kompetisi yang akan datang</p>
                    </div>
                </div>

                <?php if (!empty($kompetisi_latest)): ?>
                <?php foreach ($kompetisi_latest as $kompetisi): ?>
                <div class="team-item">
                    <div class="team-header">
                        <div>
                            <div class="team-title"><?= htmlspecialchars($kompetisi['nama_lomba']) ?></div>
                            <div class="team-creator"><?= htmlspecialchars($kompetisi['kategori']) ?></div>
                        </div>
                        <span class="badge badge-upcoming">Aktif</span>
                    </div>
                    <div class="team-tags">
                        <span class="tag"><?= htmlspecialchars($kompetisi['kategori']) ?></span>
                    </div>
                    <div class="team-footer">
                        <span>Tanggal: <?= date('Y-m-d', strtotime($kompetisi['tanggal_lomba'])) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="team-item">
                    <div class="team-header">
                        <div>
                            <div class="team-title">Tidak ada kompetisi yang akan datang</div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <div class="view-all">
                    <i class="fas fa-eye"></i>
                    <span>Lihat Semua Kompetisi</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>