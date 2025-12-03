<?php
/**
 * Check dan isi forum database
 * Run: php check_forum.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    
    echo "🔍 Memeriksa struktur tabel forum_komunitas...\n";
    
    // Check columns
    $columns = $db->query("
        SELECT column_name, data_type, column_default
        FROM information_schema.columns 
        WHERE table_name = 'forum_komunitas'
        ORDER BY ordinal_position
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    echo "\nKolom yang ada:\n";
    foreach ($columns as $col) {
        echo "  - {$col['column_name']} ({$col['data_type']})\n";
    }
    
    // Check existing data
    echo "\n📊 Data yang ada:\n";
    $stats = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM forum_komunitas) as komunitas,
            (SELECT COUNT(*) FROM forum_anggota) as anggota,
            (SELECT COUNT(*) FROM forum_messages) as messages
    ")->fetch(PDO::FETCH_ASSOC);
    
    echo "  Komunitas: {$stats['komunitas']}\n";
    echo "  Anggota: {$stats['anggota']}\n";
    echo "  Messages: {$stats['messages']}\n";
    
    // If empty, try simple insert
    if ($stats['komunitas'] == 0) {
        echo "\n💡 Database kosong, mencoba insert sederhana...\n";
        
        // Try without icon_type first
        try {
            $db->exec("
                INSERT INTO forum_komunitas (nama_komunitas, deskripsi) 
                VALUES ('Komunitas Global', 'Forum untuk semua mahasiswa')
            ");
            echo "  ✅ Berhasil insert komunitas pertama\n";
            
            // Get the ID
            $komId = $db->lastInsertId();
            
            // Add current user as member
            $user = $db->query("SELECT user_id FROM users LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $db->exec("
                    INSERT INTO forum_anggota (komunitas_id, user_id, joined_at)
                    VALUES ($komId, '{$user['user_id']}', NOW())
                ");
                echo "  ✅ User ditambahkan ke komunitas\n";
                
                // Add a message
                $db->exec("
                    INSERT INTO forum_messages (komunitas_id, user_id, message_text, created_at)
                    VALUES ($komId, '{$user['user_id']}', 'Halo! Selamat datang 👋', NOW())
                ");
                echo "  ✅ Sample message dibuat\n";
            }
            
        } catch (Exception $e) {
            echo "  ❌ Error: " . $e->getMessage() . "\n";
        }
    }
    
    // Show final stats
    $stats = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM forum_komunitas) as komunitas,
            (SELECT COUNT(*) FROM forum_anggota) as anggota,  
            (SELECT COUNT(*) FROM forum_messages) as messages
    ")->fetch(PDO::FETCH_ASSOC);
    
    echo "\n✨ Status akhir:\n";
    echo "  Komunitas: {$stats['komunitas']}\n";
    echo "  Anggota: {$stats['anggota']}\n";
    echo "  Messages: {$stats['messages']}\n";
    
    if ($stats['anggota'] > 0) {
        echo "\n🎉 Data sudah ada! Silakan refresh halaman forum.\n";
    } else {
        echo "\n⚠️  Belum ada data anggota. Coba jalankan manual query SQL.\n";
    }
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
}
