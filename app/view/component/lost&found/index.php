<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/event.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    <style>
        .main-content {
            background-color: var(--bg-primary);
            min-height: 100vh;
        }

        /* Layout adaptation */
        .lf-container {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 20px 20px; /* Reduced top padding */
        }

        @media (max-width: 1024px) {
            .lf-container {
                grid-template-columns: 1fr;
            }
        }

        /* Post Card Styles matching dashboard feed-card */
        .post-card {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
            transition: all 0.2s ease;
            border: none; /* Reset previous border */
        }

        .post-card:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .post-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
            padding: 0; /* Reset padding */
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: none;
        }

        .user-details h4 {
            margin: 0;
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .time-ago {
            font-size: 13px;
            color: var(--text-secondary);
            margin-top: 2px;
            display: block;
        }

        .post-status-badge {
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
            color: white;
        }

        .status-hilang {
            background-color: var(--danger-color);
        }

        .status-menemukan {
            background-color: var(--primary-color);
        }

        .post-image-container {
            width: 100%;
            margin-bottom: 16px;
            border-radius: 12px;
            overflow: hidden;
            border: none;
            background: none;
        }

        .post-image {
            width: 100%;
            height: auto;
            display: block;
            max-height: 600px;
            object-fit: contain;
            border-radius: 12px;
        }

        .post-content {
            padding: 0; /* Reset padding */
        }

        .location-info {
            font-size: 14px;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        
        .post-description {
            font-size: 14px;
            line-height: 1.6;
            color: var(--text-primary);
            margin-bottom: 12px;
        }

        .read-more {
            color: var(--text-secondary);
            cursor: pointer;
            font-weight: 400;
        }

        .post-actions {
            display: flex;
            gap: 16px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border-color);
        }

        .action-item {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--text-secondary);
            font-size: 14px;
            cursor: pointer;
            transition: color 0.2s;
        }
        
        .action-item:hover {
            color: var(--primary-color);
        }

        /* Header Override to match Dashboard */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px; /* Reduced margin */
            padding: 16px 20px;
            background: transparent;
            border: none;
            position: static;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        /* Admin Controls */
        .admin-controls {
            padding: 10px 0;
            background: transparent;
            border-top: 1px solid var(--border-color);
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 12px;
        }
        /* Search Box Refinement - Premium Look */
        .search-box .search-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box input {
            width: 100%;
            padding: 12px 55px 12px 20px;
            border: 2px solid #eef2f6;
            border-radius: 16px;
            font-size: 15px;
            background: #ffffff;
            color: var(--text-primary);
            transition: all 0.3s ease;
            height: 52px;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.15);
        }

        .search-box button {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: var(--primary-color);
            color: #ffffff !important;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(74, 144, 226, 0.3);
        }

        .search-box button:hover {
            background: #3a7bc8;
            transform: translateY(-50%) scale(1.05);
        }

        .search-box button i {
            font-size: 18px;
            color: #ffffff !important;
            line-height: 1;
            display: block;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content" style="padding: 0;">
        <div class="dashboard-container">
            <!-- Header -->
            <div class="page-header">
                <h1 class="header-title">Lost and Found</h1>
                <div class="header-actions">
                    <button class="notification-btn">
                        <i class="bi bi-bell"></i>
                        <span class="badge">3</span>
                    </button>
                    <button class="mode-toggle">
                        <i class="bi bi-sun"></i>
                        <span>MODE SIANG</span>
                    </button>
                </div>
            </div>

            <div class="lf-container">
                <!-- Feed Column -->
                <div class="lf-feed">
                    <?php if (isset($_GET['success'])): ?>
                        <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px; background: #d1fae5; color: #065f46; border-radius: 12px;">
                            <?= htmlspecialchars($_GET['success']) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($datas)): ?>
                        <div class="feed-card" style="text-align: center; padding: 40px;">
                            <i class="bi bi-folder2-open" style="font-size: 48px; color: var(--text-light); margin-bottom: 16px; display: block;"></i>
                            <p style="color: var(--text-secondary);">Belum ada postingan.</p>
                            <a href="/lost_found/create" class="btn-primary" style="margin-top: 16px; display: inline-block; text-decoration: none; padding: 10px 20px; border-radius: 8px; background: var(--primary-color); color: white;">Buat Postingan</a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($datas as $item): ?>
                            <div class="post-card" data-kategori="<?= htmlspecialchars($item['kategori']) ?>">
                                <!-- Header -->
                                <div class="post-header">
                                    <div class="user-info">
                                        <!-- Placeholder Avatar -->
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($item['username'] ?? 'User') ?>&background=random" alt="Avatar" class="user-avatar">
                                        <div class="user-details">
                                            <h4><?= htmlspecialchars($item['username'] ?? 'Anonymous') ?></h4>
                                            <span class="time-ago"><?= timeAgo($item['tanggal']) ?></span>
                                        </div>
                                    </div>
                                    <div class="post-options">
                                        <span class="post-status-badge status-<?= $item['kategori'] == 'hilang' ? 'hilang' : 'menemukan' ?>">
                                            <?= $item['kategori'] == 'hilang' ? 'Kehilangan' : 'Menemukan' ?>
                                        </span>
                                        <i class="bi bi-three-dots-vertical" style="margin-left: 12px; color: var(--text-secondary); cursor: pointer;"></i>
                                    </div>
                                </div>

                                <!-- Image -->
                                <?php if ($item['foto_barang']): ?>
                                    <div class="post-image-container">
                                        <img src="<?= htmlspecialchars($item['foto_barang']) ?>" alt="<?= htmlspecialchars($item['nama_barang']) ?>" class="post-image">
                                    </div>
                                <?php endif; ?>

                                <!-- Content -->
                                <div class="post-content">
                                    <div class="location-info">
                                        <i class="bi bi-geo-alt-fill" style="color: var(--danger-color);"></i>
                                        <strong><?= $item['kategori'] == 'hilang' ? 'Lokasi Kehilangan' : 'Lokasi Ditemukan' ?> :</strong> <?= htmlspecialchars($item['lokasi']) ?>
                                    </div>
                                    
                                    <div class="post-description">
                                        <?= nl2br(htmlspecialchars($item['deskripsi'])) ?>
                                        <a href="/lost_found/detail/<?= $item['id_barang'] ?>" class="read-more" style="text-decoration: none;">...Selengkapnya</a>
                                    </div>

                                    <!-- Contact Info -->
                                    <div class="contact-info" style="margin-bottom: 16px; padding: 12px; background: var(--bg-primary); border-radius: 12px;">
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                            <i class="bi bi-telephone" style="color: var(--primary-color);"></i>
                                            <span style="font-size: 14px; color: var(--text-primary);"><?= htmlspecialchars($item['no_hp'] ?? '-') ?></span>
                                        </div>
                                        <?php if (!empty($item['e-mail'])): ?>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <i class="bi bi-envelope" style="color: var(--primary-color);"></i>
                                            <span style="font-size: 14px; color: var(--text-primary);"><?= htmlspecialchars($item['e-mail']) ?></span>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="post-actions">
                                        <div class="action-item">
                                            <i class="bi bi-heart"></i>
                                            <span>999</span>
                                        </div>
                                        <div class="action-item">
                                            <i class="bi bi-chat"></i>
                                            <span>2.5rb</span>
                                        </div>
                                        <div class="action-item" style="margin-left: auto;">
                                            <i class="bi bi-share"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Admin/Owner Controls -->
                                <?php if ($userRole === 'admin' || $user === $item['user_id']): ?>
                                    <div class="admin-controls">
                                        <button onclick="window.location.href='/lost_found/edit/<?= $item['id_barang'] ?>'" style="border: none; background: none; color: var(--primary-color); cursor: pointer; font-weight: 600;">Edit</button>
                                        <button onclick="deleteItem('<?= $item['id_barang'] ?>')" style="border: none; background: none; color: var(--danger-color); cursor: pointer; font-weight: 600;">Hapus</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Sidebar Column -->
                <aside class="dashboard-sidebar">
                    <!-- Categories (Moved above Search) -->
                    <div class="sidebar-section">
                        <h3>Kategori</h3>
                        <div class="forum-item" onclick="filterByKategori('all')">
                            <div style="width: 40px; height: 40px; background: var(--bg-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-grid" style="color: var(--text-secondary);"></i>
                            </div>
                            <div>
                                <strong>Semua</strong>
                                <span>Tampilkan semua item</span>
                            </div>
                        </div>
                        <div class="forum-item" onclick="filterByKategori('hilang')">
                            <div style="width: 40px; height: 40px; background: #FFF5F5; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-exclamation-circle" style="color: var(--danger-color);"></i>
                            </div>
                            <div>
                                <strong>Kehilangan</strong>
                                <span>Barang yang dicari</span>
                            </div>
                        </div>
                        <div class="forum-item" onclick="filterByKategori('menemukan')">
                            <div style="width: 40px; height: 40px; background: #F0F7FF; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-check-circle" style="color: var(--primary-color);"></i>
                            </div>
                            <div>
                                <strong>Ditemukan</strong>
                                <span>Barang yang ditemukan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="search-box">
                        <h3>Pencarian</h3>
                        <div class="search-input-wrapper">
                            <input type="text" id="searchInput" placeholder="Cari barang..." onkeyup="filterItems()">
                            <button onclick="filterItems()">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script>
    let currentCategory = 'all';

    function filterItems() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const itemCards = document.querySelectorAll('.post-card');

        itemCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const category = card.dataset.kategori;
            
            const matchesSearch = text.includes(searchTerm);
            const matchesCategory = currentCategory === 'all' || category === currentCategory;

            if (matchesSearch && matchesCategory) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function filterByKategori(kategori) {
        currentCategory = kategori;
        filterItems();
    }

    function deleteItem(id) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            fetch('/lost_found/delete/' + id, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal: ' + data.message);
                }
            });
        }
    }
    </script>
</body>
</html>

<?php
function timeAgo($datetime) {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return 'Baru saja';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' menit lalu';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' jam lalu';
    } else {
        return date('d M Y', $timestamp);
    }
}
?>