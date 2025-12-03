<div class="team-detail-container" style="max-width: 1000px; margin: 40px auto; padding: 20px;">
    <div class="back-button" style="margin-bottom: 20px;">
        <a href="/kompetisi" style="text-decoration: none; color: #666; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Tim
        </a>
    </div>

    <div class="detail-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <!-- Hero Image -->
        <div class="detail-hero" style="height: 250px; background: #f8f9fa; position: relative; overflow: hidden;">
            <?php if (!empty($team['poster_lomba'])): ?>
                <img src="<?= htmlspecialchars($team['poster_lomba']) ?>" alt="<?= htmlspecialchars($team['nama_team']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(45deg, #10b981, #059669); color: white;">
                    <i class="bi bi-people-fill" style="font-size: 5rem; opacity: 0.5;"></i>
                </div>
            <?php endif; ?>
            
            <div class="status-badge" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.9); padding: 8px 16px; border-radius: 50px; font-weight: 600; color: #10b981; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <?= htmlspecialchars($team['status'] ?? 'Open') ?>
            </div>
        </div>

        <div class="detail-content" style="padding: 40px;">
            <div class="row" style="display: flex; gap: 40px; flex-wrap: wrap;">
                <!-- Main Info -->
                <div class="col-main" style="flex: 2; min-width: 300px;">
                    <h1 style="font-size: 2.5rem; margin-bottom: 10px; color: #333;"><?= htmlspecialchars($team['nama_team']) ?></h1>
                    <h2 style="font-size: 1.2rem; color: #666; margin-bottom: 20px; font-weight: 500;">
                        <i class="bi bi-flag"></i> <?= htmlspecialchars($team['nama_kegiatan']) ?>
                    </h2>
                    
                    <div class="meta-tags" style="display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap;">
                        <span class="tag" style="background: #eef2ff; color: #4f87ff; padding: 6px 12px; border-radius: 6px; font-size: 0.9rem;">
                            <i class="bi bi-people"></i> Max <?= htmlspecialchars($team['max_anggota']) ?> Anggota
                        </span>
                    </div>

                    <div class="description-section" style="margin-bottom: 40px;">
                        <h3 style="font-size: 1.2rem; margin-bottom: 15px; color: #444;">Tentang Tim</h3>
                        <div class="description-text" style="color: #666; line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($team['deskripsi_anggota'])) ?>
                        </div>
                    </div>

                    <div class="roles-section" style="margin-bottom: 40px;">
                        <h3 style="font-size: 1.2rem; margin-bottom: 15px; color: #444;">Role yang Dibutuhkan</h3>
                        <div class="roles-list" style="display: flex; gap: 10px; flex-wrap: wrap;">
                            <?php 
                            $roles = explode(',', $team['role_required']);
                            foreach($roles as $role): 
                            ?>
                            <span class="role-badge" style="background: #f3f4f6; color: #374151; padding: 8px 16px; border-radius: 50px; font-size: 0.9rem; border: 1px solid #e5e7eb;">
                                <?= htmlspecialchars(trim($role)) ?>
                            </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-sidebar" style="flex: 1; min-width: 250px;">
                    <div class="info-box" style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 25px;">
                        <h3 style="font-size: 1.1rem; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">Bergabung dengan Tim</h3>
                        
                        <div class="info-item" style="margin-bottom: 20px;">
                            <div style="color: #888; font-size: 0.9rem; margin-bottom: 5px;">Status Rekrutmen</div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #10b981; font-weight: 500;">
                                <i class="bi bi-check-circle-fill"></i>
                                <?= htmlspecialchars($team['status'] ?? 'Open') ?>
                            </div>
                        </div>

                        <button class="btn-join" onclick="openJoinModal('<?= $team['team_id'] ?>', '<?= htmlspecialchars($team['nama_team']) ?>', <?= $team['max_anggota'] ?>)" style="width: 100%; background: #10b981; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                            Ajukan Bergabung
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-join:hover {
        background: #059669 !important;
    }
    @media (max-width: 768px) {
        .detail-hero {
            height: 200px !important;
        }
        .detail-content {
            padding: 20px !important;
        }
    }
</style>

<!-- Re-use the join modal from main page or include it here -->
<!-- Ideally, we should have a shared partial for the modal, but for now we rely on the layout or duplicate if needed. -->
<!-- Assuming the layout includes the modal or we need to add it here. -->
<!-- Adding modal here just in case -->

<div id="joinModal" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5);">
    <div class="modal-content" style="background-color: #fefefe; margin: 10% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 600px; border-radius: 12px;">
        <span class="close" onclick="closeJoinModal()" style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;">&times;</span>
        <h2 class="title_pop" style="margin-top: 0;">Ajukan Bergabung ke <span id="teamNameDisplay"></span></h2>
        <p class="deskripsi_pop">Isi form berikut untuk mengajukan diri bergabung dengan tim</p>

        <form action="/team/request" method="POST" id="joinForm">
            <input type="hidden" name="team_id" id="joinTeamId">

            <div style="background: #f8f9fa; padding: 12px; border-radius: 8px; margin-bottom: 15px;">
                <p style="margin: 0; font-size: 14px;"><strong>Anggota Saat Ini:</strong> <span id="joinMemberCount"></span> orang</p>
            </div>

            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Role yang Diminati <span style="color: red;">*</span></label>
            <select name="role_diminta" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 15px;">
                <option value="">Pilih Role</option>
                <option value="ketua">Ketua</option>
                <option value="anggota">Anggota</option>
            </select>

            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Alasan Bergabung <span style="color: red;">*</span></label>
            <textarea name="alasan_bergabung" placeholder="Ceritakan mengapa kamu ingin bergabung dengan tim ini..." rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 15px;"></textarea>

            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Keahlian & Pengalaman <span style="color: red;">*</span></label>
            <textarea name="keahlian_pengalaman" placeholder="Jelaskan keahlian dan pengalaman yang relevan..." rows="4" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 15px;"></textarea>

            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Link Portfolio/Project (Optional)</label>
            <input type="url" name="portfolio_link" placeholder="https://github.com/username atau portfolio link" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 15px;">

            <label style="display: block; margin-bottom: 8px; font-weight: 500;">Kontak (Email/WhatsApp) <span style="color: red;">*</span></label>
            <input type="text" name="kontak" placeholder="email@example.com atau 08123456789" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; margin-bottom: 15px;">

            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="closeJoinModal()" style="flex: 1; padding: 12px; background: #6c757d; color: white; border: none; border-radius: 8px; cursor: pointer;">Batal</button>
                <button type="submit" class="submit-btn" style="flex: 1; margin: 0; background: #4f87ff; color: white; border: none; padding: 12px; border-radius: 8px; cursor: pointer;">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>

<script>
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

window.onclick = function(event) {
    if (event.target == joinModal) {
        closeJoinModal();
    }
}
</script>
