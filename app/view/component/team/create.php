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
        .create-team-container {
            max-width: 1000px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .back-button {
            background: none;
            border: none;
            font-size: 24px;
            color: #6b7280;
            cursor: pointer;
            margin-bottom: 24px;
            padding: 0;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 8px;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="url"],
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
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .char-counter {
            text-align: right;
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .member-controls {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .member-control {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 12px;
        }

        .member-control label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .number-input {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .number-input button {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            background: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #6b7280;
        }

        .number-input button:hover {
            background: #f3f4f6;
        }

        .number-input input {
            width: 60px;
            text-align: center;
            border: none;
            background: transparent;
            font-size: 16px;
            font-weight: 500;
        }

        .add-position-btn {
            width: 100%;
            padding: 12px;
            background: #ede9fe;
            color: #7c3aed;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .add-position-btn:hover {
            background: #ddd6fe;
        }

        .position-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            position: relative;
        }

        .position-card .remove-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 18px;
        }

        .position-card .remove-btn:hover {
            color: #ef4444;
        }

        .position-card h4 {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .position-card input,
        .position-card textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .position-card .number-input {
            margin-bottom: 8px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 8px;
        }

        .requirement-item input {
            flex: 1;
            margin-bottom: 0;
        }

        .requirement-item button {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 16px;
        }

        .requirement-item button:hover {
            color: #ef4444;
        }

        .add-requirement-btn {
            width: 100%;
            padding: 8px;
            background: #f3f4f6;
            color: #6b7280;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
        }

        .add-requirement-btn:hover {
            background: #e5e7eb;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #8b5cf6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            margin-top: 24px;
        }

        .submit-btn:hover {
            background: #7c3aed;
        }

        .form-hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        .positions-container {
            margin-bottom: 24px;
        }
    </style>
</head>
<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>

    <div class="main-content">
        <div class="create-team-container">
            <button class="back-button" onclick="window.location.href='/team'">
                <i class="bi bi-arrow-left"></i>
            </button>

            <form action="/team/store" method="POST" id="teamForm">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <!-- Nama Tim -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Nama Tim</label>
                        <input type="text" name="nama_team" id="namaTeam" maxlength="50" required placeholder="Masukkan nama tim">
                        <div class="char-counter"><span id="namaCounter">0</span>/50</div>
                    </div>

                    <!-- Jumlah Member -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Jumlah Member Maksimal</label>
                        <p class="form-hint">Tentukan jumlah anggota maksimal yang kamu butuhkan.</p>
                        <div class="member-control" style="max-width: 300px;">
                            <div class="number-input">
                                <button type="button" onclick="changeNumber('max', -1)">−</button>
                                <input type="number" id="maxMembers" name="max_members" value="2" readonly>
                                <button type="button" onclick="changeNumber('max', 1)">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- Batas Tanggal Pendaftaran & Bonus -->
                    <!-- Batas Tanggal Pendaftaran -->
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>Batas Tanggal Pendaftaran</label>
                        <p class="form-hint">Tentukan kapan pendaftaran kandidat ditutup.</p>
                        <input type="date" name="deadline" required>
                    </div>

                </div>

                <!-- Posisi yang dibutuhkan -->
                <div class="form-group">
                    <label>Posisi yang dibutuhkan</label>
                    <p class="form-hint">Tulis posisi/tugas yang kamu butuhkan serta ketentuan dasar untuk posisi tsb.</p>
                    
                    <div class="positions-container" id="positionsContainer">
                        <!-- Position cards will be added here -->
                    </div>

                    <button type="button" class="add-position-btn" onclick="addPosition()">
                        <i class="bi bi-plus-lg"></i> Tambah Posisi
                    </button>
                </div>

                <!-- Informasi Lomba -->
                <div class="form-group">
                    <label>Informasi Lomba</label>
                    <p class="form-hint">Tulis informasi lomba yang kamu dan tim-mu akan ikuti.</p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <input type="text" name="nama_lomba" placeholder="Nama Lomba" required>
                        <input type="text" name="penyelenggara" placeholder="Nama Penyelenggara">
                        <input type="url" name="link_postingan" placeholder="Link Postingan Lomba" style="grid-column: 1 / -1;">
                    </div>
                </div>

                <button type="submit" class="submit-btn">Buat Tim</button>
            </form>
        </div>
    </div>

    <script>
        let positionCount = 0;

        // Character counter
        document.getElementById('namaTeam').addEventListener('input', function() {
            document.getElementById('namaCounter').textContent = this.value.length;
        });

        // Number input controls
        function changeNumber(type, delta) {
            const input = document.getElementById('maxMembers');
            let value = parseInt(input.value) || 2;
            value = Math.max(1, value + delta);
            input.value = value;
        }

        // Add position
        function addPosition() {
            positionCount++;
            const container = document.getElementById('positionsContainer');
            const positionCard = document.createElement('div');
            positionCard.className = 'position-card';
            positionCard.id = `position-${positionCount}`;
            
            positionCard.innerHTML = `
                <button type="button" class="remove-btn" onclick="removePosition(${positionCount})">×</button>
                <h4>Posisi ${positionCount}</h4>
                
                <input type="text" name="position_name[]" placeholder="Nama Posisi/Tugas" required>
                <input type="hidden" name="position_qty[]" value="1">
                
                <label style="font-size: 13px; color: #6b7280; margin-bottom: 8px; display: block;">Ketentuan :</label>
                <textarea name="position_req[]" rows="3" placeholder="Tulis ketentuan untuk posisi ini (opsional)"></textarea>
            `;
            
            container.appendChild(positionCard);
        }

        function removePosition(id) {
            document.getElementById(`position-${id}`).remove();
        }



        // Add first position by default
        addPosition();
    </script>
</body>
</html>
