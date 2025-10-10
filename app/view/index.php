<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouNiFirst - Platform Komunitas Kampus</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

</head>

<body>
    <?php
    require_once __DIR__ . "/layouts/navbar.php";
    ?>

    <section class="hero">
        <h1>Terhubung, Berkompetisi, Berkembang Bersama</h1>
        <p>YouNiFirst adalah platform all-in-one untuk mahasiswa. Temukan lomba, tim, event, dan lebih banyak lagi dalam
            satu tempat.</p>
        <div class="cta-buttons">
            <button class="btn-primary">Mulai Sekarang</button>
            <button class="btn-secondary">Pelajari Lebih Lanjut</button>
        </div>
    </section>

    <section class="features">
        <h2>Fitur Lengkap untuk Mahasiswa</h2>
        <p class="features-subtitle">Semua yang kamu butuhkan untuk berkembang di kampus, dalam satu platform <br>yang
            mudah
            digunakan</p>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon icon-purple"><i class="bi bi-trophy"
                        style="color: #ffffff; font-size: 20px;"></i></div>
                <h3>Kompetisi</h3>
                <p>Posting lomba atau buat tim untuk berkompetensi bersama</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon icon-pink"><i class="bi bi-box-seam"
                        style="color: #ffffff; font-size: 20px;"></i></div>
                <h3>Lost & Found</h3>
                <p>Temukan atau laporkan barang hilang dengan mudah</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon icon-orange"><i class="bi bi-calendar4"
                        style="color: #ffffff; font-size: 20px;"></i></div>
                <h3>Event</h3>
                <p>Posting dan daftar event kampus yang menarik</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon icon-blue"><i class="bi bi-chat-left"
                        style="color: #ffffff; font-size: 20px;"></i></div>
                <h3>Forum</h3>
                <p>Diskusi dan berbagi informasi dengan komunitas</p>
            </div>
        </div>
    </section>

    <section class="why-section">
        <div class="why-content">
            <h2>Mengapa Memilih <br>YouNiFirst?</h2>

            <div class="why-item">
                <div class="why-icon icon-people"><i class="bi bi-people"></i></div>
                <div>
                    <h3>Komunitas Aktif</h3>
                    <p>Bergabung dengan ribuan mahasiswa yang aktif berbagi dan berkolaborasi</p>
                </div>
            </div>

            <div class="why-item">
                <div class="why-icon icon-shield"><i class="bi bi-shield"></i></div>
                <div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Platform dengan sistem keamanan terjamin untuk melindungi data kamu</p>
                </div>
            </div>

            <div class="why-item">
                <div class="why-icon icon-calendar"><i class="bi bi-calendar4"></i></div>
                <div>
                    <h3>Update Berkala</h3>
                    <p>Dapatkan informasi terbaru tentang lomba, event, dan aktivitas kampus</p>
                </div>
            </div>
        </div>

        <div class="stats-card">
            <div class="stats-number">1000+</div>
            <div class="stats-label">Pengguna Aktif</div>
            <p class="stats-description">Bergabung dengan komunitas yang terus berkembang</p>
        </div>
    </section>

    <section class="testimonial-section">
        <div class="section-header">
            <h2>Apa Kata Mahasiswa?</h2>
            <p>Dengarkan pengalaman dari pengguna YouNiFirst lainnya</p>
        </div>

        <div class="testimonial-grid">
            <div class="testimonial-card">
                <div class="card-header">
                    <div class="avatar blue1">AM</div>
                    <div class="user-info">
                        <h3>Ahmad Maulana</h3>
                        <p>Teknologi Informasi</p>
                    </div>
                </div>
                <div class="testimonial-text">
                    "YouNiFirst sangat membantu saya menemukan tim untuk kompetisi. Sekarang lebih mudah berkolaborasi
                    dengan mahasiswa lain!"
                </div>
            </div>

            <div class="testimonial-card">
                <div class="card-header">
                    <div class="avatar blue2">SP</div>
                    <div class="user-info">
                        <h3>Siti Permata</h3>
                        <p>Sistem Informasi</p>
                    </div>
                </div>
                <div class="testimonial-text">
                    "Fitur Lost & Found-nya sangat berguna! Saya berhasil menemukan dompet yang hilang dalam 2 hari."
                </div>
            </div>

            <div class="testimonial-card">
                <div class="card-header">
                    <div class="avatar blue3">RP</div>
                    <div class="user-info">
                        <h3>Rizki Pratama</h3>
                        <p>Teknik Elektro</p>
                    </div>
                </div>
                <div class="testimonial-text">
                    "Platform yang lengkap dan mudah digunakan. Forum diskusinya aktif dan membantu dalam belajar
                    kelompok."
                </div>
            </div>
        </div>
    </section>

    <section class="faq-section">
        <div class="section-header">
            <h2>Pertanyaan Yang Sering Diajukan</h2>
            <p>Temukan jawaban untuk pertanyaan umum tentang YouNiFirst</p>
        </div>

        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">Apakah YouNiFirst gratis?</div>
                <div class="faq-answer">
                    Ya, YouNiFirst sepenuhnya gratis untuk semua mahasiswa. Anda dapat mengakses semua fitur tanpa biaya
                    apapun.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Bagaimana cara bergabung dengan tim kompetisi?</div>
                <div class="faq-answer">
                    Buka halaman Kompetisi, cari tim yang sesuai dengan minat Anda, lalu klik tombol "Daftar". Pembuat
                    tim akan meninjau aplikasi Anda.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Siapa yang bisa melihat postingan saya di forum?</div>
                <div class="faq-answer">
                    Untuk forum jurusan, hanya mahasiswa dari jurusan yang sama yang dapat melihat. Forum global dapat
                    dilihat oleh semua pengguna.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">Bagaimana cara melaporkan barang hilang?</div>
                <div class="faq-answer">
                    Kunjungi halaman Lost & Found, klik tombol "Laporkan", pilih kategori "Kehilangan", lalu isi detail
                    barang yang hilang dengan lengkap.
                </div>
            </div>
        </div>
    </section>

    <?php
    require_once __DIR__ . "/layouts/footer.php";
    ?>
</body>

</html>