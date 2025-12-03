<?php
/**
 * Script untuk membuat data sample forum
 * Jalankan: php setup_forum_data.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    
    echo "🔄 Membuat data sample untuk Forum...\n\n";
    
    // 1. Check struktur tabel
    echo "🔍 Memeriksa struktur tabel...\n";
    $tables = ['forum_komunitas', 'forum_anggota', 'forum_messages'];
    foreach ($tables as $table) {
        $check = $db->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_name = '$table'")->fetchColumn();
        if ($check == 0) {
            echo "  ❌ Tabel $table tidak ditemukan!\n";
            exit(1);
        }
        echo "  ✅ Tabel $table ada\n";
    }
    
    // 2. Insert Komunitas (Forum Groups)
    echo "\n📝 Membuat komunitas forum...\n";
    
    $komunitas = [
        [
            'nama_komunitas' => 'Komunitas Global',
            'deskripsi' => 'Forum untuk semua mahasiswa dari berbagai jurusan',
            'icon_type' => 'globe',
            'jurusan_filter' => null
        ],
        [
            'nama_komunitas' => 'Teknik Informatika',
            'deskripsi' => 'TI - Diskusi seputar programming, AI, dan teknologi',
            'icon_type' => 'users',
            'jurusan_filter' => 'Informatika'
        ],
        [
            'nama_komunitas' => 'Teknik Sipil',
            'deskripsi' => 'TS - Berbagi ilmu konstruksi dan infrastruktur',
            'icon_type' => 'users',
            'jurusan_filter' => 'Teknik Sipil'
        ],
        [
            'nama_komunitas' => 'Teknik Industri',
            'deskripsi' => 'IND - Forum optimasi dan manajemen produksi',
            'icon_type' => 'users',
            'jurusan_filter' => 'Teknik Industri'
        ],
        [
            'nama_komunitas' => 'Teknik Mesin',
            'deskripsi' => 'TM - Diskusi mesin dan manufaktur',
            'icon_type' => 'users',
            'jurusan_filter' => 'Teknik Mesin'
        ]
    ];
    
    $komunitas_ids = [];
    foreach ($komunitas as $k) {
        // Check if exists
        $check = $db->prepare("SELECT komunitas_id FROM forum_komunitas WHERE nama_komunitas = ?");
        $check->execute([$k['nama_komunitas']]);
        $existing = $check->fetch(PDO::FETCH_ASSOC);
        
        if ($existing) {
            $komunitas_ids[$k['nama_komunitas']] = $existing['komunitas_id'];
            echo "  ⏭️  {$k['nama_komunitas']} (sudah ada)\n";
        } else {
            $stmt = $db->prepare("INSERT INTO forum_komunitas (nama_komunitas, deskripsi, icon_type, jurusan_filter) VALUES (?, ?, ?, ?)");
            
            if ($k['jurusan_filter'] === null) {
                $stmt->bindValue(1, $k['nama_komunitas']);
                $stmt->bindValue(2, $k['deskripsi']);
                $stmt->bindValue(3, $k['icon_type']);
                $stmt->bindValue(4, null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(1, $k['nama_komunitas']);
                $stmt->bindValue(2, $k['deskripsi']);
                $stmt->bindValue(3, $k['icon_type']);
                $stmt->bindValue(4, $k['jurusan_filter']);
            }
            
            $stmt->execute();
            $komunitas_ids[$k['nama_komunitas']] = $db->lastInsertId();
            echo "  ✅ {$k['nama_komunitas']}\n";
        }
    }
    
    // 3. Ambil semua user_id dari database
    echo "\n👥 Mengambil data users...\n";
    $users = $db->query("SELECT user_id, username, jurusan FROM users ORDER BY user_id LIMIT 50")->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "  ⚠️  Tidak ada user di database! Silakan buat user terlebih dahulu.\n";
        exit(1);
    }
    
    echo "  ✅ Ditemukan " . count($users) . " users\n";
    
    // 4. Tambahkan anggota ke setiap komunitas
    echo "\n👨‍👩‍👧‍👦 Menambahkan anggota ke komunitas...\n";
    
    $stmt_anggota = $db->prepare("INSERT INTO forum_anggota (komunitas_id, user_id, joined_at) VALUES (?, ?, NOW()) ON CONFLICT DO NOTHING");
    
    $total_anggota = 0;
    
    // Semua user join ke Komunitas Global
    if (isset($komunitas_ids['Komunitas Global'])) {
        foreach ($users as $user) {
            $stmt_anggota->execute([$komunitas_ids['Komunitas Global'], $user['user_id']]);
            $total_anggota++;
        }
        echo "  ✅ Komunitas Global: " . count($users) . " anggota\n";
    }
    
    // User join ke komunitas sesuai jurusan mereka
    foreach ($users as $user) {
        $jurusan = $user['jurusan'];
        
        // Mapping jurusan ke nama komunitas
        $komunitas_map = [
            'Informatika' => 'Teknik Informatika',
            'Teknik Sipil' => 'Teknik Sipil',
            'Teknik Industri' => 'Teknik Industri',
            'Teknik Mesin' => 'Teknik Mesin'
        ];
        
        if (isset($komunitas_map[$jurusan]) && isset($komunitas_ids[$komunitas_map[$jurusan]])) {
            $stmt_anggota->execute([$komunitas_ids[$komunitas_map[$jurusan]], $user['user_id']]);
            $total_anggota++;
        }
    }
    
    echo "  ✅ Total keanggotaan ditambahkan\n";
    
    // 5. Buat sample messages untuk setiap komunitas
    echo "\n💬 Membuat sample messages...\n";
    
    $sample_messages = [
        'Komunitas Global' => [
            'Halo semua! Selamat datang di Komunitas Global 👋',
            'Ada event menarik minggu depan, yuk gabung!',
            'Siapa yang mau ikut workshop programming?',
            'Diskusi project bersama yuk!',
            'Tips belajar efektif dong guys'
        ],
        'Teknik Informatika' => [
            'Ada yang bisa bantu masalah algoritma sorting?',
            'Rekomendasi framework backend yang bagus dong',
            'Tutorial machine learning untuk pemula ada?',
            'Sharing pengalaman magang di tech company yuk!',
            'Bug di Laravel, help! 🐛'
        ],
        'Teknik Sipil' => [
            'Diskusi tentang struktur beton bertulang',
            'Ada yang punya catatan mata kuliah Mekanika Tanah?',
            'Tips mengerjakan tugas akhir konstruksi?',
            'Software terbaik untuk desain struktur?',
            'Sharing pengalaman PKL di kontraktor'
        ],
        'Teknik Industri' => [
            'Cara optimasi supply chain yang efektif?',
            'Ada yang bisa jelaskan Linear Programming?',
            'Diskusi tentang lean manufacturing',
            'Rekomendasi software simulasi produksi?',
            'Tips interview di perusahaan manufaktur'
        ],
        'Teknik Mesin' => [
            'Belajar CAD untuk pemula, mulai dari mana?',
            'Diskusi tentang material engineering',
            'Ada yang pernah magang di automotive?',
            'Tips mengerjakan tugas gambar teknik',
            'Rekomendasi buku termodinamika yang bagus'
        ]
    ];
    
    $total_messages = 0;
    foreach ($sample_messages as $komunitas_name => $messages) {
        if (!isset($komunitas_ids[$komunitas_name])) continue;
        
        $komunitas_id = $komunitas_ids[$komunitas_name];
        
        // Ambil beberapa user random untuk posting message
        $random_users = array_rand($users, min(5, count($users)));
        if (!is_array($random_users)) $random_users = [$random_users];
        
        foreach ($messages as $index => $message) {
            $user_index = $random_users[$index % count($random_users)];
            $user_id = $users[$user_index]['user_id'];
            
            // Insert message dengan timestamp yang berbeda
            $stmt_message = $db->prepare("INSERT INTO forum_messages (komunitas_id, user_id, message_text, created_at) VALUES (?, ?, ?, NOW() - INTERVAL '" . rand(1, 72) . " hours')");
            $stmt_message->execute([$komunitas_id, $user_id, $message]);
            $total_messages++;
        }
        
        echo "  ✅ {$komunitas_name}: " . count($messages) . " messages\n";
    }
    
    echo "\n✨ SELESAI! Data forum berhasil dibuat:\n";
    echo "  📁 Komunitas: " . count($komunitas) . "\n";
    echo "  👥 Total keanggotaan: ~$total_anggota\n";
    echo "  💬 Total messages: $total_messages\n";
    echo "\n🎉 Silakan refresh halaman forum Anda di browser (Ctrl+F5)!\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
