<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    
    echo "Setting up Lost & Found tables...\n\n";
    
    // Check if table exists and get current structure
    $checkTable = "SELECT column_name FROM information_schema.columns WHERE table_name = 'lost_found'";
    $stmt = $db->query($checkTable);
    $existingColumns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($existingColumns)) {
        // Create table if doesn't exist
        $createTableSql = "
        CREATE TABLE lost_found (
            id SERIAL PRIMARY KEY,
            id_barang VARCHAR(50) UNIQUE NOT NULL,
            user_id VARCHAR(255) NOT NULL,
            kategori VARCHAR(20) NOT NULL CHECK (kategori IN ('hilang', 'ditemukan')),
            nama_barang VARCHAR(255) NOT NULL,
            deskripsi TEXT,
            lokasi VARCHAR(255) NOT NULL,
            tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            no_hp VARCHAR(20) NOT NULL,
            email VARCHAR(255),
            foto_barang VARCHAR(255),
            status VARCHAR(20) DEFAULT 'aktif' CHECK (status IN ('aktif', 'selesai')),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_lostfound_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
        );
        CREATE INDEX IF NOT EXISTS idx_lostfound_kategori ON lost_found(kategori);
        CREATE INDEX IF NOT EXISTS idx_lostfound_status ON lost_found(status);
        CREATE INDEX IF NOT EXISTS idx_lostfound_user ON lost_found(user_id);
        ";
        
        $db->exec($createTableSql);
        echo "✓ Lost_found table created successfully\n";
    } else {
        echo "✓ Lost_found table already exists\n";
        
        // Add missing columns if needed
        if (!in_array('foto_barang', $existingColumns)) {
            $db->exec("ALTER TABLE lost_found ADD COLUMN foto_barang VARCHAR(255)");
            echo "✓ Added foto_barang column\n";
        }
        
        if (!in_array('status', $existingColumns)) {
            $db->exec("ALTER TABLE lost_found ADD COLUMN status VARCHAR(20) DEFAULT 'aktif'");
            echo "✓ Added status column\n";
        }
        
        if (!in_array('created_at', $existingColumns)) {
            $db->exec("ALTER TABLE lost_found ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
            echo "✓ Added created_at column\n";
        }
        
        // Update existing records to have default status
        $db->exec("UPDATE lost_found SET status = 'aktif' WHERE status IS NULL");
        echo "✓ Updated existing records with default status\n";
    }
    
    // Create uploads directory if it doesn't exist
    $uploadsDir = __DIR__ . '/public/uploads/lostfound';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
        echo "✓ Created uploads directory: $uploadsDir\n";
    } else {
        echo "✓ Uploads directory already exists\n";
    }
    
    echo "\n✅ Lost & Found tables setup completed successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Error setting up tables: " . $e->getMessage() . "\n";
    exit(1);
}
