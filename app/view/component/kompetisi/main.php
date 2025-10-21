<div class="container">
    <div class="header">
        <h1>Kompetisi</h1>
        <p>Temukan lomba dan tim untuk berkompetisi bersama</p>
    </div>

    <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'error' ?>"
            style="padding: 15px; margin: 20px 0; border-radius: 8px; background: <?= $_GET['status'] === 'success' ? '#d4edda' : '#f8d7da' ?>; color: <?= $_GET['status'] === 'success' ? '#155724' : '#721c24' ?>; border: 1px solid <?= $_GET['status'] === 'success' ? '#c3e6cb' : '#f5c6cb' ?>;">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
    <?php endif; ?>

    <div class="search-box">
        <input type="text" id="searchInput" class="input" placeholder="Cari kompetisi atau tim...">
    </div>

    <div class="tabs">
        <button class="tab active" data-tab="daftar-lomba">Daftar Lomba</button>
        <button class="tab" data-tab="cari-tim">Cari Tim</button>
    </div>

    <div id="daftar-lomba" class="tab-content active">
        <div class="top-section">
            <button class="btn-posting" id="openModalBtn" type="button">Posting Lomba</button>
        </div>

        <div class="competitions-grid">
            <?php if (!empty($competitions)): ?>
                <?php foreach ($competitions as $comp): ?>
                    <div class="competition-card">
                        <div class="card-header">
                            <div class="card-title">
                                <span class="trophy-icon"><i class="bi bi-trophy"></i></span>
                                <h3><?= htmlspecialchars($comp['nama_lomba']) ?></h3>
                            </div>
                            <span class="category-badge badge-technology"><?= htmlspecialchars($comp['kategori']) ?></span>
                        </div>
                        <p class="card-description"><?= htmlspecialchars($comp['deskripsi']) ?></p>
                        <div class="card-details">
                            <div class="detail-item">
                                <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Deadline: <?= date('d F Y', strtotime($comp['tanggal_lomba'])) ?></span>
                            </div>
                            <div class="detail-item">
                                <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span><?= htmlspecialchars($comp['lokasi']) ?></span>
                            </div>
                            <div class="detail-item">
                                <span class="trophy-icon" style="font-size: 1rem;"><i class="bi bi-trophy"></i></span>
                                <span class="prize-amount">Rp <?= number_format($comp['hadiah'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                        <button class="btn-detail" data-id="<?= $comp['lomba_id'] ?>">Lihat Detail</button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>Belum ada kompetisi yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div id="cari-tim" class="tab-content">
        <div class="top-section">
            <button class="btn-posting" id="openTimModalBtn" type="button">Buat Tim</button>
        </div>

        <div class="teams-grid">
            <?php if (!empty($teams)): ?>
                <?php foreach ($teams as $team): ?>
                    <div class="team-card" data-team-id="<?= $team['team_id'] ?>">
                        <div class="team-header">
                            <div class="team-title">
                                <span class="team-icon"><i class="bi bi-people"></i></span>
                                <h3><?= htmlspecialchars($team['nama_team']) ?></h3>
                            </div>
                            <span class="member-count"><?= $team['jumlah_anggota'] ?> anggota</span>
                        </div>
                        <p class="team-description"><?= htmlspecialchars($team['deskripsi_anggota']) ?></p>
                        <button class="btn-join"
                            onclick="openJoinModal('<?= $team['team_id'] ?>', '<?= htmlspecialchars($team['nama_team']) ?>', <?= $team['jumlah_anggota'] ?>)">
                            Ajukan Bergabung
                        </button>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <p>Belum ada tim yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal Posting Lomba -->
<div id="lombaModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeLombaModal()"><i class="bi bi-x"></i></span>
        <h2 class="title_pop">Posting Lomba Baru</h2>
        <p class="deskripsi_pop">Bagikan informasi lomba kepada komunitas</p>

        <form action="/kompetisi/create" method="POST" enctype="multipart/form-data" id="lombaForm">
            <label>Judul Lomba <span style="color: red;">*</span></label>
            <input type="text" name="nama_lomba" placeholder="Nama lomba" required>

            <label>Kategori</label>
            <input type="text" name="kategori" placeholder="Contoh: Technology, Business">

            <label>Deskripsi</label>
            <textarea name="deskripsi" placeholder="Detail lomba..." rows="4"></textarea>

            <div class="row">
                <div>
                    <label>Deadline <span style="color: red;">*</span></label>
                    <input type="date" name="deadline" required>
                </div>
                <div>
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" placeholder="Lokasi lomba">
                </div>
            </div>

            <label>Hadiah (Rp)</label>
            <input type="number" name="hadiah" placeholder="Contoh: 10000000" min="0">

            <label>Poster Lomba</label>
            <input type="file" name="poster_lomba" accept="image/*">

            <button type="submit" class="submit-btn">Posting Lomba</button>
        </form>
    </div>
</div>

<!-- Modal Buat Tim -->
<div id="timModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeTimModal()"><i class="bi bi-x"></i></span>
        <h2 class="title_pop">Buat Tim Baru</h2>
        <p class="deskripsi_pop">Rekrut anggota untuk timmu</p>

        <form action="/team/create" method="POST" id="timForm">
            <label>Nama Tim <span style="color: red;">*</span></label>
            <input type="text" name="nama_team" placeholder="Nama tim kamu" required>

            <label>Deskripsi Tim</label>
            <textarea name="deskripsi_anggota" placeholder="Ceritakan tentang timmu..." rows="4"></textarea>

            <label>Role Anda sebagai Pembuat Tim</label>
            <select name="role_pembuat"
                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="ketua">Ketua</option>
                <option value="anggota">Anggota</option>
            </select>

            <button type="submit" class="submit-btn">Buat Tim</button>
        </form>
    </div>
</div>

<!-- Modal Ajukan Bergabung -->
<div id="joinModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeJoinModal()"><i class="bi bi-x"></i></span>
        <h2 class="title_pop">Ajukan Bergabung ke <span id="teamNameDisplay"></span></h2>
        <p class="deskripsi_pop">Isi form berikut untuk mengajukan diri bergabung dengan tim</p>

        <form action="/team/request" method="POST" id="joinForm">
            <input type="hidden" name="team_id" id="joinTeamId">

            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                <p style="margin: 0; font-size: 14px;"><strong>Anggota Saat Ini:</strong> <span
                        id="joinMemberCount"></span> orang</p>
            </div>

            <label>Role yang Diminati <span style="color: red;">*</span></label>
            <select name="role_diminta" required
                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                <option value="">Pilih Role</option>
                <option value="ketua">Ketua</option>
                <option value="anggota">Anggota</option>
            </select>

            <label>Alasan Bergabung <span style="color: red;">*</span></label>
            <textarea name="alasan_bergabung" placeholder="Ceritakan mengapa kamu ingin bergabung dengan tim ini..."
                rows="4" required></textarea>

            <label>Keahlian & Pengalaman <span style="color: red;">*</span></label>
            <textarea name="keahlian_pengalaman" placeholder="Jelaskan keahlian dan pengalaman yang relevan..." rows="4"
                required></textarea>

            <label>Link Portfolio/Project (Optional)</label>
            <input type="url" name="portfolio_link" placeholder="https://github.com/username atau portfolio link">

            <label>Kontak (Email/WhatsApp) <span style="color: red;">*</span></label>
            <input type="text" name="kontak" placeholder="email@example.com atau 08123456789" required>

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="closeJoinModal()"
                    style="flex: 1; padding: 12px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" class="submit-btn" style="flex: 1; margin: 0;">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Modal Lomba
    const lombaModal = document.getElementById('lombaModal');
    const openModalBtn = document.getElementById('openModalBtn');

    if (openModalBtn) {
        openModalBtn.onclick = function(e) {
            e.preventDefault(); // Prevent default action
            e.stopPropagation(); // Stop event bubbling
            lombaModal.style.display = 'block';
        }
    }

    function closeLombaModal() {
        lombaModal.style.display = 'none';
    }

    // Modal Tim
    const timModal = document.getElementById('timModal');
    const openTimModalBtn = document.getElementById('openTimModalBtn');

    if (openTimModalBtn) {
        openTimModalBtn.onclick = function(e) {
            e.preventDefault(); // Prevent default action
            e.stopPropagation(); // Stop event bubbling
            timModal.style.display = 'block';
        }
    }

    function closeTimModal() {
        timModal.style.display = 'none';
    }

    // Modal Join
    const joinModal = document.getElementById('joinModal');

    function openJoinModal(teamId, teamName, memberCount) {
        document.getElementById('joinTeamId').value = teamId;
        document.getElementById('teamNameDisplay').textContent = teamName;
        document.getElementById('joinMemberCount').textContent = memberCount;

        joinModal.style.display = 'block';
    }

    function closeJoinModal() {
        joinModal.style.display = 'none';
        document.getElementById('joinForm').reset();
    }

    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === lombaModal) {
            closeLombaModal();
        }
        if (event.target === timModal) {
            closeTimModal();
        }
        if (event.target === joinModal) {
            closeJoinModal();
        }
    });

    // Tab switching
    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
        });
    });

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const activeTab = document.querySelector('.tab-content.active');

        if (activeTab.id === 'daftar-lomba') {
            document.querySelectorAll('.competition-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        } else {
            document.querySelectorAll('.team-card').forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        }
    });
</script>