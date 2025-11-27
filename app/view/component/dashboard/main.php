<section id="hero">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <p>Selamat datang di Campus Nexus Grid Admin Panel</p>
    </div>

    <!-- Stats Cards Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon-box blue">
                <i class="bi bi-calendar4"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Events</div>
                <div class="stat-number"><?= htmlspecialchars($stat_event ?? '45') ?></div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> +12% dari bulan lalu
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box green">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Active Teams</div>
                <div class="stat-number">23</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> +5% dari bulan lalu
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box orange">
                <i class="bi bi-chat-dots"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Forum Posts</div>
                <div class="stat-number">156</div>
                <div class="stat-change positive">
                    <i class="bi bi-arrow-up"></i> +18% dari bulan lalu
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon-box red">
                <i class="bi bi-search"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Lost Items</div>
                <div class="stat-number"><?= htmlspecialchars($stat_lost ?? '8') ?></div>
                <div class="stat-change negative">
                    <i class="bi bi-arrow-down"></i> -2% dari bulan lalu
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid: Events and Team Search -->
    <div class="content-grid-two">
        <!-- Event Terbaru Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-title-group">
                    <h2><i class="bi bi-calendar4"></i> Event Terbaru</h2>
                    <p class="section-subtitle">Event yang akan datang dan sedang berlangsung</p>
                </div>
            </div>

            <div class="events-list">
                <?php
                $sample_events = [
                    [
                        'nama_event' => 'Workshop React JS untuk Pemula',
                        'organizer' => 'Himpunan Informatika',
                        'tanggal_mulai' => '2024-01-15',
                        'waktu' => '14:00',
                        'peserta' => '45',
                        'status' => 'Upcoming'
                    ],
                    [
                        'nama_event' => 'Seminar Digital Marketing',
                        'organizer' => 'BEM Fakultas Ekonomi',
                        'tanggal_mulai' => '2024-01-18',
                        'waktu' => '09:00',
                        'peserta' => '67',
                        'status' => 'Upcoming'
                    ],
                    [
                        'nama_event' => 'Lomba Programming Competition',
                        'organizer' => 'UKM Programming Club',
                        'tanggal_mulai' => '2024-01-20',
                        'waktu' => '08:00',
                        'peserta' => '89',
                        'status' => 'Registrasi'
                    ]
                ];

                $events_to_show = $events_latest ?? $sample_events;
                foreach ($events_to_show as $e):
                ?>
                <div class="event-item-row">
                    <div class="event-info">
                        <div class="event-title-row"><?= htmlspecialchars($e['nama_event']) ?></div>
                        <div class="event-meta">
                            <?= htmlspecialchars($e['organizer'] ?? 'Organizer') ?>
                        </div>
                        <div class="event-details">
                            <span><i class="bi bi-calendar4"></i> <?= htmlspecialchars($e['tanggal_mulai']) ?> •
                                <?= htmlspecialchars($e['waktu'] ?? '00:00') ?></span>
                            <span><i class="bi bi-people"></i> <?= htmlspecialchars($e['peserta'] ?? '0') ?>
                                peserta</span>
                        </div>
                    </div>
                    <div class="event-status">
                        <span class="badge-status <?= strtolower($e['status'] ?? 'upcoming') ?>">
                            <?= htmlspecialchars($e['status'] ?? 'Upcoming') ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="section-footer">
                <a href="#" class="view-all-link">
                    <i class="bi bi-eye"></i> Lihat Semua Event
                </a>
            </div>
        </div>

        <!-- Pencarian Tim Aktif Section -->
        <div class="section-card">
            <div class="section-header">
                <div class="section-title-group">
                    <h2><i class="bi bi-people"></i> Pencarian Tim Aktif</h2>
                    <p class="section-subtitle">Mahasiswa yang sedang mencari anggota tim</p>
                </div>
            </div>

            <div class="team-search-list">
                <?php
                $sample_teams = [
                    [
                        'nama_tim' => 'Tim Lomba Mobile App Development',
                        'leader' => 'Ahmad Ridwan (Informatika)',
                        'skills' => ['Flutter', 'UI/UX Design'],
                        'butuh' => '2 orang',
                        'deadline' => '2024-01-25'
                    ],
                    [
                        'nama_tim' => 'Tim Business Plan Competition',
                        'leader' => 'Sarah Putri (Manajemen)',
                        'skills' => ['Business Analysis', 'Financial Modeling'],
                        'butuh' => '1 orang',
                        'deadline' => '2024-01-30'
                    ]
                ];

                $teams_to_show = $teams_latest ?? $sample_teams;
                foreach ($teams_to_show as $t):
                ?>
                <div class="team-item">
                    <div class="team-info">
                        <div class="team-title"><?= htmlspecialchars($t['nama_tim']) ?></div>
                        <div class="team-leader"><?= htmlspecialchars($t['leader']) ?></div>
                        <div class="team-skills">
                            <?php foreach ($t['skills'] as $skill): ?>
                            <span class="skill-tag"><?= htmlspecialchars($skill) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="team-deadline">
                            Deadline: <?= htmlspecialchars($t['deadline']) ?>
                        </div>
                    </div>
                    <div class="team-need">
                        <div class="need-badge">Butuh <?= htmlspecialchars($t['butuh']) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="section-footer">
                <a href="#" class="view-all-link">
                    <i class="bi bi-eye"></i> Lihat Semua Pencarian
                </a>
            </div>
        </div>
    </div>
</section>