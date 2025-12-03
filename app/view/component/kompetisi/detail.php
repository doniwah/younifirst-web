<div class="competition-detail-container" style="max-width: 1000px; margin: 40px auto; padding: 20px;">
    <div class="back-button" style="margin-bottom: 20px;">
        <a href="/kompetisi" style="text-decoration: none; color: #666; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Kompetisi
        </a>
    </div>

    <div class="detail-card" style="background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05);">
        <!-- Hero Image -->
        <div class="detail-hero" style="height: 300px; background: #f8f9fa; position: relative; overflow: hidden;">
            <?php if (!empty($competition['poster_lomba'])): ?>
                <img src="<?= htmlspecialchars($competition['poster_lomba']) ?>" alt="<?= htmlspecialchars($competition['nama_lomba']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
            <?php else: ?>
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(45deg, #4f87ff, #2d5bff); color: white;">
                    <i class="bi bi-trophy" style="font-size: 5rem; opacity: 0.5;"></i>
                </div>
            <?php endif; ?>
            
            <div class="status-badge" style="position: absolute; top: 20px; right: 20px; background: rgba(255,255,255,0.9); padding: 8px 16px; border-radius: 50px; font-weight: 600; color: #4f87ff; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <?= htmlspecialchars($competition['status'] ?? 'Open') ?>
            </div>
        </div>

        <div class="detail-content" style="padding: 40px;">
            <div class="row" style="display: flex; gap: 40px; flex-wrap: wrap;">
                <!-- Main Info -->
                <div class="col-main" style="flex: 2; min-width: 300px;">
                    <h1 style="font-size: 2.5rem; margin-bottom: 15px; color: #333;"><?= htmlspecialchars($competition['nama_lomba']) ?></h1>
                    
                    <div class="meta-tags" style="display: flex; gap: 10px; margin-bottom: 30px; flex-wrap: wrap;">
                        <span class="tag" style="background: #eef2ff; color: #4f87ff; padding: 6px 12px; border-radius: 6px; font-size: 0.9rem;">
                            <i class="bi bi-tag"></i> <?= htmlspecialchars($competition['kategori'] ?? 'Umum') ?>
                        </span>
                        <span class="tag" style="background: #f0fdf4; color: #16a34a; padding: 6px 12px; border-radius: 6px; font-size: 0.9rem;">
                            <i class="bi bi-cash"></i> <?= ($competition['biaya'] ?? 'Gratis') === 'Gratis' ? 'Gratis' : 'Rp ' . number_format($competition['harga_lomba'] ?? 0, 0, ',', '.') ?>
                        </span>
                        <span class="tag" style="background: #fefce8; color: #ca8a04; padding: 6px 12px; border-radius: 6px; font-size: 0.9rem;">
                            <i class="bi bi-globe"></i> <?= htmlspecialchars($competition['scope'] ?? 'Nasional') ?>
                        </span>
                    </div>

                    <div class="description-section" style="margin-bottom: 40px;">
                        <h3 style="font-size: 1.2rem; margin-bottom: 15px; color: #444;">Deskripsi</h3>
                        <div class="description-text" style="color: #666; line-height: 1.8;">
                            <?= nl2br(htmlspecialchars($competition['deskripsi'])) ?>
                        </div>
                    </div>

                    <div class="organizer-section" style="padding: 20px; background: #f8f9fa; border-radius: 12px; display: flex; align-items: center; gap: 15px;">
                        <div class="org-icon" style="width: 50px; height: 50px; background: #eef2ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #4f87ff; font-size: 1.5rem;">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <div style="font-size: 0.9rem; color: #888;">Diselenggarakan oleh</div>
                            <div style="font-weight: 600; color: #333; font-size: 1.1rem;"><?= htmlspecialchars($competition['penyelenggara'] ?? 'Panitia Lomba') ?></div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Info -->
                <div class="col-sidebar" style="flex: 1; min-width: 250px;">
                    <div class="info-box" style="background: #fff; border: 1px solid #eee; border-radius: 12px; padding: 25px;">
                        <h3 style="font-size: 1.1rem; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px;">Informasi Penting</h3>
                        
                        <div class="info-item" style="margin-bottom: 20px;">
                            <div style="color: #888; font-size: 0.9rem; margin-bottom: 5px;">Tanggal Lomba</div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #333; font-weight: 500;">
                                <i class="bi bi-calendar-event" style="color: #4f87ff;"></i>
                                <?= date('d F Y', strtotime($competition['tanggal_lomba'])) ?>
                            </div>
                        </div>

                        <div class="info-item" style="margin-bottom: 20px;">
                            <div style="color: #888; font-size: 0.9rem; margin-bottom: 5px;">Lokasi</div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #333; font-weight: 500;">
                                <i class="bi bi-geo-alt" style="color: #4f87ff;"></i>
                                <?= htmlspecialchars($competition['lokasi'] ?? 'Online') ?>
                            </div>
                        </div>

                        <div class="info-item" style="margin-bottom: 20px;">
                            <div style="color: #888; font-size: 0.9rem; margin-bottom: 5px;">Total Hadiah</div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #333; font-weight: 500;">
                                <i class="bi bi-trophy" style="color: #fbbf24;"></i>
                                <?= is_numeric($competition['hadiah']) ? 'Rp ' . number_format($competition['hadiah'], 0, ',', '.') : htmlspecialchars($competition['hadiah']) ?>
                            </div>
                        </div>

                        <div class="info-item" style="margin-bottom: 25px;">
                            <div style="color: #888; font-size: 0.9rem; margin-bottom: 5px;">Tipe Peserta</div>
                            <div style="display: flex; align-items: center; gap: 10px; color: #333; font-weight: 500;">
                                <i class="bi bi-people" style="color: #4f87ff;"></i>
                                <?= htmlspecialchars($competition['lomba_type'] ?? 'Individu/Tim') ?>
                            </div>
                        </div>

                        <button class="btn-register" style="width: 100%; background: #4f87ff; color: white; border: none; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                            Daftar Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-register:hover {
        background: #3a72ec !important;
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
