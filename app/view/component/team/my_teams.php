<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    
<style>
    .my-teams-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        padding-bottom: 80px; /* Space for FAB */
    }

    .team-card {
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        border: 1px solid #e5e7eb;
    }

    .team-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }

    .team-title {
        font-size: 16px;
        font-weight: 600;
        color: #111827;
        margin: 0;
    }

    .team-meta {
        display: flex;
        gap: 16px;
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 16px;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .card-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 0 -16px 12px -16px;
    }

    .post-status {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 12px;
    }

    .action-btn {
        width: 100%;
        padding: 10px;
        background: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .action-btn:hover {
        background: #1d4ed8;
    }

    .fab-btn {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #2563eb;
        color: white;
        border: none;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        cursor: pointer;
        transition: transform 0.2s;
        z-index: 50;
    }

    .fab-btn:hover {
        transform: scale(1.05);
        background: #1d4ed8;
    }
</style>
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

<div class="main-content">
    <div class="my-teams-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1 style="font-size: 20px; font-weight: 600; margin: 0;">Tim Anda</h1>
        </div>

        <?php if (empty($teams)): ?>
            <div style="text-align: center; padding: 40px 20px; color: #6b7280;">
                <p>Belum ada tim yang dibuat.</p>
            </div>
        <?php else: ?>
            <?php foreach ($teams as $team): ?>
                <div class="team-card">
                    <div class="team-header">
                        <h3 class="team-title"><?= htmlspecialchars($team['nama_team']) ?></h3>
                    </div>

                    <div class="team-meta">
                        <div class="meta-item">
                            <i class="bi bi-people"></i>
                            <span><?= $team['current_members'] ?? 1 ?>/<?= $team['max_anggota'] ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span><?= !empty($team['tenggat_join']) ? date('d F Y', strtotime($team['tenggat_join'])) : '-' ?></span>
                        </div>
                    </div>

                    <div class="card-divider"></div>

                    <div class="post-status">
                        0 postingan aktif
                    </div>

                    <div class="upload-container" id="upload-container-<?= $team['team_id'] ?>">
                        <?php if (!empty($team['poster_lomba'])): ?>
                            <img src="/uploads/posters/<?= htmlspecialchars($team['poster_lomba']) ?>" id="preview-<?= $team['team_id'] ?>" class="poster-preview" style="width: 100%; border-radius: 8px; margin-bottom: 12px;">
                        <?php else: ?>
                            <img src="" id="preview-<?= $team['team_id'] ?>" class="poster-preview" style="width: 100%; border-radius: 8px; margin-bottom: 12px; display: none;">
                        <?php endif; ?>
                        
                        <input type="file" id="poster-input-<?= $team['team_id'] ?>" style="display: none;" accept="image/*" onchange="handleFileSelect(this, '<?= $team['team_id'] ?>')">
                        
                        <button class="action-btn" onclick="document.getElementById('poster-input-<?= $team['team_id'] ?>').click()">
                            <?= !empty($team['poster_lomba']) ? 'Ganti Postingan Rekrut Tim' : 'Buat Postingan Rekrut Tim' ?>
                        </button>
                        
                        <button class="action-btn save-btn" id="save-btn-<?= $team['team_id'] ?>" style="display: none; margin-top: 8px; background-color: #059669;" onclick="uploadPoster('<?= $team['team_id'] ?>')">
                            Simpan
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <button class="fab-btn" onclick="window.location.href='/team/create?new=1'">
            <i class="bi bi-plus-lg"></i>
        </button>
    </div>
</div>
<script>
    function handleFileSelect(input, teamId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function(e) {
                var preview = document.getElementById('preview-' + teamId);
                preview.src = e.target.result;
                preview.style.display = 'block';
                
                var saveBtn = document.getElementById('save-btn-' + teamId);
                saveBtn.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function uploadPoster(teamId) {
        var input = document.getElementById('poster-input-' + teamId);
        var file = input.files[0];
        
        if (!file) {
            alert('Pilih gambar terlebih dahulu');
            return;
        }
        
        var formData = new FormData();
        formData.append('poster', file);
        
        var saveBtn = document.getElementById('save-btn-' + teamId);
        var originalText = saveBtn.innerText;
        saveBtn.innerText = 'Menyimpan...';
        saveBtn.disabled = true;
        
        fetch('/team/upload-poster/' + teamId, {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Poster berhasil disimpan');
                saveBtn.style.display = 'none';
                // Optional: reload to refresh state or just keep the preview
            } else {
                alert('Gagal menyimpan poster: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengupload poster');
        })
        .finally(() => {
            saveBtn.innerText = originalText;
            saveBtn.disabled = false;
        });
    }
</script>
</body>
</html>
