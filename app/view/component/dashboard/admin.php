<?php
// Admin Dashboard View
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>
    <link rel="stylesheet" href="/css/variable.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    
    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="admin-dashboard">
            <!-- Header -->
            <div class="dashboard-header">
                <h1>Beranda</h1>
                <div class="header-actions">
                    <i class="bi bi-bell" style="font-size: 20px;"></i>
                    <button class="mode-toggle">
                        <i class="bi bi-brightness-high"></i>
                        MODE SIANG
                    </button>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- Total Users -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Total Pengguna</div>
                            <div class="stat-value"><?= number_format($total_users) ?></div>
                            <div class="stat-trend positive">+12% dari bulan lalu</div>
                        </div>
                        <div class="stat-icon bg-purple">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>

                <!-- Active Users -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Pengguna Aktif</div>
                            <div class="stat-value"><?= number_format($active_users) ?></div>
                            <div class="stat-trend positive">+5% dari minggu lalu</div>
                        </div>
                        <div class="stat-icon bg-green">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                </div>

                <!-- Reports -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Laporan Masuk</div>
                            <div class="stat-value"><?= number_format($laporan_masuk) ?></div>
                            <div class="stat-trend negative">15 belum ditangani</div>
                        </div>
                        <div class="stat-icon bg-orange">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                    </div>
                </div>

                <!-- Calls -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div>
                            <div class="stat-title">Call Request</div>
                            <div class="stat-value"><?= number_format($call_requests) ?></div>
                            <div class="stat-trend secondary">8 menunggu</div>
                        </div>
                        <div class="stat-icon bg-red">
                            <i class="bi bi-telephone"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-grid">
                <div class="left-column">
                    <!-- Chart Section -->
                    <div class="chart-section">
                        <div class="section-header">
                            <div>
                                <div class="section-title">Laporan Minggu Ini</div>
                                <div style="font-size: 12px; color: #6b7280;">Total: 86 laporan</div>
                            </div>
                            <div class="chart-legend">
                                <div class="legend-item">
                                    <div class="dot purple"></div> Masuk
                                </div>
                                <div class="legend-item">
                                    <div class="dot green"></div> Selesai
                                </div>
                            </div>
                        </div>
                        
                        <div class="chart-container">
                            <?php foreach ($chart_data as $day => $data): ?>
                            <div class="chart-bar-group">
                                <div class="bars">
                                    <div class="bar masuk" style="height: <?= $data['masuk'] * 5 ?>px;" title="Masuk: <?= $data['masuk'] ?>"></div>
                                    <div class="bar selesai" style="height: <?= $data['selesai'] * 5 ?>px;" title="Selesai: <?= $data['selesai'] ?>"></div>
                                </div>
                                <div class="day-label"><?= $day ?></div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Action Items -->
                    <div class="action-section">
                        <div class="section-header">
                            <div class="section-title">
                                <i class="bi bi-exclamation-triangle" style="color: #f59e0b; margin-right: 8px;"></i>
                                Perlu Tindakan
                            </div>
                            <div class="badge medium">4 pending</div>
                        </div>
                        <div class="action-list">
                            <?php foreach ($action_items as $item): ?>
                            <div class="action-item">
                                <div class="action-icon" style="background-color: <?= $item['color'] ?>20; color: <?= $item['color'] ?>;">
                                    <i class="<?= $item['icon'] ?>"></i>
                                </div>
                                <div class="action-content">
                                    <h4><?= $item['title'] ?> <span class="badge <?= $item['tag'] ?>"><?= $item['tag'] ?></span></h4>
                                    <p><?= $item['desc'] ?></p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Activity List -->
                <div class="activity-section">
                    <div class="section-header">
                        <div class="section-title">Aktivitas Terbaru</div>
                        <a href="#" style="font-size: 12px; color: #4f46e5; text-decoration: none;">Lihat Semua</a>
                    </div>
                    <div class="activity-list">
                        <?php foreach ($recent_activity as $activity): ?>
                        <div class="activity-item">
                            <div class="activity-icon" style="background-color: <?= $activity['color'] ?>20; color: <?= $activity['color'] ?>;">
                                <i class="<?= $activity['icon'] ?>"></i>
                            </div>
                            <div class="activity-content">
                                <h4><?= $activity['title'] ?></h4>
                                <p><?= $activity['desc'] ?></p>
                                <div class="activity-time"><?= $activity['time'] ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
