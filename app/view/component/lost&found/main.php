<div class="container">
    <div class="header">
        <h1>Lost & Found</h1>
        <p>Temukan atau laporkan barang hilang di kampus</p>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        ✓ Laporan berhasil diposting!
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-error">
        <?php
            $error_msg = match ($_GET['error']) {
                'user_not_found' => '✗ User ID tidak ditemukan. Silakan login ulang.',
                'missing_fields' => '✗ Mohon lengkapi semua field yang required.',
                'database' => '✗ Terjadi kesalahan database. Silakan coba lagi.',
                default => '✗ Terjadi kesalahan. Silakan coba lagi.'
            };
            echo $error_msg;
            ?>
    </div>
    <?php endif; ?>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Cari barang">
        <div class="custom-select">
            <select id="categoryFilter">
                <option value="all">Semua Kategori</option>
                <option value="hilang">Barang Hilang</option>
                <option value="menemukan">Barang Ditemukan</option>
            </select>
        </div>
    </div>

    <div id="daftar-lomba" class="tab-content active">
        <div class="top-section">
            <button class="btn-posting" onclick="showCreateForm()">Laporkan Barang</button>
        </div>

        <div class="competitions-grid">
            <?php if (!empty($items)): ?>
            <?php foreach ($items as $item): ?>
            <div class="competition-card" data-category="<?= htmlspecialchars($item['kategori']) ?>">
                <div class="card-header">
                    <div class="card-title">
                        <span class="trophy-icon">📦</span>
                        <h3><?= htmlspecialchars($item['nama_barang']) ?></h3>
                    </div>
                    <span class="category-badge ver-<?= htmlspecialchars($item['kategori']) ?>">
                        <?= ucfirst(htmlspecialchars($item['kategori'])) ?>
                    </span>
                </div>
                <p class="card-description"><?= htmlspecialchars($item['deskripsi']) ?></p>
                <div class="card-details">
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span><?= htmlspecialchars($item['lokasi']) ?></span>
                    </div>
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span><?= timeAgo($item['tanggal']) ?></span>
                    </div>
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.95.68l1.22 3.26a1 1 0 01-.27 1.09L8.91 9.91a11.05 11.05 0 005.18 5.18l1.88-1.27a1 1 0 011.09-.27l3.26 1.22a1 1 0 01.68.95V19a2 2 0 01-2 2h-1C9.27 21 3 14.73 3 7V5z" />
                        </svg>
                        <span><?= htmlspecialchars($item['no_hp']) ?></span>
                    </div>
                    <hr size="1" color="#888888ff" width="100%">
                    <span class="card-description dibuat-oleh">
                        Dilaporkan oleh: <?= htmlspecialchars($item['username'] ?? 'Unknown') ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>Belum ada barang yang dilaporkan.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Popup -->
<div id="modalOverlay" class="modal-overlay">
    <div id="modalContent" class="modal-content">
        <div class="modal-header">
            <h2>Laporkan Barang</h2>
            <button class="close-btn" onclick="closeModal()">&times;</button>
        </div>
        <p class="modal-subtitle">Laporkan barang hilang atau barang yang ditemukan</p>

        <form id="laporanForm" method="POST" action="/lost_found/create">
            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required>
                    <option value="">Pilih kategori</option>
                    <option value="hilang">Barang Hilang</option>
                    <option value="menemukan">Barang Ditemukan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nama_barang">Nama Barang</label>
                <input type="text" id="nama_barang" name="nama_barang" placeholder="Contoh: Dompet Kulit" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi Detail</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan ciri-ciri barang..."
                    required></textarea>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" placeholder="Lokasi terakhir terlihat / ditemukan"
                    required>
            </div>

            <div class="form-group">
                <label for="no_hp">Nomor Kontak</label>
                <input type="tel" id="no_hp" name="no_hp" placeholder="0812-xxxx-xxxx" required>
            </div>

            <div class="form-group">
                <label for="email">Email (Opsional)</label>
                <input type="email" id="email" name="email" placeholder="email@example.com">
            </div>

            <button type="submit" class="btn-submit">Posting Laporan</button>
        </form>
    </div>
</div>

<script>
// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Modal Functions
function showCreateForm() {
    const modal = document.getElementById('modalOverlay');
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('modalOverlay');
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
    document.getElementById('laporanForm').reset();
}

// Close modal when clicking outside
document.getElementById('modalOverlay').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Filter functionality
document.getElementById('categoryFilter').addEventListener('change', function() {
    const filter = this.value;
    const cards = document.querySelectorAll('.competition-card');

    cards.forEach(card => {
        if (filter === 'all' || card.dataset.category === filter) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const cards = document.querySelectorAll('.competition-card');

    cards.forEach(card => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const description = card.querySelector('.card-description').textContent.toLowerCase();

        if (title.includes(searchTerm) || description.includes(searchTerm)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});
</script>

<?php
// Helper function untuk menampilkan waktu relatif
function timeAgo($datetime)
{
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;

    if ($diff < 60) {
        return $diff . ' detik yang lalu';
    } elseif ($diff < 3600) {
        return floor($diff / 60) . ' menit yang lalu';
    } elseif ($diff < 86400) {
        return floor($diff / 3600) . ' jam yang lalu';
    } elseif ($diff < 604800) {
        return floor($diff / 86400) . ' hari yang lalu';
    } else {
        return date('d M Y', $timestamp);
    }
}
?>