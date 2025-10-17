<div class="container">
    <div class="header">
        <h1>Kompetisi</h1>
        <p>Temukan lomba dan tim untuk berkompetisi bersama</p>
    </div>

    <!-- Display Success/Error Messages -->
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
        <button class="tab active" data-tab="daftar-lomba" id="openModalBtn">Daftar Lomba</button>
        <button class="tab" data-tab="cari-tim">Cari Tim</button>
    </div>

    <!-- DAFTAR LOMBA SECTION -->
    <div id="daftar-lomba" class="tab-content active">
        <div class="top-section">
            <button class="btn-posting">Posting Lomba</button>
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

    <!-- CARI TIM SECTION -->
    <div id="cari-tim" class="tab-content">
        <div class="top-section">
            <button class="btn-posting">Buat Tim</button>
        </div>

        <div class="teams-grid">
            <div class="team-card">
                <div class="team-header">
                    <div class="team-title">
                        <span class="team-icon"><i class="bi bi-people"></i></span>
                        <h3>Code Warriors</h3>
                    </div>
                    <span class="member-count">3/5 anggota</span>
                </div>
                <p class="competition-name">Hackathon Nasional 2024</p>
                <p class="team-description">Tim yang berfokus pada pengembangan web modern</p>
                <div class="roles-section">
                    <div class="roles-title">Role yang Dibutuhkan:</div>
                    <div class="roles-list">
                        <span class="role-badge">Frontend Developer</span>
                        <span class="role-badge">UI/UX Designer</span>
                    </div>
                </div>
                <button class="btn-join">Ajukan Bergabung</button>
            </div>

            <div class="team-card">
                <div class="team-header">
                    <div class="team-title">
                        <span class="team-icon"><i class="bi bi-people"></i></span>
                        <h3>Business Innovators</h3>
                    </div>
                    <span class="member-count">2/4 anggota</span>
                </div>
                <p class="competition-name">Business Plan Competition</p>
                <p class="team-description">Tim dengan ide bisnis di bidang edtech</p>
                <div class="roles-section">
                    <div class="roles-title">Role yang Dibutuhkan:</div>
                    <div class="roles-list">
                        <span class="role-badge">Financial Analyst</span>
                        <span class="role-badge">Marketing Specialist</span>
                    </div>
                </div>
                <button class="btn-join">Ajukan Bergabung</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Posting Lomba -->
<div id="lombaModal" class="modal">
    <div class="modal-content">
        <span class="close"><i class="bi bi-x"></i></span>
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