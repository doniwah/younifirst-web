    <section id="hero">
        <div class="dashboard-header">
            <h1>Dashboard</h1>
            <p>Selamat datang kembali! Lihat update terbaru dari komunitas.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card trophy">
                <div class="stat-info">
                    <h3>Kompetisi Aktif</h3>
                    <div class="number"><?= htmlspecialchars($stat_kompetisi) ?></div>
                </div>
                <div class="stat-icon"><i class="bi bi-trophy" style="font-size: 20px; color: #0f4174"></i></div>
            </div>

            <div class="stat-card package">
                <div class="stat-info">
                    <h3>Barang Hilang</h3>
                    <div class="number"><?= htmlspecialchars($stat_lost) ?></div>
                </div>
                <div class="stat-icon"><i class="bi bi-box-seam" style="font-size: 20px; color: #0f4174"></i></div>
            </div>

            <div class="stat-card calendar">
                <div class="stat-info">
                    <h3>Event Mendatang</h3>
                    <div class="number"><?= htmlspecialchars($stat_event) ?></div>
                </div>
                <div class="stat-icon"><i class="bi bi-calendar4" style="font-size: 20px; color: #2b99d0"></i></div>
            </div>
        </div>

        <div class="content-grid">
            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <div>
                            <h2 class="h-1"><i class="bi bi-trophy"
                                    style="font-size: 18px; margin-right: 10px;"></i>Kompetisi
                                Terbaru</h2>
                            <div class="section-subtitle">Lomba yang sedang dibuka pendaftaran</div>
                        </div>
                    </div>
                    <a href="#" class="view-all">Lihat Semua <i class="bi bi-arrow-right-short"
                            style="font-size: 24px;"></i></a>
                </div>
                <?php foreach ($kompetisi_latest as $k): ?>
                    <div class="item">
                        <div class="item-header">
                            <div>
                                <div class="item-title"><?= htmlspecialchars($k['nama_lomba']) ?></div>
                                <div class="item-meta">
                                    <span
                                        style="background:white; border:1px solid #ddd; padding:2px 10px; border-radius:20px; font-size:12px;">
                                        <?= htmlspecialchars($k['kategori']) ?>
                                    </span>
                                    <span><i class="bi bi-clock"></i> <?= htmlspecialchars($k['tanggal_lomba']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="section-card">
                <div class="section-header">
                    <div class="section-title">
                        <div>
                            <h2 class="h-2"><i class="bi bi-box-seam"
                                    style="font-size: 18px; margin-right: 10px;"></i>Lost & Found
                                Terbaru</h2>
                            <div class="section-subtitle">Barang hilang dan ditemukan</div>
                        </div>
                    </div>
                    <a href="#" class="view-all">Lihat Semua <i class="bi bi-arrow-right-short"
                            style="font-size: 24px;"></i></a>
                </div>

                <?php foreach ($lost_latest as $l): ?>
                    <div class="item">
                        <div class="item-header">
                            <div>
                                <div class="item-title"><?= htmlspecialchars($l['nama_barang']) ?></div>
                                <div class="item-meta">
                                    <span><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($l['lokasi']) ?></span>
                                    <span><i class="bi bi-clock"></i> <?= htmlspecialchars($l['tanggal']) ?></span>
                                </div>
                            </div>
                            <span class="badge <?= $l['kategori'] === 'hilang' ? 'missing' : 'found' ?>">
                                <?= ucfirst($l['kategori']) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="event-full">
            <div class="section-header">
                <div class="section-title">
                    <div>
                        <h2 class="event-h2"><i class="bi bi-calendar4"
                                style="font-size: 18px; font-weight: 600; color: #2b99d0"></i>
                            Event Mendatang
                        </h2>
                        <div class="section-subtitle">Acara yang akan segera berlangsung</div>
                    </div>
                </div>
                <a href="#" class="view-all">Lihat Semua <i class="bi bi-arrow-right-short"
                        style="font-size: 24px;"></i></a>
            </div>

            <div class="event-grid">
                <?php foreach ($events_latest as $e): ?>
                    <div class="event-item">
                        <div class="event-title"><?= htmlspecialchars($e['nama_event']) ?></div>
                        <div class="event-detail"><i class="bi bi-calendar4"></i>
                            <?= htmlspecialchars($e['tanggal_mulai']) ?></div>
                        <div class="event-detail"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($e['lokasi']) ?></div>
                        <button class="register-btn">Daftar Sekarang</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>