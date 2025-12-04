<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Settings' ?> - Younifirst</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/settings.css">
</head>
<body>
    <?php require_once __DIR__ . "/../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="settings-container">
            <!-- Left Sidebar -->
            <div class="settings-sidebar">
                <div class="user-mini-profile">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username) ?>&background=random" alt="User" class="mini-avatar">
                    <div class="mini-info">
                        <div class="mini-name"><?= htmlspecialchars($user->username) ?></div>
                        <div class="mini-email"><?= htmlspecialchars($user->email) ?></div>
                    </div>
                    <i class="bi bi-gear settings-icon"></i>
                </div>
            </div>
            <div class="settings-content">
                <div class="content-header">
                    <a href="/dashboard" class="back-btn"><i class="bi bi-arrow-left"></i></a>
                    <h2>Profil Akun</h2>
                </div>

                <div class="profile-avatar-section">
                    <div class="large-avatar-wrapper">
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user->username) ?>&background=random&size=128" alt="Profile" class="large-avatar">
                        <div class="camera-btn">
                            <i class="bi bi-camera-fill"></i>
                        </div>
                    </div>
                </div>

                <form action="/settings/update" method="POST" class="form-section">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <div class="form-input-wrapper">
                            <input type="text" class="form-input" value="<?= htmlspecialchars($user->username) ?>" readonly style="color: #999;">
                            <i class="bi bi-lock-fill edit-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="form-input-wrapper">
                            <input type="text" name="nama_lengkap" class="form-input" value="<?= htmlspecialchars($user->nama_lengkap ?? '') ?>" placeholder="Belum diisi">
                            <i class="bi bi-pencil edit-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Angkatan</label>
                        <div class="form-input-wrapper">
                            <input type="text" name="angkatan" class="form-input" value="<?= htmlspecialchars($user->angkatan ?? '') ?>" placeholder="Contoh: 2024">
                            <i class="bi bi-pencil edit-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Student Email</label>
                        <div class="form-input-wrapper">
                            <input type="text" class="form-input" value="<?= htmlspecialchars($user->email) ?>" readonly style="color: #999;">
                            <i class="bi bi-lock-fill edit-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">NIM</label>
                        <div class="form-input-wrapper">
                            <input type="text" class="form-input" value="<?= htmlspecialchars($user->user_id) ?>" readonly style="color: #999;">
                            <i class="bi bi-lock-fill edit-icon"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tahun-Bulan-Tanggal Lahir</label>
                        <div class="form-input-wrapper">
                            <input type="date" name="tgl_lahir" class="form-input" value="<?= htmlspecialchars($user->tgl_lahir ?? '') ?>">
                            <i class="bi bi-pencil edit-icon"></i>
                        </div>
                    </div>

                    <div style="text-align: right; margin-top: 20px;">
                        <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


            </div>
        </div>
    </div>
</body>
</html>
