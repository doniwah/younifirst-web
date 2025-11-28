<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouNiFirst - Platform Komunitas Kampus</title>
    <link rel="stylesheet" href="/css/index.css">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=logout" />
</head>

<body>
    <?php
    require_once __DIR__ . "/layouts/navbar.php";
    ?>

    <section class="hero">
        <h1 data-aos="fade-up">Terhubung, Berkompetisi, Berkembang Bersama</h1>
        <p data-aos="fade-up" data-aos-delay="200">YouNiFirst adalah platform all-in-one untuk mahasiswa. Temukan lomba,
            tim, event, dan lebih banyak lagi dalam
            satu tempat.</p>
        <div class="cta-buttons">
            <button class="btn-primary" data-aos="fade-right" data-aos-delay="400">Mulai Sekarang</button>
            <button class="btn-secondary" data-aos="fade-left" data-aos-delay="600">Pelajari Lebih Lanjut</button>
        </div>
    </section>

    <section class="features">
        <h2 data-aos="fade-up">Fitur Lengkap untuk Mahasiswa</h2>
        <p class="features-subtitle" data-aos="fade-up">Semua yang kamu butuhkan untuk berkembang di kampus, dalam
            satu platform <br>yang
            mudah
            digunakan</p>

        <div class="features-grid">
            <?php if (!empty($features)): ?>
                <?php foreach ($features as $index => $feature): ?>
                <div class="feature-card" data-aos="fade-up" data-aos-delay="<?= 200 + ($index * 200) ?>">
                    <div class="feature-icon <?= $feature['icon_color_class'] ?>"><i class="<?= $feature['icon'] ?>"
                            style="color: #ffffff; font-size: 20px;"></i></div>
                    <h3><?= htmlspecialchars($feature['title']) ?></h3>
                    <p><?= htmlspecialchars($feature['description']) ?></p>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada fitur yang ditampilkan.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="why-section">
        <div class="why-content" data-aos="fade-left">
            <h2>Mengapa Memilih <br>YouNiFirst?</h2>

            <div class="why-item" data-aos="fade-left" data-aos-delay="200">
                <div class="why-icon icon-people"><i class="bi bi-people"></i></div>
                <div>
                    <h3>Komunitas Aktif</h3>
                    <p>Bergabung dengan ribuan mahasiswa yang aktif berbagi dan berkolaborasi</p>
                </div>
            </div>

            <div class="why-item" data-aos="fade-left" data-aos-delay="400">
                <div class="why-icon icon-shield"><i class="bi bi-shield"></i></div>
                <div>
                    <h3>Aman & Terpercaya</h3>
                    <p>Platform dengan sistem keamanan terjamin untuk melindungi data kamu</p>
                </div>
            </div>

            <div class="why-item" data-aos="fade-left" data-aos-delay="600">
                <div class="why-icon icon-calendar"><i class="bi bi-calendar4"></i></div>
                <div>
                    <h3>Update Berkala</h3>
                    <p>Dapatkan informasi terbaru tentang lomba, event, dan aktivitas kampus</p>
                </div>
            </div>
        </div>

        <div class="stats-card" data-aos="fade-right" data-aos-delay="800">
            <?php if (!empty($stats)): ?>
                <?php foreach ($stats as $stat): ?>
                <div class="stats-number"><?= htmlspecialchars($stat['number']) ?></div>
                <div class="stats-label"><?= htmlspecialchars($stat['label']) ?></div>
                <p class="stats-description"><?= htmlspecialchars($stat['description']) ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="stats-number">0+</div>
                <div class="stats-label">Pengguna</div>
                <p class="stats-description">Data belum tersedia</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="testimonial-section">
        <div class="section-header" data-aos="fade-up">
            <h2>Apa Kata Mahasiswa?</h2>
            <p>Dengarkan pengalaman dari pengguna YouNiFirst lainnya</p>
        </div>

        <div class="testimonial-grid">
            <?php if (!empty($testimonials)): ?>
                <?php foreach ($testimonials as $index => $testimonial): ?>
                <div class="testimonial-card" data-aos="zoom-in" data-aos-delay="<?= 200 + ($index * 200) ?>">
                    <div class="card-header">
                        <div class="avatar <?= $testimonial['avatar_class'] ?>">
                            <?php
                            $parts = explode(' ', $testimonial['name']);
                            $initials = '';
                            foreach ($parts as $part) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                            echo substr($initials, 0, 2);
                            ?>
                        </div>
                        <div class="user-info">
                            <h3><?= htmlspecialchars($testimonial['name']) ?></h3>
                            <p><?= htmlspecialchars($testimonial['major']) ?></p>
                        </div>
                    </div>
                    <div class="testimonial-text">
                        <?= htmlspecialchars($testimonial['text']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada testimoni.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="faq-section">
        <div class="section-header">
            <h2 data-aos="fade-up">Pertanyaan Yang Sering Diajukan</h2>
            <p data-aos="fade-up">Temukan jawaban untuk pertanyaan umum tentang YouNiFirst</p>
        </div>

        <div class="faq-container">
            <?php if (!empty($faqs)): ?>
                <?php foreach ($faqs as $faq): ?>
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question"><?= htmlspecialchars($faq['question']) ?></div>
                    <div class="faq-answer">
                        <?= htmlspecialchars($faq['answer']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada FAQ.</p>
            <?php endif; ?>
        </div>
    </section>

    <?php
    require_once __DIR__ . "/layouts/footer.php";
    ?>
    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
    AOS.init({
        duration: 1000,
        once: true,
        offset: 120,
    });
    </script>
</body>

</html>