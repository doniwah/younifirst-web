<div class="container">
    <div class="header">
        <h1>Kompetisi</h1>
        <p>Temukan lomba dan tim untuk berkompetisi bersama</p>
    </div>

    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Cari kompetisi atau tim...">
    </div>

    <div class="tabs">
        <button class="tab active" data-tab="daftar-lomba">Daftar Lomba</button>
        <button class="tab" data-tab="cari-tim">Cari Tim</button>
    </div>

    <!-- DAFTAR LOMBA SECTION -->
    <div id="daftar-lomba" class="tab-content active">
        <div class="top-section">
            <button class="btn-posting">Posting Lomba</button>
        </div>

        <div class="competitions-grid">
            <div class="competition-card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="trophy-icon"><i class="bi bi-trophy"></i></span>
                        <h3>Hackathon Nasional 2024</h3>
                    </div>
                    <span class="category-badge badge-technology">Technology</span>
                </div>
                <p class="card-description">Kompetisi pengembangan aplikasi dengan tema Smart City</p>
                <div class="card-details">
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Deadline: 31 Desember 2024</span>
                    </div>
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Jakarta Convention Center</span>
                    </div>
                    <div class="detail-item">
                        <span class="trophy-icon" style="font-size: 1rem;"><i class="bi bi-trophy"></i></span>
                        <span class="prize-amount">Rp 50.000.000</span>
                    </div>
                </div>
                <button class="btn-detail">Lihat Detail</button>
            </div>

            <div class="competition-card">
                <div class="card-header">
                    <div class="card-title">
                        <span class="trophy-icon"><i class="bi bi-trophy"></i></span>
                        <h3>Business Plan Competition</h3>
                    </div>
                    <span class="category-badge badge-business">Business</span>
                </div>
                <p class="card-description">Lomba rencana bisnis untuk startup inovatif</p>
                <div class="card-details">
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Deadline: 25 Desember 2024</span>
                    </div>
                    <div class="detail-item">
                        <svg class="detail-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Online</span>
                    </div>
                    <div class="detail-item">
                        <span class="trophy-icon" style="font-size: 1rem;"><i class="bi bi-trophy"></i></span>
                        <span class="prize-amount">Rp 30.000.000</span>
                    </div>
                </div>
                <button class="btn-detail">Lihat Detail</button>
            </div>
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