<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/team.css">

    <style>
    body {
        background: #f9fafb !important;
        color: #1f2937 !important;
    }

    .main-content {
        background: #f9fafb !important;
    }

    .form-container {
        background: white;
        border-radius: 12px;
        padding: 32px;
        max-width: 800px;
        margin: 0 auto;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #374151;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #4f87ff;
        box-shadow: 0 0 0 3px rgba(79, 135, 255, 0.1);
    }

    .form-group textarea {
        min-height: 120px;
        resize: vertical;
    }

    .form-group small {
        display: block;
        margin-top: 4px;
        color: #6b7280;
        font-size: 13px;
    }

    .image-upload-container {
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
    }

    .image-upload-container:hover {
        border-color: #4f87ff;
        background: #f9fafb;
    }

    .image-upload-container.has-image {
        border-style: solid;
        border-color: #4f87ff;
    }

    .image-preview {
        max-width: 100%;
        max-height: 300px;
        margin-top: 16px;
        border-radius: 8px;
    }

    .upload-icon {
        font-size: 48px;
        color: #9ca3af;
        margin-bottom: 12px;
    }

    .form-actions {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 1px solid #e5e7eb;
    }

    .btn {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-primary {
        background: #4f87ff;
        color: white;
    }

    .btn-primary:hover {
        background: #3b6fdd;
    }

    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #e5e7eb;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .required {
        color: #ef4444;
    }
    </style>
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>
    <div class="main-content">
        <div class="header">
            <div>
                <h1><?= $title ?></h1>
                <p>Edit informasi event</p>
            </div>
        </div>

        <div class="form-container">
            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
            <?php endif; ?>

            <form action="/event/update/<?= $event['event_id'] ?>" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama_event">Nama Event <span class="required">*</span></label>
                    <input type="text" id="nama_event" name="nama_event" required
                        value="<?= htmlspecialchars($event['nama_event']) ?>">
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi <span class="required">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" required><?= htmlspecialchars($event['deskripsi']) ?></textarea>
                </div>

                <div class="form-group">
                    <label for="tanggal_mulai">Tanggal & Waktu Mulai <span class="required">*</span></label>
                    <input type="datetime-local" id="tanggal_mulai" name="tanggal_mulai" required
                        value="<?= date('Y-m-d\TH:i', strtotime($event['tanggal_mulai'])) ?>">
                </div>

                <div class="form-group">
                    <label for="tanggal_selsai">Tanggal & Waktu Selesai <span class="required">*</span></label>
                    <input type="datetime-local" id="tanggal_selsai" name="tanggal_selsai" required
                        value="<?= date('Y-m-d\TH:i', strtotime($event['tanggal_selsai'])) ?>">
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi <span class="required">*</span></label>
                    <input type="text" id="lokasi" name="lokasi" required
                        value="<?= htmlspecialchars($event['lokasi']) ?>">
                </div>

                <div class="form-group">
                    <label for="organizer">Penyelenggara <span class="required">*</span></label>
                    <input type="text" id="organizer" name="organizer" required
                        value="<?= htmlspecialchars($event['organizer']) ?>">
                </div>

                <div class="form-group">
                    <label for="kapasitas">Kapasitas Peserta <span class="required">*</span></label>
                    <input type="number" id="kapasitas" name="kapasitas" min="1" max="10000" required
                        value="<?= $event['kapasitas'] ?>">
                </div>

                <div class="form-group">
                    <label for="poster_event">Poster Event (Kosongkan jika tidak ingin mengubah)</label>
                    <?php if ($event['poster_event']): ?>
                    <div style="margin-bottom: 12px;">
                        <img src="<?= htmlspecialchars($event['poster_event']) ?>" alt="Current Poster" style="max-width: 200px; border-radius: 8px;">
                        <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">Poster saat ini</p>
                    </div>
                    <?php endif; ?>
                    <div class="image-upload-container" onclick="document.getElementById('poster_event').click()">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <p>Klik untuk upload poster baru</p>
                        <small>Format: JPG, PNG, GIF (Max 5MB)</small>
                        <img id="imagePreview" class="image-preview" alt="Preview" style="display: none;">
                    </div>
                    <input type="file" id="poster_event" name="poster_event" accept="image/*" style="display: none;" onchange="previewImage(this)">
                </div>

                <div class="form-actions">
                    <a href="/event" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Update Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        const container = document.querySelector('.image-upload-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                container.classList.add('has-image');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</body>

</html>
