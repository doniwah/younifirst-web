<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    
    echo "Creating event management tables...\n\n";
    
    // Create event table
    $eventTableSql = "
    CREATE TABLE IF NOT EXISTS event (
        event_id SERIAL PRIMARY KEY,
        nama_event VARCHAR(255) NOT NULL,
        tanggal_mulai TIMESTAMP NOT NULL,
        tanggal_selsai TIMESTAMP NOT NULL,
        status VARCHAR(20) DEFAULT 'waiting' CHECK (status IN ('waiting', 'confirm')),
        poster_event VARCHAR(255) DEFAULT NULL,
        lokasi VARCHAR(255) NOT NULL,
        organizer VARCHAR(255) NOT NULL,
        kapasitas INT NOT NULL,
        peserta_terdaftar INT DEFAULT 0,
        deskripsi TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
    CREATE INDEX IF NOT EXISTS idx_event_status ON event(status);
    CREATE INDEX IF NOT EXISTS idx_event_tanggal_mulai ON event(tanggal_mulai);
    ";
    
    $db->exec($eventTableSql);
    echo "✓ Event table created successfully\n";
    
    // Create pendaftaran_event table
    $pendaftaranEventSql = "
    CREATE TABLE IF NOT EXISTS pendaftaran_event (
        id SERIAL PRIMARY KEY,
        event_id INT NOT NULL,
        user_id VARCHAR(255) NOT NULL,
        tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        status VARCHAR(50) DEFAULT 'registered',
        CONSTRAINT fk_event FOREIGN KEY (event_id) REFERENCES event(event_id) ON DELETE CASCADE,
        CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
        CONSTRAINT unique_registration UNIQUE (event_id, user_id)
    );
    CREATE INDEX IF NOT EXISTS idx_pendaftaran_event_id ON pendaftaran_event(event_id);
    CREATE INDEX IF NOT EXISTS idx_pendaftaran_user_id ON pendaftaran_event(user_id);
    ";
    
    $db->exec($pendaftaranEventSql);
    echo "✓ Pendaftaran_event table created successfully\n";
    
    // Create uploads directory if it doesn't exist
    $uploadsDir = __DIR__ . '/public/uploads/events';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
        echo "✓ Created uploads directory: $uploadsDir\n";
    } else {
        echo "✓ Uploads directory already exists\n";
    }
    
    echo "\n✅ All event tables created successfully!\n";
    
} catch (PDOException $e) {
    echo "❌ Error creating tables: " . $e->getMessage() . "\n";
    exit(1);
}
