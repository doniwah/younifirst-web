<div class="kompetisi-layout">
    <!-- Left Column: Main Content -->
    <div class="kompetisi-main">
        <!-- Header -->
        <header class="dashboard-header">
            <h1>Kompetisi dan Tim</h1>
        </header>

        <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <div class="alert alert-<?= $_GET['status'] === 'success' ? 'success' : 'error' ?>">
            <?= htmlspecialchars($_GET['message']) ?>
        </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="tabs-container">
            <button class="tab-btn active" data-tab="daftar-lomba">Kompetisi</button>
            <button class="tab-btn" data-tab="cari-tim">Tim</button>
            <button class="filter-btn">
                <i class="bi bi-funnel"></i>
            </button>
            <a href="/kompetisi/create" class="btn-create-lomba" style="margin-left: auto; background: #4f87ff; color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
                <i class="bi bi-plus-lg"></i> Buat Lomba
            </a>
        </div>

        <!-- Kompetisi Tab Content -->
        <div id="daftar-lomba" class="tab-content active">
            <!-- Sedang Tren Section -->
            <div class="section-trending">
                <div class="competitions-scroll">
                    <?php if (!empty($competitions)): ?>
                    <?php foreach (array_slice($competitions, 0, 3) as $comp): ?>
                    <div class="competition-card-large">
                        <?php if (!empty($comp['poster_lomba'])): ?>
                        <img src="<?= htmlspecialchars($comp['poster_lomba']) ?>" alt="Poster" class="comp-poster">
                        <?php else: ?>
                        <div class="comp-poster-placeholder">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <?php endif; ?>
                        
                        <div class="comp-info">
                            <div class="comp-meta">
                                <span class="comp-scope">Berbayar • Nasional</span>
                                <span class="comp-participants">
                                    <i class="bi bi-people"></i> Tim + <i class="bi bi-person"></i> Individu
                                </span>
                            </div>
                            
                            <h3 class="comp-title"><?= htmlspecialchars($comp['nama_lomba']) ?></h3>
                            
                            <div class="comp-tags">
                                <?php 
                                $tags = !empty($comp['kategori']) ? explode(',', $comp['kategori']) : ['Umum'];
                                foreach ($tags as $tag): 
                                ?>
                                <span class="tag"><?= htmlspecialchars(trim($tag)) ?></span>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="comp-details">
                                <div class="detail-row">
                                    <i class="bi bi-calendar"></i>
                                    <span><?= date('d F Y', strtotime($comp['tanggal_lomba'])) ?> - <?= date('d F Y', strtotime($comp['tanggal_lomba'] . ' +15 days')) ?></span>
                                </div>
                                <div class="detail-row">
                                    <i class="bi bi-geo-alt"></i>
                                    <span><?= htmlspecialchars($comp['lokasi']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sedang Tren Title -->
            <h2 class="section-title">Sedang Tren</h2>

            <!-- Trending Competitions List -->
            <div class="competitions-list">
                <?php if (!empty($competitions)): ?>
                <?php foreach ($competitions as $comp): ?>
                <div class="competition-card-small">
                    <div class="card-left">
                        <?php if (!empty($comp['poster_lomba'])): ?>
                        <img src="<?= htmlspecialchars($comp['poster_lomba']) ?>" alt="Poster" class="comp-thumbnail">
                        <?php else: ?>
                        <div class="comp-thumbnail-placeholder">
                            <i class="bi bi-trophy"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card-right">
                        <div class="comp-header-small">
                            <span class="comp-scope-small">Berbayar • Nasional</span>
                            <span class="comp-participants-small">
                                <i class="bi bi-people"></i> Tim + <i class="bi bi-person"></i> Individu
                            </span>
                        </div>
                        
                        <h4 class="comp-title-small"><?= htmlspecialchars($comp['nama_lomba']) ?></h4>
                        
                        <div class="comp-tags-small">
                            <?php 
                            $tags = !empty($comp['kategori']) ? explode(',', $comp['kategori']) : ['Umum'];
                            foreach (array_slice($tags, 0, 3) as $tag): 
                            ?>
                            <span class="tag-small"><?= htmlspecialchars(trim($tag)) ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="comp-details-small">
                            <div class="detail-item-small">
                                <i class="bi bi-calendar"></i>
                                <span><?= date('d F Y', strtotime($comp['tanggal_lomba'])) ?> - <?= date('d F Y', strtotime($comp['tanggal_lomba'] . ' +15 days')) ?></span>
                            </div>
                            <div class="detail-item-small">
                                <i class="bi bi-geo-alt"></i>
                                <span><?= htmlspecialchars($comp['lokasi']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state">
                    <p>Belum ada kompetisi yang tersedia.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tim Tab Content -->
        <div id="cari-tim" class="tab-content">
            <div class="teams-list">
                <?php if (!empty($teams)): ?>
                <?php foreach ($teams as $team): ?>
                <div class="team-card-modern">
                    <div class="team-card-left">
                        <?php if (!empty($team['poster_lomba'])): ?>
                        <img src="<?= htmlspecialchars($team['poster_lomba']) ?>" alt="Team Poster" class="team-poster">
                        <?php else: ?>
                        <div class="team-poster-placeholder">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="team-card-right">
                        <div class="team-meta-header">
                            <span class="team-scope">Berbayar • Nasional</span>
                            <span class="team-participants">
                                <i class="bi bi-people"></i> Tim + <i class="bi bi-person"></i> Individu
                            </span>
                        </div>
                        
                        <h4 class="team-title-modern"><?= htmlspecialchars($team['nama_kegiatan']) ?></h4>
                        
                        <div class="team-info-row">
                            <div class="team-info-item">
                                <span class="info-label">Nama Tim:</span>
                                <span class="info-value"><?= htmlspecialchars($team['nama_team']) ?></span>
                            </div>
                            <div class="team-info-item">
                                <span class="info-label">Ketua:</span>
                                <span class="info-value">Ketua</span>
                            </div>
                        </div>
                        
                        <div class="team-tags-modern">
                            <?php 
                            $roles = !empty($team['role_dibutuhkan']) ? explode(',', $team['role_dibutuhkan']) : ['Anggota'];
                            foreach (array_slice($roles, 0, 3) as $role): 
                            ?>
                            <span class="team-tag"><?= htmlspecialchars(trim($role)) ?></span>
                            <?php endforeach; ?>
                        </div>
                        
                        <p class="team-description-modern"><?= htmlspecialchars($team['deskripsi_anggota']) ?></p>
                        
                        <div class="team-details-modern">
                            <div class="team-detail-item">
                                <i class="bi bi-people"></i>
                                <span>Butuh <?= $team['max_anggota'] ?> anggota</span>
                            </div>
                            <div class="team-detail-item">
                                <i class="bi bi-check-circle"></i>
                                <span>Status: <?= htmlspecialchars($team['status'] ?? 'Aktif') ?></span>
                            </div>
                        </div>
                        
                        <button class="btn-join-team-modern" onclick="openJoinModal('<?= $team['team_id'] ?>', '<?= htmlspecialchars($team['nama_team']) ?>', <?= $team['max_anggota'] ?>)">
                            Ajukan Bergabung
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <div class="empty-state">
                    <p>Belum ada tim yang tersedia.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar -->
    <div class="kompetisi-sidebar">
        <!-- Search Section -->
        <div class="sidebar-search">
            <label class="search-label">Pencarian</label>
            <div class="search-input-wrapper">
                <input type="text" id="searchInput" placeholder="Cari" class="search-input">
                <button class="search-btn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>

        <!-- User Posts Section -->
        <div class="sidebar-posts">
            <!-- <h3 class="sidebar-title">Postingan Lomba Anda</h3> -->
            
            <?php if (!empty($user_competitions)): ?>
            <?php foreach ($user_competitions as $userComp): ?>
            <div class="user-post-card">
                <?php if (!empty($userComp['poster_lomba'])): ?>
                <img src="<?= htmlspecialchars($userComp['poster_lomba']) ?>" alt="Poster" class="user-post-thumbnail">
                <?php else: ?>
                <div class="user-post-thumbnail-placeholder">
                    <i class="bi bi-trophy"></i>
                </div>
                <?php endif; ?>
                
                <div class="user-post-info">
                    <div class="user-post-header">
                        <span class="user-post-scope">Berbayar • Nasional</span>
                        <span class="user-post-participant">
                            <i class="bi bi-person"></i> Individu
                        </span>
                    </div>
                    
                    <h4 class="user-post-title"><?= htmlspecialchars($userComp['nama_lomba']) ?></h4>
                    
                    <div class="user-post-tags">
                        <?php 
                        $tags = !empty($userComp['kategori']) ? explode(',', $userComp['kategori']) : ['Umum'];
                        foreach (array_slice($tags, 0, 3) as $tag): 
                        ?>
                        <span class="tag-badge"><?= htmlspecialchars(trim($tag)) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="user-post-meta">
                        <div class="meta-item">
                            <i class="bi bi-calendar"></i>
                            <span><?= date('d M Y', strtotime($userComp['tanggal_lomba'])) ?> - <?= date('d M Y', strtotime($userComp['tanggal_lomba'] . ' +15 days')) ?></span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-geo-alt"></i>
                            <span><?= htmlspecialchars($userComp['lokasi']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <!-- <div class="empty-user-posts">
                <p>Belum ada postingan lomba Anda</p>
            </div> -->
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
            <div class="main-content-add">
                <div class="left-content">
                    <label>Judul Lomba <span style="color: red;">*</span></label>
                    <input type="text" name="nama_lomba" placeholder="Nama lomba" required>

                    <label>Kategori</label>
                    <input type="text" name="kategori" placeholder="Contoh: Technology, Business">

                    <label>Deskripsi</label>
                    <textarea name="deskripsi" placeholder="Detail lomba..." rows="4"></textarea>
                </div>
                <div class="right-content">
                    <div class="row">
                        <div>
                            <label>Deadline <span style="color: red;">*</span></label>
                            <input type="date" name="tanggal_lomba" required>
                        </div>
                        <div>
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" placeholder="Lokasi lomba">
                        </div>
                    </div>

                    <label>Hadiah (Rp)</label>
                    <input type="number" name="hadiah" placeholder="Contoh: 10000000" min="0" value="0">

                    <label>Poster Lomba</label>
                    <input type="file" name="poster_lomba" accept="image/*">
                </div>
            </div>
            <button type="submit" class="submit-btn">Posting Lomba</button>
        </form>
    </div>
</div>

<!-- Modal Buat Tim -->
<div id="timModal" class="modal" style="display: none;">
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
        e.preventDefault();
        e.stopPropagation();
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
        e.preventDefault();
        e.stopPropagation();
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
document.querySelectorAll('.tab-btn').forEach(tab => {
    tab.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');

        // Remove active class from all tabs and contents
        document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        // Add active class to clicked tab and corresponding content
        this.classList.add('active');
        document.getElementById(targetTab).classList.add('active');
    });
});

</script>