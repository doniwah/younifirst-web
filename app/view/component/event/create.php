<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Event Baru - Campus Nexus</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/sidebar.css">
    <link rel="stylesheet" href="/css/dashboard-modern.css">
    <style>
        body {
            background: #f5f7fa;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .create-event-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .form-row-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .back-button {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            margin-bottom: 20px;
            color: #374151;
        }

        .form-step {
            display: none;
        }

        .form-step.active {
            display: block;
        }

        .poster-upload {
            background: #eff6ff;
            border: 2px dashed #93c5fd;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 24px;
            position: relative;
        }

        .poster-upload:hover {
            background: #dbeafe;
        }

        .poster-upload i {
            font-size: 48px;
            color: #3b82f6;
            margin-bottom: 12px;
        }

        .poster-upload h3 {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .poster-upload p {
            font-size: 13px;
            color: #6b7280;
        }

        .poster-preview {
            max-width: 100%;
            max-height: 200px;
            border-radius: 12px;
            margin-top: 12px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .input-with-icon input {
            padding-left: 40px;
        }

        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .section-header h3 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .section-header p {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .add-day-btn {
            background: #eff6ff;
            color: #3b82f6;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .day-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
        }

        .day-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .day-card-header h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .remove-day-btn {
            background: none;
            border: none;
            color: #ef4444;
            cursor: pointer;
            font-size: 18px;
        }

        .time-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .price-toggle {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
        }

        .price-option {
            flex: 1;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .price-option.active {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .price-option i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .price-option span {
            display: block;
            font-size: 13px;
            font-weight: 500;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .tags-container {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 12px;
        }

        .tag-item {
            padding: 8px 16px;
            background: #eff6ff;
            color: #3b82f6;
            border: 2px solid #bfdbfe;
            border-radius: 20px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .tag-item:hover {
            background: #dbeafe;
        }

        .tag-item.active {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .tag-item input[type="checkbox"] {
            display: none;
        }

        .textarea-wrapper {
            position: relative;
        }

        .textarea-wrapper textarea {
            width: 100%;
            min-height: 120px;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            resize: vertical;
        }

        .textarea-counter {
            position: absolute;
            bottom: 12px;
            right: 16px;
            font-size: 12px;
            color: #9ca3af;
        }

        .social-media {
            display: flex;
            gap: 12px;
            margin-top: 12px;
        }

        .social-input {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .social-input i {
            font-size: 20px;
            color: #6b7280;
        }

        .social-input input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
        }

        .section-label {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .section-description {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
        }

        .submit-btn, .next-btn {
            width: 100%;
            padding: 14px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 24px;
        }

        .submit-btn:hover, .next-btn:hover {
            background: #2563eb;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="create-event-container">
            <button class="back-button" onclick="handleBack()">
                <i class="bi bi-arrow-left"></i>
            </button>

            <form action="/event/store" method="POST" enctype="multipart/form-data" id="eventForm">
                <!-- STEP 1: Basic Information -->
                <div class="form-step active" id="step1">
                    <!-- Poster Upload -->
                    <div class="poster-upload" onclick="document.getElementById('poster').click()">
                        <i class="bi bi-image"></i>
                        <h3>Tambahkan Poster Event</h3>
                        <p>Format jpg/jpeg/png. Maks 15MB</p>
                        <img id="posterPreview" class="poster-preview hidden" alt="Preview">
                        <input type="file" id="poster" name="poster_event" accept="image/*" style="display: none;" onchange="previewPoster(this)">
                    </div>

                    <!-- Row 1: Nama Event & Lokasi -->
                    <div class="form-row-2col">
                        <div class="form-group">
                            <input type="text" name="nama_event" placeholder="Nama Event" maxlength="30" id="namaEvent" required>
                            <div class="char-counter"><span id="charCount">0</span>/30</div>
                        </div>
                        <div class="form-group">
                            <div class="input-with-icon">
                                <i class="bi bi-geo-alt"></i>
                                <input type="text" name="lokasi" placeholder="Lokasi Event" required>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Tanggal Pelaksanaan & Harga -->
                    <div class="form-row-2col">
                        <!-- Tanggal dan Waktu Pelaksanaan -->
                        <div class="form-group">
                            <div class="section-header">
                                <div>
                                    <h3>Tanggal dan Waktu Pelaksanaan</h3>
                                    <p>Atur tanggal dan waktu event. Tambahkan hari jika berlangsung lebih dari satu.</p>
                                </div>
                                <button type="button" class="add-day-btn" onclick="addDay()">
                                    <i class="bi bi-plus"></i> Tambah Hari
                                </button>
                            </div>

                            <div id="daysContainer">
                                <div class="day-card">
                                    <div class="day-card-header">
                                        <h4>Hari 1</h4>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <i class="bi bi-calendar"></i>
                                            <input type="date" name="tanggal_hari[]" required>
                                        </div>
                                    </div>
                                    <div class="time-inputs">
                                        <div class="input-with-icon">
                                            <i class="bi bi-clock"></i>
                                            <input type="time" name="waktu_mulai[]" placeholder="Waktu Mulai" required>
                                        </div>
                                        <div class="input-with-icon">
                                            <i class="bi bi-clock"></i>
                                            <input type="time" name="waktu_selesai[]" placeholder="Waktu Selesai" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Harga & Link Pendaftaran -->
                        <div>
                            <div class="form-group">
                                <label>Harga</label>
                                <div class="price-toggle">
                                    <div class="price-option active" onclick="togglePrice('gratis')">
                                        <i class="bi bi-gift"></i>
                                        <span>Gratis</span>
                                    </div>
                                    <div class="price-option" onclick="togglePrice('berbayar')">
                                        <i class="bi bi-cash"></i>
                                        <span>Berbayar</span>
                                    </div>
                                </div>
                                <input type="hidden" name="harga" id="hargaInput" value="0">
                                <input type="number" name="harga_value" id="hargaValue" class="hidden" placeholder="Masukkan harga" min="0">
                            </div>

                            <!-- Link Pendaftaran -->
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="bi bi-link-45deg"></i>
                                    <input type="url" name="link_pendaftaran" placeholder="Link Pendaftaran">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Batas Tanggal dan Waktu Pendaftaran -->
                    <div class="form-group">
                        <label>Batas Tanggal dan Waktu Pendaftaran</label>
                        <p style="font-size: 12px; color: #6b7280; margin-bottom: 12px;">Tentukan kapan pendaftaran dibuka dan ditutup.</p>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="bi bi-calendar"></i>
                                    <input type="date" name="batas_tanggal_mulai" placeholder="Tanggal Mulai">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="bi bi-clock"></i>
                                    <input type="time" name="batas_waktu_mulai" placeholder="Waktu Mulai">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="bi bi-calendar"></i>
                                    <input type="date" name="batas_tanggal_tutup" placeholder="Tanggal Tutup">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-with-icon">
                                    <i class="bi bi-clock"></i>
                                    <input type="time" name="batas_waktu_tutup" placeholder="Waktu Tutup">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tag Terkait -->
                    <div class="form-group">
                        <div class="section-label">Tag Terkait</div>
                        <div class="section-description">Pilih atau ketuk tag yang sesuai dengan jenis event</div>
                        <div class="tags-container">
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Seminar">
                                <span>Seminar</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Webinar">
                                <span>Webinar</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Konser">
                                <span>Konser</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Pameran">
                                <span>Pameran</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Turnamen">
                                <span>Turnamen</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Festival">
                                <span>Festival</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Online">
                                <span>Online</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Offline">
                                <span>Offline</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Umum">
                                <span>Umum</span>
                            </label>
                            <label class="tag-item">
                                <input type="checkbox" name="tags[]" value="Hanya Mahasiswa">
                                <span>Hanya Mahasiswa</span>
                            </label>
                        </div>
                    </div>

                    <!-- Keterangan Event -->
                    <div class="form-group">
                        <div class="section-label">Keterangan Event</div>
                        <div class="textarea-wrapper">
                            <textarea name="deskripsi" id="deskripsi" maxlength="500" placeholder="Tulis keterangan event..."></textarea>
                            <div class="textarea-counter"><span id="descCount">0</span>/500</div>
                        </div>
                    </div>

                    <!-- Media Sosial -->
                    <div class="form-group">
                        <div class="section-label">Media Sosial</div>
                        <div class="section-description">Tambahkan media sosial agar peserta mudah terhubung dengan Anda (Opsional)</div>
                        <div class="social-media">
                            <div class="social-input">
                                <i class="bi bi-instagram"></i>
                                <input type="text" name="instagram" placeholder="Instagram">
                            </div>
                            <div class="social-input">
                                <i class="bi bi-whatsapp"></i>
                                <input type="text" name="whatsapp" placeholder="WhatsApp">
                            </div>
                        </div>
                    </div>

                    <!-- Hidden fields -->
                    <input type="hidden" name="organizer" value="Campus Nexus">
                    <input type="hidden" name="kapasitas" value="100">

                    <button type="submit" class="submit-btn">Buat Event</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let dayCount = 1;

        // Character counters
        document.getElementById('namaEvent').addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        document.getElementById('deskripsi').addEventListener('input', function() {
            document.getElementById('descCount').textContent = this.value.length;
        });

        // Preview poster
        function previewPoster(input) {
            const preview = document.getElementById('posterPreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Add day
        function addDay() {
            dayCount++;
            const container = document.getElementById('daysContainer');
            const dayCard = document.createElement('div');
            dayCard.className = 'day-card';
            dayCard.innerHTML = `
                <div class="day-card-header">
                    <h4>Hari ${dayCount}</h4>
                    <button type="button" class="remove-day-btn" onclick="removeDay(this)">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="bi bi-calendar"></i>
                        <input type="date" name="tanggal_hari[]" required>
                    </div>
                </div>
                <div class="time-inputs">
                    <div class="input-with-icon">
                        <i class="bi bi-clock"></i>
                        <input type="time" name="waktu_mulai[]" placeholder="Waktu Mulai" required>
                    </div>
                    <div class="input-with-icon">
                        <i class="bi bi-clock"></i>
                        <input type="time" name="waktu_selesai[]" placeholder="Waktu Selesai" required>
                    </div>
                </div>
            `;
            container.appendChild(dayCard);
        }

        // Remove day
        function removeDay(btn) {
            btn.closest('.day-card').remove();
            dayCount--;
            document.querySelectorAll('.day-card h4').forEach((h4, index) => {
                h4.textContent = `Hari ${index + 1}`;
            });
        }

        // Toggle price
        function togglePrice(type) {
            const options = document.querySelectorAll('.price-option');
            options.forEach(opt => opt.classList.remove('active'));
            event.target.closest('.price-option').classList.add('active');
            
            const hargaValue = document.getElementById('hargaValue');
            const hargaInput = document.getElementById('hargaInput');
            
            if (type === 'gratis') {
                hargaValue.classList.add('hidden');
                hargaInput.value = '0';
            } else {
                hargaValue.classList.remove('hidden');
                hargaInput.value = hargaValue.value || '0';
            }
        }

        // Update hidden input when price value changes
        document.getElementById('hargaValue')?.addEventListener('input', function() {
            document.getElementById('hargaInput').value = this.value;
        });

        // Toggle tag selection
        document.querySelectorAll('.tag-item').forEach(tag => {
            tag.addEventListener('click', function() {
                this.classList.toggle('active');
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });

        // Handle back button
        function handleBack() {
            window.location.href = '/event';
        }
    </script>
</body>
</html>
