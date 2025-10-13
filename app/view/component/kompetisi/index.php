<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kompetisi - CampusHub</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: white;
            margin-top: 40px;
            color: #333;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 2.5rem;
            color: #0a1f44;
            margin-bottom: 8px;
        }

        .header p {
            color: #6c757d;
            font-size: 1rem;
        }

        .search-box {
            margin: 30px 0;
        }

        .search-box input {
            width: 100%;
            padding: 8px 13px;
            padding-left: 50px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="%23999" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>');
            background-repeat: no-repeat;
            background-position: 15px center;
        }

        .search-box input:focus {
            outline: none;
            border-color: #0a1f44;
        }

        .tabs {
            display: flex;
            gap: 0;
            margin-bottom: 30px;
            background-color: white;
            border-radius: 12px;
            padding: 8px;
            width: 400px;
            border: 1px solid #ddd;
        }

        .tab {
            flex: 1;
            padding: 12px 30px;
            background: none;
            border: none;
            font-size: 1rem;
            color: #6c757d;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s;
            font-weight: 500;
        }

        .tab.active {
            color: #0a1f44;
            font-weight: 600;
            background-color: #e6ebf0;
        }

        .top-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
        }

        .btn-posting {
            background-color: #0a1f44;
            color: white;
            padding: 8px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.3s;
        }

        .btn-posting:hover {
            background-color: #162e5a;
        }

        .btn-posting::before {
            content: '+';
            font-size: 1.5rem;
            font-weight: 300;
        }

        /* Section visibility */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Daftar Lomba Styles */
        .competitions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        .competition-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #ddd;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .competition-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .card-title {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .trophy-icon {
            font-size: 1.5rem;
        }

        .card-title h3 {
            font-size: 1.4rem;
            color: #0a1f44;
            font-weight: 600;
        }

        .category-badge {
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            white-space: nowrap;
        }

        .badge-technology {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .badge-business {
            background-color: #f3e5f5;
            color: #7b1fa2;
        }

        .card-description {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .card-details {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #495057;
            font-size: 0.95rem;
        }

        .detail-icon {
            width: 20px;
            color: #6c757d;
        }

        .prize-amount {
            color: #0a1f44;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .btn-detail {
            width: 100%;
            padding: 14px;
            background-color: #0a1f44;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-detail:hover {
            background-color: #162e5a;
        }

        /* Cari Tim Styles */
        .teams-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }

        .team-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            border: 1px solid #ddd;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .team-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
        }

        .team-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .team-title {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .team-icon {
            font-size: 1.5rem;
            color: #0a1f44;
        }

        .team-title h3 {
            font-size: 1.4rem;
            color: #0a1f44;
            font-weight: 600;
        }

        .member-count {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .competition-name {
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .team-description {
            color: #495057;
            font-size: 0.95rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .roles-section {
            margin-bottom: 20px;
        }

        .roles-title {
            font-weight: 600;
            color: #0a1f44;
            font-size: 0.95rem;
            margin-bottom: 10px;
        }

        .roles-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .role-badge {
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            background-color: #0a1f44;
            color: white;
        }

        .btn-join {
            width: 100%;
            padding: 14px;
            background-color: #0a1f44;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-join:hover {
            background-color: #162e5a;
        }

        @media (max-width: 768px) {

            .competitions-grid,
            .teams-grid {
                grid-template-columns: 1fr;
            }

            .header h1 {
                font-size: 2rem;
            }

            .tabs {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                width: 100%;
            }

            .tab {
                white-space: nowrap;
            }
        }
    </style>
</head>

<body>
    <?php
    require_once __DIR__ . "/../../layouts/navbar.php";
    ?>
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

    <script>
        // Tab switching functionality
        const tabs = document.querySelectorAll('.tab');
        const tabContents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                tabs.forEach(t => t.classList.remove('active'));

                // Add active class to clicked tab
                this.classList.add('active');

                // Hide all tab contents
                tabContents.forEach(content => content.classList.remove('active'));

                // Show the selected tab content
                const targetTab = this.getAttribute('data-tab');
                document.getElementById(targetTab).classList.add('active');

                // Update search placeholder
                const searchInput = document.getElementById('searchInput');
                if (targetTab === 'daftar-lomba') {
                    searchInput.placeholder = 'Cari kompetisi atau tim...';
                } else {
                    searchInput.placeholder = 'Cari tim...';
                }
            });
        });

        // Button functionality for Daftar Lomba
        document.querySelectorAll('.btn-posting').forEach(btn => {
            btn.addEventListener('click', function() {
                const activeTab = document.querySelector('.tab.active').getAttribute('data-tab');
                if (activeTab === 'daftar-lomba') {
                    alert('Fitur Posting Lomba');
                } else {
                    alert('Fitur Buat Tim');
                }
            });
        });

        document.querySelectorAll('.btn-detail').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Menampilkan detail kompetisi');
            });
        });

        document.querySelectorAll('.btn-join').forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Ajukan bergabung dengan tim');
            });
        });
    </script>
</body>

</html>