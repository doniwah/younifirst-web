<?php
require_once __DIR__ . '/vendor/autoload.php';
use App\Config\Database;

try {
    echo "🚀 Setup Forum Groups\n\n";
    $db = Database::getConnection('prod');
    
    // 1. Create table
    echo "1. Creating forum_groups table...\n";
    $db->exec("CREATE TABLE IF NOT EXISTS forum_groups (
        group_id SERIAL PRIMARY KEY,
        komunitas_id INTEGER NOT NULL REFERENCES forum_komunitas(komunitas_id) ON DELETE CASCADE,
        name VARCHAR(100) NOT NULL,
        icon VARCHAR(50) DEFAULT 'hash',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(komunitas_id, name)
    )");
    
    // 2. Add column
    echo "2. Adding group_id column...\n";
    try {
        $db->exec("ALTER TABLE forum_messages ADD COLUMN group_id INTEGER REFERENCES forum_groups(group_id) ON DELETE SET NULL");
    } catch (Exception $e) {
        // Column might already exist
    }
    
    // 3. Create indexes
    echo "3. Creating indexes...\n";
    $db->exec("CREATE INDEX IF NOT EXISTS idx_forum_groups_komunitas ON forum_groups(komunitas_id)");
    $db->exec("CREATE INDEX IF NOT EXISTS idx_forum_messages_group ON forum_messages(group_id)");
    
    // 4. Get komunitas list
    echo "4. Creating default groups...\n";
    $komunitas = $db->query("SELECT komunitas_id, nama_komunitas FROM forum_komunitas")->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($komunitas)) {
        echo "   ⚠️  No komunitas found! Run setup_forum_data.php first.\n";
        exit(1);
    }
    
    // 5. Create groups
    foreach ($komunitas as $k) {
        // Pengumuman
        try {
            $stmt = $db->prepare("INSERT INTO forum_groups (komunitas_id, name, icon) VALUES (?, ?, ?)");
            $stmt->execute([$k['komunitas_id'], 'Pengumuman', 'volume-up']);
        } catch (Exception $e) {
            // Already exists
        }
        
        // Diskusi Umum
        try {
            $stmt = $db->prepare("INSERT INTO forum_groups (komunitas_id, name, icon) VALUES (?, ?, ?)");
            $stmt->execute([$k['komunitas_id'], 'Diskusi Umum', 'message-circle']);
        } catch (Exception $e) {
            // Already exists
        }
        
        echo "   ✅ {$k['nama_komunitas']}\n";
    }
    
    // 6. Migrate messages
    echo "5. Migrating old messages...\n";
    $updated = $db->exec("
        UPDATE forum_messages m
        SET group_id = (
            SELECT g.group_id FROM forum_groups g 
            WHERE g.komunitas_id = m.komunitas_id AND g.name = 'Diskusi Umum'
            LIMIT 1
        )
        WHERE m.group_id IS NULL
    ");
    echo "   ✅ Migrated $updated messages\n";
    
    // Stats
    echo "\n📊 Statistics:\n";
    $stats = $db->query("
        SELECT 
            (SELECT COUNT(*) FROM forum_komunitas) as komunitas,
            (SELECT COUNT(*) FROM forum_groups) as groups,
            (SELECT COUNT(*) FROM forum_messages) as messages
    ")->fetch(PDO::FETCH_ASSOC);
    
    echo "   Komunitas: {$stats['komunitas']}\n";
    echo "   Groups: {$stats['groups']}\n";
    echo "   Messages: {$stats['messages']}\n";
    
    echo "\n✅ Setup complete! Open http://localhost:8000/forum\n\n";
    
} catch (Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
