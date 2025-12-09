<?php
// Admin Dashboard View
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?></title>

    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600&display=swap">
    <link rel="stylesheet" href="/css/variable.css">
    <style>
        body::before {
            content: "Font Test: Poppins & Inter";
            display: none;
        }

        .font-test {
            font-family: 'Poppins', sans-serif !important;
            color: red !important;
        }
    </style>
</head>

<body>

    <?php require __DIR__ . '/../../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="admin-dashboard">
            <!-- Header -->
            <div class="dashboard-header">
                <div class="header-left">
                    <h1>Dashboard Admin</h1>
                    <p class="welcome-text">Selamat datang, <?= $user_name ?? 'Admin' ?>!</p>
                    <div class="date-badge">
                        <i class="bi bi-calendar3"></i>
                        <?= $current_date ?? date('d F Y') ?>
                    </div>
                </div>
                <div class="header-actions">
                    <div class="notification-badge">
                        <i class="bi bi-bell"></i>
                        <?php if (isset($unprocessed_reports) && $unprocessed_reports > 0): ?>
                            <span class="badge-count"><?= $unprocessed_reports ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="user-profile">
                        <i class="bi bi-person-circle"></i>
                        <span><?= $user_name ?? 'Admin' ?></span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <!-- Total Users -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <div class="stat-title">Total Pengguna</div>
                            <div class="stat-value"><?= number_format($total_users ?? 0) ?></div>
                            <div class="stat-change">
                                <i
                                    class="bi bi-arrow-up <?= isset($user_growth['trend']) && $user_growth['trend'] == 'up' ? 'positive' : 'negative' ?>"></i>
                                <span
                                    class="stat-percentage <?= isset($user_growth['trend']) && $user_growth['trend'] == 'up' ? 'positive' : 'negative' ?>">
                                    <?= $user_growth['percentage'] ?? '0' ?>%
                                </span>
                                <span class="stat-period">dari bulan lalu</span>
                            </div>
                        </div>
                        <div class="stat-icon bg-blue">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span><i class="bi bi-check-circle"></i> <?= $active_users ?? 0 ?> aktif</span>
                    </div>
                </div>

                <!-- Active Reports -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <div class="stat-title">Laporan Aktif</div>
                            <div class="stat-value"><?= number_format($laporan_masuk ?? 0) ?></div>
                            <div class="stat-change">
                                <i class="bi bi-arrow-down negative"></i>
                                <span class="stat-percentage negative">
                                    <?= isset($laporan_selesai) && $laporan_masuk > 0 ? round(($laporan_selesai / $laporan_masuk) * 100) : '0' ?>%
                                </span>
                                <span class="stat-period">selesai</span>
                            </div>
                        </div>
                        <div class="stat-icon bg-orange">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span><i class="bi bi-clock"></i> <?= $laporan_masuk - ($laporan_selesai ?? 0) ?>
                            menunggu</span>
                    </div>
                </div>

                <!-- Call Requests -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <div class="stat-title">Call Request</div>
                            <div class="stat-value"><?= number_format($call_requests ?? 0) ?></div>
                            <div class="stat-change">
                                <i class="bi bi-arrow-up positive"></i>
                                <span class="stat-percentage positive">
                                    <?= isset($average_response_time) ? round($average_response_time) : '0' ?>
                                </span>
                                <span class="stat-period">menit rata-rata</span>
                            </div>
                        </div>
                        <div class="stat-icon bg-green">
                            <i class="bi bi-telephone"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span><i class="bi bi-hourglass-split"></i> 8 menunggu konfirmasi</span>
                    </div>
                </div>

                <!-- Resolution Rate -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <div class="stat-title">Tingkat Penyelesaian</div>
                            <div class="stat-value">
                                <?= isset($laporan_masuk) && $laporan_masuk > 0 ? round((($laporan_selesai ?? 0) / $laporan_masuk) * 100) : '0' ?>%
                            </div>
                            <div class="stat-change">
                                <i class="bi bi-arrow-up positive"></i>
                                <span class="stat-percentage positive">
                                    <?= $laporan_bulanan['improvement'] ?? '0' ?>%
                                </span>
                                <span class="stat-period">dari bulan lalu</span>
                            </div>
                        </div>
                        <div class="stat-icon bg-purple">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                    </div>
                    <div class="stat-footer">
                        <span><i class="bi bi-lightning-charge"></i> Target: 85%</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="content-grid">
                <!-- Left Column -->
                <div class="left-column">
                    <!-- Weekly Report Chart -->
                    <div class="chart-section">
                        <div class="section-header">
                            <div class="section-title">
                                <h3><i class="bi bi-bar-chart-line"></i> Laporan Mingguan</h3>
                                <p class="section-subtitle">Statistik laporan masuk vs selesai</p>
                            </div>
                            <div class="section-actions">
                                <select class="time-select">
                                    <option>Minggu Ini</option>
                                    <option>Bulan Ini</option>
                                    <option>Tahun Ini</option>
                                </select>
                            </div>
                        </div>

                        <div class="chart-wrapper">
                            <canvas id="weeklyChart"></canvas>
                        </div>

                        <div class="chart-summary">
                            <div class="summary-item">
                                <span class="summary-label">Total Laporan</span>
                                <span class="summary-value">
                                    <?= array_sum(array_column($chart_data, 'masuk')) ?? 0 ?>
                                </span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Terselesaikan</span>
                                <span class="summary-value positive">
                                    <?= array_sum(array_column($chart_data, 'selesai')) ?? 0 ?>
                                </span>
                            </div>
                            <div class="summary-item">
                                <span class="summary-label">Dalam Proses</span>
                                <span class="summary-value warning">
                                    <?= (array_sum(array_column($chart_data, 'masuk')) - array_sum(array_column($chart_data, 'selesai'))) ?? 0 ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Items -->
                    <div class="action-section">
                        <div class="section-header">
                            <div class="section-title">
                                <h3><i class="bi bi-exclamation-triangle"></i> Perlu Tindakan</h3>
                                <div class="badge high"><?= count($action_items ?? []) ?> urgent</div>
                            </div>
                            <a href="/admin/actions" class="view-all">Lihat Semua <i class="bi bi-arrow-right"></i></a>
                        </div>

                        <div class="action-list">
                            <?php if (!empty($action_items)): ?>
                                <?php foreach ($action_items as $item): ?>
                                    <div class="action-item">
                                        <div class="action-icon"
                                            style="background-color: <?= $item['color'] ?>20; color: <?= $item['color'] ?>;">
                                            <i class="<?= $item['icon'] ?>"></i>
                                        </div>
                                        <div class="action-content">
                                            <div class="action-header">
                                                <h4><?= $item['title'] ?></h4>
                                                <span
                                                    class="badge <?= $item['tag'] ?? 'medium' ?>"><?= $item['tag'] ?? 'medium' ?></span>
                                            </div>
                                            <p><?= $item['desc'] ?></p>
                                            <div class="action-time">
                                                <i class="bi bi-clock"></i> <?= $item['time'] ?? 'Baru saja' ?>
                                            </div>
                                        </div>
                                        <button class="action-button">
                                            <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="bi bi-check-circle"></i>
                                    <p>Tidak ada tindakan yang diperlukan</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="right-column">
                    <!-- Recent Activity -->
                    <div class="activity-section">
                        <div class="section-header">
                            <div class="section-title">
                                <h3><i class="bi bi-clock-history"></i> Aktivitas Terbaru</h3>
                            </div>
                            <a href="/admin/activity" class="view-all">Lihat Semua <i class="bi bi-arrow-right"></i></a>
                        </div>

                        <div class="activity-list">
                            <?php if (!empty($recent_activity)): ?>
                                <?php foreach ($recent_activity as $activity): ?>
                                    <div class="activity-item">
                                        <div class="activity-icon"
                                            style="background-color: <?= $activity['color'] ?>20; color: <?= $activity['color'] ?>;">
                                            <i class="<?= $activity['icon'] ?>"></i>
                                        </div>
                                        <div class="activity-content">
                                            <h4><?= $activity['title'] ?></h4>
                                            <p><?= $activity['desc'] ?></p>
                                            <div class="activity-meta">
                                                <span class="activity-time">
                                                    <i class="bi bi-clock"></i> <?= $activity['time'] ?>
                                                </span>
                                                <?php if (isset($activity['user'])): ?>
                                                    <span class="activity-user">
                                                        <i class="bi bi-person"></i> <?= $activity['user'] ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <i class="bi bi-activity"></i>
                                    <p>Tidak ada aktivitas terbaru</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Top Categories -->
                    <div class="categories-section">
                        <div class="section-header">
                            <h3><i class="bi bi-tags"></i> Kategori Laporan</h3>
                        </div>

                        <div class="categories-list">
                            <?php if (!empty($top_categories)): ?>
                                <?php foreach ($top_categories as $category): ?>
                                    <div class="category-item">
                                        <div class="category-info">
                                            <span class="category-name"><?= $category['name'] ?></span>
                                            <span class="category-count"><?= $category['count'] ?> laporan</span>
                                        </div>
                                        <div class="category-progress">
                                            <div class="progress-bar" style="width: <?= $category['percentage'] ?>%"></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <p>Belum ada data kategori</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart Data dari PHP
        const chartData = <?= json_encode($chart_data ?? []) ?>;
        const days = Object.keys(chartData);
        const masukData = days.map(day => chartData[day]?.masuk || 0);
        const selesaiData = days.map(day => chartData[day]?.selesai || 0);

        // Inisialisasi Chart
        const ctx = document.getElementById('weeklyChart').getContext('2d');
        const weeklyChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: days,
                datasets: [{
                        label: 'Masuk',
                        data: masukData,
                        backgroundColor: 'rgba(79, 70, 229, 0.7)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false,
                    },
                    {
                        label: 'Selesai',
                        data: selesaiData,
                        backgroundColor: 'rgba(16, 185, 129, 0.7)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1,
                        borderRadius: 6,
                        borderSkipped: false,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 5
                        },
                        grid: {
                            borderDash: [2, 2]
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Dark/Light Mode Toggle
        document.querySelector('.mode-toggle')?.addEventListener('click', function() {
            const body = document.body;
            const isDark = body.classList.contains('dark-mode');

            if (isDark) {
                body.classList.remove('dark-mode');
                this.innerHTML = '<i class="bi bi-brightness-high"></i> MODE SIANG';
            } else {
                body.classList.add('dark-mode');
                this.innerHTML = '<i class="bi bi-moon"></i> MODE MALAM';
            }
        });
    </script>

</body>

</html>