<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;

try {
    $db = Database::getConnection('prod');
    echo "Connected to database.\n";

    // 1. Teams Table
    $db->exec("CREATE TABLE IF NOT EXISTS teams (
        id SERIAL PRIMARY KEY,
        nama_team VARCHAR(255) NOT NULL,
        deskripsi TEXT,
        competition_id INT,
        user_id VARCHAR(50) NOT NULL,
        max_members INT DEFAULT 5,
        skills_required TEXT,
        contact_info TEXT,
        status VARCHAR(50) DEFAULT 'active',
        deadline TIMESTAMP,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "Teams table created.\n";

    // 2. Team Members Table
    $db->exec("CREATE TABLE IF NOT EXISTS team_members (
        id SERIAL PRIMARY KEY,
        team_id INT NOT NULL,
        user_id VARCHAR(50) NOT NULL,
        role VARCHAR(50) DEFAULT 'member',
        joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(team_id, user_id)
    )");
    echo "Team members table created.\n";

    // 3. Team Applications Table
    $db->exec("CREATE TABLE IF NOT EXISTS team_applications (
        id SERIAL PRIMARY KEY,
        team_id INT NOT NULL,
        user_id VARCHAR(50) NOT NULL,
        message TEXT,
        status VARCHAR(50) DEFAULT 'pending',
        applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        processed_at TIMESTAMP,
        UNIQUE(team_id, user_id)
    )");
    echo "Team applications table created.\n";

    echo "\nAll team tables created successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
