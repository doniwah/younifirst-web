<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Kompetisi Baru - YouNiFirst</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    <link rel="stylesheet" href="/css/forum-create.css">
    <style>
        /* Additional styles for competition specific fields */
        .form-row {
            display: flex;
            gap: 16px;
        }
        .form-row .form-group {
            flex: 1;
        }
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="dashboard-container">
            <div class="create-forum-container">
                <form action="/kompetisi/create" method="POST" enctype="multipart/form-data" id="createLombaForm">
                    <div class="create-header">
                        <button type="button" class="back-button" onclick="history.back()">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <h2 style="margin: 0; font-size: 1.5rem;">Buat Kompetisi Baru</h2>
                    </div>

                    <div class="forum-create-layout">
                        <div class="left-column">
                            <div class="image-upload-section">
                                <div class="image-preview-wrapper">
                                    <div class="image-preview" id="imagePreview">
                                        <i class="bi bi-trophy-fill default-icon"></i>
                                    </div>
                                    <label for="lombaImage" class="upload-btn">
                                        <i class="bi bi-camera"></i>
                                    </label>
                                    <input type="file" id="lombaImage" name="poster_lomba" accept="image/*" hidden onchange="previewImage(this)">
                                </div>
                                <span class="upload-label">Upload Poster</span>
                            </div>
                        </div>

                        <div class="right-column">
                            <div class="form-group">
                                <label class="form-label">Nama Lomba</label>
                                <input type="text" class="form-input" name="nama_lomba" placeholder="Contoh: Hackathon 2024" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Penyelenggara</label>
                                <input type="text" class="form-input" name="penyelenggara" placeholder="Contoh: BEM FIK" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-textarea" name="deskripsi" placeholder="Jelaskan detail kompetisi..." required></textarea>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Lomba</label>
                                    <input type="date" class="form-input" name="tanggal_lomba" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Lokasi</label>
                                    <input type="text" class="form-input" name="lokasi" placeholder="Online / Gedung A" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kategori</label>
                                <input type="text" class="form-input" name="kategori" placeholder="Contoh: Technology, Design (pisahkan dengan koma)" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tipe Lomba</label>
                                <select class="form-input" name="lomba_type" required>
                                    <option value="individual">Individu</option>
                                    <option value="team">Team</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Lingkup (Scope)</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="scope" value="nasional" class="radio-input" checked>
                                        <span class="radio-label">Nasional</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="scope" value="internasional" class="radio-input">
                                        <span class="radio-label">Internasional</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Biaya Pendaftaran</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="biaya" value="gratis" class="radio-input" checked onchange="toggleHarga(false)">
                                        <span class="radio-label">Gratis</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="biaya" value="berbayar" class="radio-input" onchange="toggleHarga(true)">
                                        <span class="radio-label">Berbayar</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group hidden" id="hargaGroup">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" class="form-input" name="harga_lomba" placeholder="Contoh: 50000">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Hadiah</label>
                                <input type="text" class="form-input" name="hadiah" placeholder="Contoh: Total Rp 10.000.000 + Sertifikat" required>
                            </div>

                            <button type="submit" class="submit-btn active">Buat Kompetisi</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    preview.style.border = 'none';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function toggleHarga(show) {
            const group = document.getElementById('hargaGroup');
            if (show) {
                group.classList.remove('hidden');
                group.querySelector('input').required = true;
            } else {
                group.classList.add('hidden');
                group.querySelector('input').required = false;
                group.querySelector('input').value = '';
            }
        }
    </script>
</body>
</html>
