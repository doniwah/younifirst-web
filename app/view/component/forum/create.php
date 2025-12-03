<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Forum Baru - YouNiFirst</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    <link rel="stylesheet" href="/css/forum-create.css">
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="dashboard-container">
            <div class="create-forum-container">
                <form action="/forum/create" method="POST" enctype="multipart/form-data" id="createForumForm">
                    <div class="create-header">
                        <button type="button" class="back-button" onclick="history.back()">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                    </div>

                    <div class="forum-create-layout">
                        <div class="left-column">
                            <div class="image-upload-section">
                                <div class="image-preview-wrapper">
                                    <div class="image-preview" id="imagePreview">
                                        <i class="bi bi-people-fill default-icon"></i>
                                    </div>
                                    <label for="forumImage" class="upload-btn">
                                        <i class="bi bi-camera"></i>
                                    </label>
                                    <input type="file" id="forumImage" name="image" accept="image/*" hidden onchange="previewImage(this)">
                                </div>
                                <span class="upload-label">Ubah Foto</span>
                            </div>
                        </div>

                        <div class="right-column">
                            <div class="form-group">
                                <span class="char-count" id="nameCount">0/40</span>
                                <input type="text" class="form-input" name="nama_komunitas" placeholder="Nama Forum" maxlength="40" required oninput="updateCount(this, 'nameCount')">
                            </div>

                            <div class="form-group">
                                <span class="char-count" id="descCount">0/500</span>
                                <textarea class="form-textarea" name="deskripsi" placeholder="Deksripsi Forum" maxlength="500" required oninput="updateCount(this, 'descCount')"></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tagar</label>
                                <input type="text" class="form-input" name="tags" placeholder="Cari Tagar Forum">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Preferensi Forum</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="status" value="public" class="radio-input" checked>
                                        <div>
                                            <span class="radio-label">Publik</span>
                                            <span class="radio-desc"> - siapapun bisa bergabung</span>
                                        </div>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="status" value="private" class="radio-input">
                                        <div>
                                            <span class="radio-label">Pribadi</span>
                                            <span class="radio-desc"> - perlu undangan untuk bergabung</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="submit-btn" id="submitBtn">Buat Forum</button>
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

        function updateCount(input, counterId) {
            const count = input.value.length;
            const max = input.getAttribute('maxlength');
            document.getElementById(counterId).textContent = `${count}/${max}`;
            checkForm();
        }

        function checkForm() {
            const name = document.querySelector('input[name="nama_komunitas"]').value;
            const desc = document.querySelector('textarea[name="deskripsi"]').value;
            const btn = document.getElementById('submitBtn');
            
            if (name.length > 0 && desc.length > 0) {
                btn.classList.add('active');
                btn.disabled = false;
            } else {
                btn.classList.remove('active');
                // btn.disabled = true; // Optional: keep enabled but styled inactive
            }
        }

        // Initial check
        document.addEventListener('DOMContentLoaded', checkForm);
    </script>
</body>
</html>
