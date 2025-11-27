<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Campus Nexus</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/css/dashboard.css">
    <link rel="stylesheet" href="/css/sidebar.css">
</head>

<body>
    <?php require_once __DIR__ . "/../../layouts/sidebar.php"; ?>


    <div class="main-content">
        <div class="header">
            <h1>Dashboard</h1>
            <p>Selamat datang di Campus Nexus Grid Admin Panel</p>
        </div>


        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Events</span>
                    <div class="stat-icon icon-blue">
                        <i class="far fa-calendar"></i>
                    </div>
                </div>
                <div class="stat-value">45</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+12% dari bulan lalu</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Active Teams</span>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
                <div class="stat-value">23</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+5% dari bulan lalu</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Forum Posts</span>
                    <div class="stat-icon icon-orange">
                        <i class="far fa-comment"></i>
                    </div>
                </div>
                <div class="stat-value">156</div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+18% dari bulan lalu</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Lost Items</span>
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <div class="stat-value">8</div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>-2% dari bulan lalu</span>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Event Terbaru -->
            <div class="content-card">
                <div class="card-header">
                    <i class="far fa-calendar"></i>
                    <div>
                        <h2>Event Terbaru</h2>
                        <p class="card-subtitle">Event yang akan datang dan sedang berlangsung</p>
                    </div>
                </div>

                <div class="event-item">
                    <div class="event-header">
                        <div>
                            <div class="event-title">Workshop React JS untuk Pemula</div>
                            <div class="event-organizer">Himpunan Informatika</div>
                        </div>
                        <span class="badge badge-upcoming">Upcoming</span>
                    </div>
                    <div class="event-details">
                        <div class="event-detail">
                            <i class="far fa-clock"></i>
                            <span>2024-01-15 • 14:00</span>
                        </div>
                        <div class="event-detail">
                            <i class="fas fa-users"></i>
                            <span>45 peserta</span>
                        </div>
                    </div>
                </div>

                <div class="event-item">
                    <div class="event-header">
                        <div>
                            <div class="event-title">Seminar Digital Marketing</div>
                            <div class="event-organizer">BEM Fakultas Ekonomi</div>
                        </div>
                        <span class="badge badge-upcoming">Upcoming</span>
                    </div>
                    <div class="event-details">
                        <div class="event-detail">
                            <i class="far fa-clock"></i>
                            <span>2024-01-18 • 09:00</span>
                        </div>
                        <div class="event-detail">
                            <i class="fas fa-users"></i>
                            <span>67 peserta</span>
                        </div>
                    </div>
                </div>

                <div class="event-item">
                    <div class="event-header">
                        <div>
                            <div class="event-title">Lomba Programming Competition</div>
                            <div class="event-organizer">UKM Programming Club</div>
                        </div>
                        <span class="badge badge-registration">Registrasi</span>
                    </div>
                    <div class="event-details">
                        <div class="event-detail">
                            <i class="far fa-clock"></i>
                            <span>2024-01-20 • 08:00</span>
                        </div>
                        <div class="event-detail">
                            <i class="fas fa-users"></i>
                            <span>89 peserta</span>
                        </div>
                    </div>
                </div>

                <div class="view-all">
                    <i class="fas fa-eye"></i>
                    <span>Lihat Semua Event</span>
                </div>
            </div>

            <!-- Pencarian Tim Aktif -->
            <div class="content-card">
                <div class="card-header">
                    <i class="fas fa-users"></i>
                    <div>
                        <h2>Pencarian Tim Aktif</h2>
                        <p class="card-subtitle">Mahasiswa yang sedang mencari anggota tim</p>
                    </div>
                </div>

                <div class="team-item">
                    <div class="team-header">
                        <div>
                            <div class="team-title">Tim Lomba Mobile App Development</div>
                            <div class="team-creator">Ahmad Ridwan (Informatika)</div>
                        </div>
                        <span class="badge badge-upcoming">Aktif</span>
                    </div>
                    <div class="team-tags">
                        <span class="tag">Flutter</span>
                        <span class="tag">UI/UX Design</span>
                    </div>
                    <div class="team-footer">
                        <span>Deadline: 2024-01-25</span>
                        <span>Butuh 2 orang</span>
                    </div>
                </div>

                <div class="team-item">
                    <div class="team-header">
                        <div>
                            <div class="team-title">Tim Business Plan Competition</div>
                            <div class="team-creator">Sarah Putri (Manajemen)</div>
                        </div>
                        <span class="badge badge-upcoming">Aktif</span>
                    </div>
                    <div class="team-tags">
                        <span class="tag">Business Analysis</span>
                        <span class="tag">Financial Modeling</span>
                    </div>
                    <div class="team-footer">
                        <span>Deadline: 2024-01-30</span>
                        <span>Butuh 1 orang</span>
                    </div>
                </div>

                <div class="view-all">
                    <i class="fas fa-eye"></i>
                    <span>Lihat Semua Pencarian</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>