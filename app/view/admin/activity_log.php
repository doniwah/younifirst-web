<?php
// Admin Activity Log View
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Log Aktivitas' ?></title>
    <link rel="stylesheet" href="/css/variable.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/admin_activity_log.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    
    <?php require __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="main-content">
        <div class="activity-log-container">
            
            <!-- Header & Search -->
            <div class="page-header">
                <div class="search-filter-group">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Cari aktivitas...">
                    </div>
                    <button class="filter-btn">
                        <i class="bi bi-filter-left"></i>
                    </button>
                </div>
                <a href="#" class="export-btn" onclick="exportActivityLog(event)">
    <i class="bi bi-download"></i>
    Export PDF
</a>
            </div>

            <!-- Stats Row -->
            <div class="stats-row">
                <div class="log-stat-card">
                    <div class="log-stat-title">Diaktifkan kembali</div>
                    <div class="log-stat-value"><?= $stats['diaktifkan'] ?></div>
                </div>
                <div class="log-stat-card">
                    <div class="log-stat-title">Dihapus</div>
                    <div class="log-stat-value red"><?= $stats['dihapus'] ?></div>
                </div>
                <div class="log-stat-card">
                    <div class="log-stat-title">Suspended</div>
                    <div class="log-stat-value orange"><?= $stats['suspended'] ?></div>
                </div>
                <div class="log-stat-card">
                    <div class="log-stat-title">Blocked</div>
                    <div class="log-stat-value red"><?= $stats['blocked'] ?></div>
                </div>
            </div>

            <!-- Activity List -->
            <div class="activity-list-container">
                <?php foreach ($logs as $log): ?>
                <div class="log-item <?= $log['color'] ?>">
                    <div class="log-item-content">
                        <div class="log-icon">
                            <i class="<?= $log['icon'] ?>"></i>
                        </div>
                        <div class="log-details">
                            <div class="log-header">
                                <div class="log-title"><?= $log['title'] ?></div>
                                <div class="log-date"><?= $log['date'] ?></div>
                            </div>
                            <div class="log-meta">
                                <div><span>Pengguna:</span> <strong><?= $log['user'] ?></strong></div>
                                <div><span>Oleh:</span> <strong><?= $log['admin'] ?></strong></div>
                                <?php if (isset($log['reason'])): ?>
                                <div><span>Alasan:</span> <strong><?= $log['reason'] ?></strong></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($log['type'] === 'suspended'): ?>
                    <div class="log-accordion" onclick="this.classList.toggle('active')">
                        <div class="accordion-header">
                            <i class="bi bi-file-text accordion-icon"></i>
                            <div class="accordion-title">Catatan Internal</div>
                            <i class="bi bi-chevron-down accordion-arrow"></i>
                        </div>
                        <div class="accordion-content">
                            User telah diperingatkan sebelumnya pada tanggal 28 Nov 2025 namun tetap melakukan pelanggaran.
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
<script>
async function exportActivityLog(event) {
    event.preventDefault();
    
    try {
        // Show loading alert
        const exportBtn = event.currentTarget;
        const originalText = exportBtn.innerHTML;
        exportBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Membuat PDF...';
        exportBtn.style.pointerEvents = 'none';
        
        const response = await fetch('/admin/activity-log/export-pdf');
        const blob = await response.blob();
        
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Log_Aktivitas_${new Date().toISOString().split('T')[0]}.pdf`;
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
        document.body.removeChild(a);
        
        // Reset button
        exportBtn.innerHTML = originalText;
        exportBtn.style.pointerEvents = 'auto';
        
        // Show success message if you have alert system
        if (typeof showAlert === 'function') {
            showAlert('PDF berhasil diunduh!', 'success');
        }
    } catch (error) {
        console.error('Export error:', error);
        alert('Gagal mengekspor PDF: ' + error.message);
        
        // Reset button on error
        const exportBtn = event.currentTarget;
        exportBtn.innerHTML = '<i class="bi bi-download"></i> Export PDF';
        exportBtn.style.pointerEvents = 'auto';
    }
}
</script>
</body>
</html>
