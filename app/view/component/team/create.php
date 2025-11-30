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
    /* Force light theme */
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
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
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
                <p>Buat pencarian anggota tim untuk lomba atau kompetisi</p>
            </div>
        </div>

        <div class="form-container">
            <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_GET['error']) ?>
            </div>
            <?php endif; ?>

            <form action="/team/store" method="POST">
                <div class="form-group">
                    <label for="nama_team">Nama Tim <span class="required">*</span></label>
                    <input type="text" id="nama_team" name="nama_team" required
                        placeholder="Contoh: Tim Mobile App Development">
                    <small>Nama tim yang akan ditampilkan dalam pencarian</small>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi <span class="required">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" required
                        placeholder="Jelaskan tentang tim, tujuan, dan apa yang dicari..."></textarea>
                    <small>Jelaskan secara detail tentang tim dan kebutuhan anggota</small>
                </div>

                <div class="form-group">
                    <label for="max_members">Jumlah Anggota yang Dibutuhkan <span class="required">*</span></label>
                    <input type="number" id="max_members" name="max_members" min="1" max="20" value="5" required>
                    <small>Total anggota yang dibutuhkan (termasuk Anda)</small>
                </div>

                <div class="form-group">
                    <label for="skills_required">Skill yang Dibutuhkan</label>
                    <input type="text" id="skills_required" name="skills_required"
                        placeholder="Flutter, UI/UX Design, Backend Development">
                    <small>Pisahkan dengan koma untuk multiple skills</small>
                </div>

                <div class="form-group">
                    <label for="contact_info">Informasi Kontak <span class="required">*</span></label>
                    <input type="text" id="contact_info" name="contact_info" required
                        placeholder="Email, WhatsApp, atau cara kontak lainnya">
                    <small>Cara untuk calon anggota menghubungi Anda</small>
                </div>

                <div class="form-group">
                    <label for="deadline">Deadline Pendaftaran</label>
                    <input type="date" id="deadline" name="deadline">
                    <small>Batas waktu untuk bergabung dengan tim</small>
                </div>

                <div class="form-actions">
                    <a href="/team" class="btn btn-secondary">
                        <i class="fas fa-times"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Simpan Pencarian Tim
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
