<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost & Found - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/event.css">
    <style>
    .item-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }
    .item-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .item-foto {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 20px;
        float: left;
    }
    .badge-hilang {
        background: #fee2e2;
        color: #dc2626;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
    }
    .badge-ditemukan {
        background: #dbeafe;
        color: #2563eb;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
    }
    .badge-aktif {
        background: #d1fae5;
        color: #065f46;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
    }
    .badge-selesai {
        background: #f3f4f6;
        color: #6b7280;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
    }
    .action-btn {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 13px;
        margin-right: 8px;
    }
    .action-btn.edit {
        background: #3b82f6;
        color: white;
    }
    .action-btn.delete {
        background: #ef4444;
        color: white;
    }
    .action-btn.complete {
        background: #10b981;
        color: white;
    }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="header">
            <div>
                <h1>Lost & Found</h1>
                <p>Pusat informasi barang hilang dan ditemukan di kampus</p>
            </div>
            <a href="/lost_found/create" class="btn-primary" style="text-decoration: none;">
                <i class="fas fa-plus"></i>
                Tambah Item
            </a>
        </div>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success" style="padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; background: #d1fae5; color: #065f46;">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($_GET['success']) ?>
        </div>
        <?php endif; ?>

        <!-- Filters -->
        <div class="filters">
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Cari barang, lokasi, atau deskripsi..." onkeyup="filterItems()">
            </div>
            <div class="filter-tabs">
                <button class="filter-tab active" onclick="filterByKategori('all')">Semua</button>
                <button class="filter-tab" onclick="filterByKategori('hilang')">Hilang</button>
                <button class="filter-tab" onclick="filterByKategori('ditemukan')">Ditemukan</button>
            </div>
        </div>

        <!-- Item List -->
        <div class="item-list">
            <?php if (empty($datas)): ?>
            <div class="empty-state" style="text-align: center; padding: 3rem; color: #666;">
                <i class="far fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; color: #ccc;"></i>
                <h3>Tidak ada item</h3>
                <p>Belum ada barang hilang atau ditemukan yang dilaporkan.</p>
            </div>
            <?php else: ?>
            <?php foreach ($datas as $item): ?>
            <div class="item-card" data-kategori="<?= htmlspecialchars($item['kategori']) ?>">
                <?php if ($item['foto_barang']): ?>
                <img src="<?= htmlspecialchars($item['foto_barang']) ?>" alt="Foto" class="item-foto">
                <?php endif; ?>
                
                <div class="item-header">
                    <div>
                        <h3><?= htmlspecialchars($item['nama_barang']) ?></h3>
                        <div style="margin-top: 8px;">
                            <span class="badge-<?= htmlspecialchars($item['kategori']) ?>">
                                <?= ucfirst(htmlspecialchars($item['kategori'])) ?>
                            </span>
                            <span class="badge-<?= htmlspecialchars($item['status']) ?>">
                                <?= ucfirst(htmlspecialchars($item['status'])) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div style="clear: both; padding-top: 12px;">
                    <p style="color: #6b7280; margin-bottom: 12px;"><?= htmlspecialchars($item['deskripsi']) ?></p>
                    
                    <div style="display: grid; grid-template-columns: auto auto; gap: 12px; margin-bottom: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280;">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($item['lokasi']) ?></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #6b7280;">
                            <i class="far fa-clock"></i>
                            <span><?= date('d M Y, H:i', strtotime($item['tanggal'])) ?></span>
                        </div>
                    </div>
                    
                    <div style="border-top: 1px solid #f3f4f6; padding-top: 12px; margin-top: 12px;">
                        <strong style="font-size: 14px;">Kontak:</strong>
                        <div style="margin-top: 8px; font-size: 13px; color: #6b7280;">
                            <div><i class="fas fa-phone"></i> <?= htmlspecialchars($item['no_hp']) ?></div>
                            <?php if ($item['email']): ?>
                            <div><i class="far fa-envelope"></i> <?= htmlspecialchars($item['email']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($userRole === 'admin'): ?>
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #f3f4f6;">
                        <?php if ($item['status'] === 'aktif'): ?>
                        <button class="action-btn complete" onclick="markComplete(<?= $item['id'] ?>)">
                            <i class="fas fa-check"></i> Tandai Selesai
                        </button>
                        <?php endif; ?>
                        <button class="action-btn edit" onclick="window.location.href='/lost_found/edit/<?= $item['id'] ?>'">
                            <i class="far fa-edit"></i> Edit
                        </button>
                        <button class="action-btn delete" onclick="deleteItem(<?= $item['id'] ?>)">
                            <i class="far fa-trash-alt"></i> Hapus
                        </button>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script>
    function filterItems() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const itemCards = document.querySelectorAll('.item-card');

        itemCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            card.style.display = text.includes(searchTerm) ? 'block' : 'none';
        });
    }

    function filterByKategori(kategori) {
        const itemCards = document.querySelectorAll('.item-card');
        const tabs = document.querySelectorAll('.filter-tab');

        tabs.forEach(tab => tab.classList.remove('active'));
        event.currentTarget.classList.add('active');

        itemCards.forEach(card => {
            if (kategori === 'all' || card.getAttribute('data-kategori') === kategori) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function markComplete(id) {
        if (confirm('Tandai item ini sebagai selesai?')) {
            fetch('/lost_found/complete/' + id, {
                method: 'POST'
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