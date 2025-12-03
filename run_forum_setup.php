<?php
/**
 * Script untuk menjalankan setup forum groups
 * Jalankan: php run_forum_setup.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    echo "🚀 Memulai setup Forum Groups...\n\n";
    
    $db = Database::getConnection('prod');
    
    echo "📄 Menjalankan setup...\n";
    
    // 1. Buat tabel forum_groups
    echo "  ⏳ Membuat tabel forum_groups...\n";
    $db->exec("
        CREATE TABLE IF NOT EXISTS forum_groups (
            group_id SERIAL PRIMARY KEY,
            komunitas_id INTEGER NOT NULL REFERENCES forum_komunitas(komunitas_id) ON DELETE CASCADE,
            name VARCHAR(100) NOT NULL,
            icon VARCHAR(50) DEFAULT 'hash',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE(komunitas_id, name)
        )
    ");
    echo "  ✅ Tabel forum_groups siap\n";
    
    // 2. Tambah kolom group_id
    echo "  ⏳ Menambah kolom group_id ke forum_messages...\n";
    $db->exec("
        DO $$ 
        BEGIN
            IF NOT EXISTS (
                SELECT 1 FROM information_schema.columns 
                WHERE table_name = 'forum_messages' AND column_name = 'group_id'
            ) THEN
                ALTER TABLE forum_messages 
                ADD COLUMN group_id INTEGER REFERENCES forum_groups(group_id) ON DELETE SET NULL;
            END IF;
        END $$
    ");
    echo "  ✅ Kolom group_id siap\n";
    
    // 3. Buat index
    echo "  ⏳ Membuat index...\n";
    $db->exec("CREATE INDEX IF NOT EXISTS idx_forum_groups_komunitas ON forum_groups(komunitas_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_forum_messages_group ON forum_messages(group_id)");
    echo "  ✅ Index dibuat\n";
    

    
    // 5. Migrasikan pesan lama
    echo "  ⏳ Memigrasikan pesan lama ke grup...\n";
    $result = $db->exec("
        UPDATE forum_messages m
        SET group_id = (
            SELECT g.group_id 
            FROM forum_groups g 
            WHERE g.komunitas_id = m.komunitas_id 
            AND g.name = 'Diskusi Umum'
            LIMIT 1
        )
        WHERE m.group_id IS NULL
    ");
    echo "  ✅ $result pesan dimigrasikan\n";
    
    echo "\n✅ Setup berhasil!\n\n";
    
    // Tampilkan statistik
    echo "📊 Statistik Forum:\n";
    echo "─────────────────────────────────────\n";
    
    $stats = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM forum_komunitas) as komunitas,
            (SELECT COUNT(*) FROM forum_groups) as groups,
            (SELECT COUNT(*) FROM forum_messages) as messages,
            (SELECT COUNT(*) FROM forum_messages WHERE group_id IS NOT NULL) as messages_with_group
    ")->fetch(PDO::FETCH_ASSOC);
    
    echo "  Komunitas: {$stats['komunitas']}\n";
    echo "  Groups: {$stats['groups']}\n";
    echo "  Total Pesan: {$stats['messages']}\n";
    echo "  Pesan dengan Group: {$stats['messages_with_group']}\n";
    echo "─────────────────────────────────────\n\n";
    
    // Tampilkan detail per komunitas
    echo "📋 Detail Groups per Komunitas:\n";
    echo "─────────────────────────────────────\n";
    
    $details = $db->query("
        SELECT 
            k.nama_komunitas,
            g.name as group_name,
            g.icon,
            COUNT(m.message_id) as message_count
        FROM forum_komunitas k
        LEFT JOIN forum_groups g ON k.komunitas_id = g.komunitas_id
        LEFT JOIN forum_messages m ON g.group_id = m.group_id
        GROUP BY k.komunitas_id, k.nama_komunitas, g.group_id, g.name, g.icon
        ORDER BY k.nama_komunitas, g.name
    ")->fetchAll(PDO::FETCH_ASSOC);
    
    $currentKomunitas = null;
    foreach ($details as $row) {
        if ($currentKomunitas !== $row['nama_komunitas']) {
            if ($currentKomunitas !== null) echo "\n";
            echo "  📁 {$row['nama_komunitas']}\n";
            $currentKomunitas = $row['nama_komunitas'];
        }
        if ($row['group_name']) {
            $icon = $row['icon'] ?? 'hash';
            $count = $row['message_count'] ?? 0;
            echo "     └─ {$row['group_name']} ({$icon}) - {$count} pesan\n";
        }
    }
    
    echo "\n🎉 Setup selesai! Silakan buka forum di browser.\n";
    echo "   URL: http://localhost:8000/forum\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
