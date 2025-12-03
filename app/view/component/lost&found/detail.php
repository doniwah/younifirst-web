<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Campus Nexus</title>
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

        .lf-container {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 20px 20px 20px;
        }

        @media (max-width: 1024px) {
            .lf-container {
                grid-template-columns: 1fr;
            }
        }

        .detail-card {
            background: var(--bg-secondary);
            border-radius: 16px;
            padding: 24px;
            box-shadow: var(--shadow);
            border: none;
        }

        .detail-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-details h4 {
            margin: 0;
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .time-ago {
            font-size: 14px;
            color: var(--text-secondary);
        }

        .status-badge {
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            color: white;
        }

        .status-hilang {
            background-color: var(--danger-color);
        }

        .status-menemukan {
            background-color: var(--primary-color);
        }

        .detail-image {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 24px;
            max-height: 500px;
            object-fit: contain;
            background: #f8f9fa;
        }

        .detail-content h2 {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .detail-description {
            font-size: 16px;
            line-height: 1.8;
            color: var(--text-primary);
            margin-bottom: 24px;
            white-space: pre-line;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
            padding: 20px;
            background: var(--bg-primary);
            border-radius: 12px;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: rgba(74, 144, 226, 0.1);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .info-text label {
            display: block;
            font-size: 12px;
            color: var(--text-secondary);
            margin-bottom: 2px;
        }

        .info-text span {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 16px 20px;
            background: transparent;
            border: none;
        }

        .header-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 16px;
            transition: color 0.2s;
        }

        .back-btn:hover {
            color: var(--primary-color);
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content" style="padding: 0;">
        <div class="dashboard-container">
            <div class="page-header">
                <h1 class="header-title">Detail Laporan</h1>
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
                <div class="lf-feed">
                    <a href="/lost_found" class="back-btn">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                    </a>

                    <div class="detail-card">
                        <div class="detail-header">
                            <div class="user-info">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($item['username'] ?? 'User') ?>&background=random" alt="Avatar" class="user-avatar">
                                <div class="user-details">
                                    <h4><?= htmlspecialchars($item['username'] ?? 'Anonymous') ?></h4>
                                    <span class="time-ago"><?= timeAgo($item['tanggal']) ?></span>
                                </div>
                            </div>
                            <span class="status-badge status-<?= $item['kategori'] == 'hilang' ? 'hilang' : 'menemukan' ?>">
                                <?= $item['kategori'] == 'hilang' ? 'Kehilangan' : 'Menemukan' ?>
                            </span>
                        </div>

                        <?php if ($item['foto_barang']): ?>
                            <img src="<?= htmlspecialchars($item['foto_barang']) ?>" alt="<?= htmlspecialchars($item['nama_barang']) ?>" class="detail-image">
                        <?php endif; ?>

                        <div class="detail-content">
                            <h2><?= htmlspecialchars($item['nama_barang']) ?></h2>
                            
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <div class="info-text">
                                        <label><?= $item['kategori'] == 'hilang' ? 'Lokasi Kehilangan' : 'Lokasi Ditemukan' ?></label>
                                        <span><?= htmlspecialchars($item['lokasi']) ?></span>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-telephone"></i>
                                    </div>
                                    <div class="info-text">
                                        <label>Kontak</label>
                                        <span><?= htmlspecialchars($item['no_hp'] ?? '-') ?></span>
                                    </div>
                                </div>
                                <?php if (!empty($item['email'])): ?>
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="info-text">
                                        <label>Email</label>
                                        <span><?= htmlspecialchars($item['email']) ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="detail-description">
                                <?= nl2br(htmlspecialchars($item['deskripsi'])) ?>
                            </div>

                            <?php if ($userRole === 'admin' || $user === $item['user_id']): ?>
                                <div style="display: flex; gap: 12px; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-color);">
                                    <button onclick="window.location.href='/lost_found/edit/<?= $item['id_barang'] ?>'" class="btn-primary" style="background: var(--bg-primary); color: var(--text-primary); border: 1px solid var(--border-color);">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button onclick="deleteItem('<?= $item['id_barang'] ?>')" class="btn-primary" style="background: #fee2e2; color: var(--danger-color); border: none;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <aside class="dashboard-sidebar">
                    <div class="sidebar-section">
                        <h3>Informasi</h3>
                        <div style="padding: 16px; background: #F0F7FF; border-radius: 12px; margin-bottom: 16px;">
                            <h4 style="margin: 0 0 8px 0; color: var(--primary-color); font-size: 16px;">Tips Keamanan</h4>
                            <p style="font-size: 14px; color: var(--text-secondary); margin: 0; line-height: 1.5;">
                                Pastikan untuk memverifikasi kepemilikan barang sebelum melakukan serah terima. Bertemulah di tempat yang aman dan ramai.
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <script src="/js/sidebar.js"></script>
    <script>
    function deleteItem(id) {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            fetch('/lost_found/delete/' + id, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/lost_found?success=' + encodeURIComponent(data.message);
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
