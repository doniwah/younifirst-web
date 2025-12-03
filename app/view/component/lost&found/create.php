<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Younifirst</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/event.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    <link rel="stylesheet" href="/css/lostnfound.css">
    <style>
        /* Override/Custom Styles */
        .main-content {
            background-color: var(--bg-primary);
            min-height: 100vh;
        }

        /* Layout adaptation */
        .lf-container {
            display: grid;
            grid-template-columns: 1fr;
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

        /* Header Override */
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

        /* ... */

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
                <h1 class="header-title">Buat Laporan</h1>
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
                <!-- Form Column -->
                <div class="lf-feed">
                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-error" style="padding: 12px; background: #fee2e2; color: #991b1b; border-radius: 8px; margin-bottom: 20px;">
                            <i class="fas fa-exclamation-circle"></i>
                            <?= htmlspecialchars($_GET['error']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="form-card">
                        <form action="/lost_found/store" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="form-label">Kategori <span style="color: var(--danger-color);">*</span></label>
                                <select name="kategori" class="form-control" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="hilang">Barang Hilang</option>
                                    <option value="ditemukan">Barang Ditemukan</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nama Barang <span style="color: var(--danger-color);">*</span></label>
                                <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Dompet Kulit Coklat" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Deskripsi <span style="color: var(--danger-color);">*</span></label>
                                <textarea name="deskripsi" class="form-control" rows="5" placeholder="Jelaskan detail barang..." required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal Kejadian <span style="color: var(--danger-color);">*</span></label>
                                <input type="datetime-local" name="tanggal" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Lokasi <span style="color: var(--danger-color);">*</span></label>
                                <input type="text" name="lokasi" class="form-control" placeholder="Contoh: Perpustakaan Pusat" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">No. HP <span style="color: var(--danger-color);">*</span></label>
                                <input type="tel" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="e-mail" class="form-control" placeholder="email@example.com">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Foto Barang</label>
                                <div class="upload-area" onclick="document.getElementById('foto_barang').click()">
                                    <i class="bi bi-cloud-upload" style="font-size: 32px; color: var(--text-light); margin-bottom: 8px;"></i>
                                    <p style="color: var(--text-secondary); margin: 0;">Klik untuk upload foto</p>
                                    <small style="color: var(--text-light);">JPG, PNG, GIF (Max 5MB)</small>
                                    <img id="imagePreview" style="max-width: 100%; max-height: 300px; margin-top: 16px; border-radius: 8px; display: none;">
                                </div>
                                <input type="file" id="foto_barang" name="foto_barang" accept="image/*" style="display: none;" onchange="previewImage(this)">
                            </div>

                            <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--border-color);">
                                <a href="/lost_found" class="btn-cancel">Batal</a>
                                <button type="submit" class="btn-submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>
</html>