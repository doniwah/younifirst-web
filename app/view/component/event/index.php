<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    <link rel="stylesheet" href="/css/kompetisi.css">
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <!-- Header Outside Main Content -->
    <div class="main-content">
        <?php 
        $page_title = 'Event';
        require_once __DIR__ . "/../../layouts/page-header.php"; 
        ?>

        <!-- Success/Error Messages -->
        <?php if (isset($_GET['success'])): ?>
        <div style="background: #d1fae5; border: 1px solid #10b981; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-check-circle-fill" style="font-size: 20px;"></i>
            <span><?= htmlspecialchars($_GET['success']) ?></span>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        <div style="background: #fee2e2; border: 1px solid #ef4444; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-exclamation-circle-fill" style="font-size: 20px;"></i>
            <span><?= htmlspecialchars($_GET['error']) ?></span>
        </div>
        <?php endif; ?>

        <div class="kompetisi-layout">
            <!-- Left Column: Main Content -->
            <div class="kompetisi-main">
                <!-- Horizontal Scroll Section -->
                <div class="section-trending">
                    <div class="competitions-scroll">
                        <?php if (!empty($upcomingEvents)): ?>
                        <?php foreach (array_slice($upcomingEvents, 0, 3) as $event): ?>
                        <div class="competition-card-large">
                            <?php if (!empty($event['poster_event'])): ?>
                            <img src="<?= htmlspecialchars($event['poster_event']) ?>" alt="Poster" class="comp-poster">
                            <?php else: ?>
                            <div class="comp-poster-placeholder">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <?php endif; ?>
                            
                            <div class="comp-info">
                                <div class="comp-meta">
                                    <span class="comp-scope"><?= htmlspecialchars($event['kategori'] ?? 'Event') ?></span>
                                    <span class="comp-participants">
                                        <i class="bi bi-people"></i> 
                                        <?php if ($event['kapasitas'] === '-'): ?>
                                            <?= $event['peserta_terdaftar'] ?? 0 ?>/∞
                                        <?php else: ?>
                                            <?= $event['peserta_terdaftar'] ?? 0 ?>/<?= $event['kapasitas'] ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                                
                                <h3 class="comp-title"><?= htmlspecialchars($event['nama_event']) ?></h3>
                                
                                <div class="comp-tags">
                                    <?php 
                                    $tags = !empty($event['kategori']) ? explode(',', $event['kategori']) : ['Umum'];
                                    foreach ($tags as $tag): 
                                    ?>
                                    <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                
                                <div class="comp-details">
                                    <div class="detail-row">
                                        <i class="bi bi-calendar"></i>
                                        <span><?= date('d F Y', strtotime($event['tanggal_mulai'])) ?></span>
                                    </div>
                                    <div class="detail-row">
                                        <i class="bi bi-geo-alt"></i>
                                        <span><?= htmlspecialchars($event['lokasi']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sedang Tren Title -->
                <h2 class="section-title">Sedang Tren</h2>

                <!-- Trending Events List -->
                <div class="competitions-list">
                    <?php if (!empty($trendingEvents)): ?>
                    <?php foreach ($trendingEvents as $event): ?>
                    <div class="competition-card-small">
                        <div class="card-left">
                            <?php if (!empty($event['poster_event'])): ?>
                            <img src="<?= htmlspecialchars($event['poster_event']) ?>" alt="Poster" class="comp-thumbnail">
                            <?php else: ?>
                            <div class="comp-thumbnail-placeholder">
                                <i class="bi bi-calendar-event"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="card-right">
                            <div class="comp-header-small">
                                <span class="comp-scope-small"><?= htmlspecialchars($event['kategori'] ?? 'Event') ?></span>
                                <span class="comp-participants-small">
                                    <i class="bi bi-people"></i> 
                                    <?php if ($event['kapasitas'] === '-'): ?>
                                        <?= $event['peserta_terdaftar'] ?? 0 ?>/∞
                                    <?php else: ?>
                                        <?= $event['peserta_terdaftar'] ?? 0 ?>/<?= $event['kapasitas'] ?>
                                    <?php endif; ?>
                                </span>
                            </div>
                            
                            <h4 class="comp-title-small"><?= htmlspecialchars($event['nama_event']) ?></h4>
                            
                            <div class="comp-tags-small">
                                <?php 
                                $tags = !empty($event['kategori']) ? explode(',', $event['kategori']) : ['Umum'];
                                foreach (array_slice($tags, 0, 3) as $tag): 
                                ?>
                                <span class="tag-small"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="comp-details-small">
                                <div class="detail-item-small">
                                    <i class="bi bi-calendar"></i>
                                    <span><?= date('d F Y', strtotime($event['tanggal_mulai'])) ?></span>
                                </div>
                                <div class="detail-item-small">
                                    <i class="bi bi-geo-alt"></i>
                                    <span><?= htmlspecialchars($event['lokasi']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="empty-state">
                        <p>Belum ada event yang tersedia.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right Column: Sidebar -->
            <div class="kompetisi-sidebar">
                <!-- Search Section -->
                <div class="sidebar-search">
                    <label class="search-label">Pencarian</label>
                    <div class="search-input-wrapper">
                        <input type="text" id="searchInput" placeholder="Cari" class="search-input">
                        <button class="search-btn">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- User Events Section -->
                <div class="sidebar-posts">
                    <?php if (!empty($userEvents)): ?>
                    <?php foreach ($userEvents as $event): ?>
                    <div class="user-post-card">
                        <?php if (!empty($event['poster_event'])): ?>
                        <img src="<?= htmlspecialchars($event['poster_event']) ?>" alt="Poster" class="user-post-thumbnail">
                        <?php else: ?>
                        <div class="user-post-thumbnail-placeholder">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <?php endif; ?>
                        
                        <div class="user-post-info">
                            <div class="user-post-header">
                                <span class="user-post-scope"><?= htmlspecialchars($event['kategori'] ?? 'Event') ?></span>
                                <span class="user-post-participant">
                                    <i class="bi bi-person"></i> Terdaftar
                                </span>
                            </div>
                            
                            <h4 class="user-post-title"><?= htmlspecialchars($event['nama_event']) ?></h4>
                            
                            <div class="user-post-tags">
                                <?php 
                                $tags = !empty($event['kategori']) ? explode(',', $event['kategori']) : ['Umum'];
                                foreach (array_slice($tags, 0, 3) as $tag): 
                                ?>
                                <span class="tag-badge"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="user-post-meta">
                                <div class="meta-item">
                                    <i class="bi bi-calendar"></i>
                                    <span><?= date('d M Y', strtotime($event['tanggal_mulai'])) ?></span>
                                </div>
                                <div class="meta-item">
                                    <i class="bi bi-geo-alt"></i>
                                    <span><?= htmlspecialchars($event['lokasi']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Search functionality
    document.getElementById('searchInput')?.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const eventCards = document.querySelectorAll('.competition-card-small');
        
        eventCards.forEach(card => {
            const title = card.querySelector('.comp-title-small').textContent.toLowerCase();
            if (title.includes(searchTerm)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>