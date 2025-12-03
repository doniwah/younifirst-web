<?php
/**
 * Simple script to populate forum database
 * Run: php setup_forum_simple.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    echo "🔄 Membuat data forum...\n\n";
    
    // 1. Create forum communities
    echo "📁 Membuat komunitas...\n";
    $db->exec("
        INSERT INTO forum_komunitas (nama_komunitas, deskripsi, icon_type, jurusan_filter) VALUES
        ('Komunitas Global', 'Forum untuk semua mahasiswa dari berbagai jurusan', 'globe', NULL),
        ('Teknik Informatika', 'TI - Diskusi seputar programming, AI, dan teknologi', 'users', 'Informatika'),
        ('Teknik Sipil', 'TS - Berbagi ilmu konstruksi dan infrastruktur', 'users', 'Teknik Sipil'),
        ('Teknik Industri', 'IND - Forum optimasi dan manajemen produksi', 'users', 'Teknik Industri'),
        ('Teknik Mesin', 'TM - Diskusi mesin dan manufaktur', 'users', 'Teknik Mesin')
        ON CONFLICT (nama_komunitas) DO NOTHING
    ");
    echo "  ✅ 5 komunitas dibuat\n";
    
    // 2. Add all users to Global Community
    echo "\n👥 Menambahkan anggota...\n";
    $db->exec("
        INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
        SELECT 
            (SELECT komunitas_id FROM forum_komunitas WHERE nama_komunitas = 'Komunitas Global' LIMIT 1),
            user_id,
            NOW()
        FROM users
        ON CONFLICT DO NOTHING
    ");
    echo "  ✅ Semua user join Komunitas Global\n";
    
    // 3. Add users to their major-specific forums
    $majors = [
        'Informatika' => 'Teknik Informatika',
        'Teknik Sipil' => 'Teknik Sipil',
        'Teknik Industri' => 'Teknik Industri',
        'Teknik Mesin' => 'Teknik Mesin'
    ];
    
    foreach ($majors as $userMajor => $forumName) {
        $db->exec("
            INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
            SELECT 
                (SELECT komunitas_id FROM forum_komunitas WHERE nama_komunitas = '$forumName' LIMIT 1),
                user_id,
                NOW()
            FROM users
            WHERE jurusan = '$userMajor'
            ON CONFLICT DO NOTHING
        ");
    }
    echo "  ✅ User join forum sesuai jurusan\n";
    
    // 4. Create sample messages
    echo "\n💬 Membuat sample messages...\n";
    
    // Get first user
    $user = $db->query("SELECT user_id FROM users ORDER BY user_id LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        $userId = $user['user_id'];
        
        // Get Komunitas Global ID
        $komunitas = $db->query("SELECT komunitas_id FROM forum_komunitas WHERE nama_komunitas = 'Komunitas Global' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        
        if ($komunitas) {
            $komunitasId = $komunitas['komunitas_id'];
            
            $messages = [
                "Halo semua! Selamat datang di Komunitas Global 👋",
                "Ada event menarik minggu depan, yuk gabung!",
                "Siapa yang mau ikut workshop programming?",
                "Diskusi project bersama yuk!",
                "Tips belajar efektif dong guys"
            ];
            
            $stmt = $db->prepare("INSERT INTO forum_messages (komunitas_id, user_id, message_text, created_at) VALUES (?, ?, ?, NOW() - INTERVAL '? hours')");
            
            foreach ($messages as $index => $message) {
                $hoursAgo = 5 - $index;
                $stmt->execute([$komunitasId, $userId, $message, $hoursAgo]);
            }
            
            echo "  ✅ 5 sample messages dibuat\n";
        }
    }
    
    // 5. Verify results
    echo "\n📊 Hasil:\n";
    $stats = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM forum_komunitas) as total_komunitas,
            (SELECT COUNT(*) FROM forum_anggota) as total_anggota,
            (SELECT COUNT(*) FROM forum_messages) as total_messages
    ")->fetch(PDO::FETCH_ASSOC);
    
    echo "  📁 Total komunitas: " . $stats['total_komunitas'] . "\n";
    echo "  👥 Total keanggotaan: " . $stats['total_anggota'] . "\n";
    echo "  💬 Total messages: " . $stats['total_messages'] . "\n";
    
    echo "\n✨ SELESAI! Refresh halaman forum Anda (Ctrl+F5)\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
