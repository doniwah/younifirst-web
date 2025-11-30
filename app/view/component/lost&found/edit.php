<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/team.css">
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>
    <div class="main-content">
        <div class="header">
            <div>
                <h1><?= $title ?></h1>
                <p>Edit informasi barang</p>
            </div>
        </div>

        <div class="form-container" style="background: white; border-radius: 12px; padding: 32px; max-width: 800px; margin: 0 auto; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <form action="/lost_found/update/<?= $item['id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Kategori <span style="color: #ef4444;">*</span></label>
                    <select name="kategori" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px;">
                        <option value="hilang" <?= $item['kategori'] === 'hilang' ? 'selected' : '' ?>>Barang Hilang</option>
                        <option value="ditemukan" <?= $item['kategori'] === 'ditemukan' ? 'selected' : '' ?>>Barang Ditemukan</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Nama Barang <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="nama_barang" required value="<?= htmlspecialchars($item['nama_barang']) ?>" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Deskripsi <span style="color: #ef4444;">*</span></label>
                    <textarea name="deskripsi" required style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px; min-height: 120px;"><?= htmlspecialchars($item['deskripsi']) ?></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Lokasi <span style="color: #ef4444;">*</span></label>
                    <input type="text" name="lokasi" required value="<?= htmlspecialchars($item['lokasi']) ?>" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">No. HP <span style="color: #ef4444;">*</span></label>
                    <input type="tel" name="no_hp" required value="<?= htmlspecialchars($item['no_hp']) ?>" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($item['email'] ?? '') ?>" style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 8px;">
                </div>

                <div class="form-group" style="margin-bottom: 24px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500;">Foto Barang</label>
                    <?php if ($item['foto_barang']): ?>
                    <div style="margin-bottom: 12px;">
                        <img src="<?= htmlspecialchars($item['foto_barang']) ?>" style="max-width: 200px; border-radius: 8px;">
                        <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Foto saat ini</p>
                    </div>
                    <?php endif; ?>
                    <div onclick="document.getElementById('foto_barang').click()" style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 24px; text-align: center; cursor: pointer;">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 48px; color: #9ca3af; margin-bottom: 12px;"></i>
                        <p>Klik untuk upload foto baru</p>
                        <img id="imagePreview" style="max-width: 100%; max-height: 300px; margin-top: 16px; border-radius: 8px; display: none;">
                    </div>
                    <input type="file" id="foto_barang" name="foto_barang" accept="image/*" style="display: none;" onchange="previewImage(this)">
                </div>

                <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 32px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                    <a href="/lost_found" style="padding: 12px 24px; border-radius: 8px; background: #f3f4f6; color: #374151; text-decoration: none; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-times"></i> Batal
                    </a>
                    <button type="submit" style="padding: 12px 24px; border-radius: 8px; background: #4f87ff; color: white; border: none; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
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
