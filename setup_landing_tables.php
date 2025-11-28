<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

// Ensure we can load the Database class
// We might need to manually require it if autoload isn't working for this script
require_once __DIR__ . '/app/Config/Database.php';
require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getConnection('prod');
    
    // Create Features Table
    $db->exec("CREATE TABLE IF NOT EXISTS features (
        id SERIAL PRIMARY KEY,
        icon VARCHAR(255) NOT NULL,
        icon_color_class VARCHAR(50) NOT NULL,
        title VARCHAR(100) NOT NULL,
        description TEXT NOT NULL
    )");

    // Insert Features
    $features = [
        ['bi bi-trophy', 'icon-purple', 'Kompetisi', 'Posting lomba atau buat tim untuk berkompetensi bersama'],
        ['bi bi-box-seam', 'icon-pink', 'Lost & Found', 'Temukan atau laporkan barang hilang dengan mudah'],
        ['bi bi-calendar4', 'icon-orange', 'Event', 'Posting dan daftar event kampus yang menarik'],
        ['bi bi-chat-left', 'icon-blue', 'Forum', 'Diskusi dan berbagi informasi dengan komunitas']
    ];

    $stmt = $db->prepare("INSERT INTO features (icon, icon_color_class, title, description) VALUES (?, ?, ?, ?)");
    foreach ($features as $f) {
        // Check if exists to avoid duplicates on re-run
        $check = $db->prepare("SELECT COUNT(*) FROM features WHERE title = ?");
        $check->execute([$f[2]]);
        if ($check->fetchColumn() == 0) {
            $stmt->execute($f);
        }
    }

    // Create Stats Table
    $db->exec("CREATE TABLE IF NOT EXISTS stats (
        id SERIAL PRIMARY KEY,
        number VARCHAR(50) NOT NULL,
        label VARCHAR(100) NOT NULL,
        description TEXT NOT NULL
    )");

    // Insert Stats
    $stats = [
        ['1000+', 'Pengguna Aktif', 'Bergabung dengan komunitas yang terus berkembang']
    ];

    $stmt = $db->prepare("INSERT INTO stats (number, label, description) VALUES (?, ?, ?)");
    foreach ($stats as $s) {
        $check = $db->prepare("SELECT COUNT(*) FROM stats WHERE label = ?");
        $check->execute([$s[1]]);
        if ($check->fetchColumn() == 0) {
            $stmt->execute($s);
        }
    }

    // Create Testimonials Table
    $db->exec("CREATE TABLE IF NOT EXISTS testimonials (
        id SERIAL PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        major VARCHAR(100) NOT NULL,
        avatar_class VARCHAR(50) NOT NULL,
        text TEXT NOT NULL
    )");

    // Insert Testimonials
    $testimonials = [
        ['Ahmad Maulana', 'Teknologi Informasi', 'blue1', '"YouNiFirst sangat membantu saya menemukan tim untuk kompetisi. Sekarang lebih mudah berkolaborasi dengan mahasiswa lain!"'],
        ['Siti Permata', 'Sistem Informasi', 'blue2', '"Fitur Lost & Found-nya sangat berguna! Saya berhasil menemukan dompet yang hilang dalam 2 hari."'],
        ['Rizki Pratama', 'Teknik Elektro', 'blue3', '"Platform yang lengkap dan mudah digunakan. Forum diskusinya aktif dan membantu dalam belajar kelompok."']
    ];

    $stmt = $db->prepare("INSERT INTO testimonials (name, major, avatar_class, text) VALUES (?, ?, ?, ?)");
    foreach ($testimonials as $t) {
        $check = $db->prepare("SELECT COUNT(*) FROM testimonials WHERE name = ?");
        $check->execute([$t[0]]);
        if ($check->fetchColumn() == 0) {
            $stmt->execute($t);
        }
    }

    // Create FAQs Table
    $db->exec("CREATE TABLE IF NOT EXISTS faqs (
        id SERIAL PRIMARY KEY,
        question TEXT NOT NULL,
        answer TEXT NOT NULL
    )");

    // Insert FAQs
    $faqs = [
        ['Apakah YouNiFirst gratis?', 'Ya, YouNiFirst sepenuhnya gratis untuk semua mahasiswa. Anda dapat mengakses semua fitur tanpa biaya apapun.'],
        ['Bagaimana cara bergabung dengan tim kompetisi?', 'Buka halaman Kompetisi, cari tim yang sesuai dengan minat Anda, lalu klik tombol "Daftar". Pembuat tim akan meninjau aplikasi Anda.'],
        ['Siapa yang bisa melihat postingan saya di forum?', 'Untuk forum jurusan, hanya mahasiswa dari jurusan yang sama yang dapat melihat. Forum global dapat dilihat oleh semua pengguna.'],
        ['Bagaimana cara melaporkan barang hilang?', 'Kunjungi halaman Lost & Found, klik tombol "Laporkan", pilih kategori "Kehilangan", lalu isi detail barang yang hilang dengan lengkap.']
    ];

    $stmt = $db->prepare("INSERT INTO faqs (question, answer) VALUES (?, ?)");
    foreach ($faqs as $f) {
        $check = $db->prepare("SELECT COUNT(*) FROM faqs WHERE question = ?");
        $check->execute([$f[0]]);
        if ($check->fetchColumn() == 0) {
            $stmt->execute($f);
        }
    }

    echo "Tables created and data inserted successfully.\n";

} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
