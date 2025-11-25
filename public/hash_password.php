<?php

require_once '../vendor/autoload.php';

try {

    // === KONFIGURASI SUPABASE ===
    $host = "db.gkffayfknznoctegzqhr.supabase.co";
    $dbname = "postgres";
    $username = "postgres";
    $password = "Zgje84p6t3UTeKJm";

    $dsn = "pgsql:host=$host;port=5432;dbname=$dbname;sslmode=require";

    // === KONEKSI DATABASE ===
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // Ambil semua user
    $stmt = $db->query("SELECT user_id, password FROM users");
    $users = $stmt->fetchAll();

    foreach ($users as $user) {

        // Jika password BELUM di-hash (hash default panjangnya 60 karakter)
        if (strlen($user['password']) < 60) {

            $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

            $updateStmt = $db->prepare("
                UPDATE users 
                SET password = :password 
                WHERE user_id = :user_id
            ");

            $updateStmt->execute([
                ':password' => $hashedPassword,
                ':user_id' => $user['user_id']
            ]);

            echo "Password user_id {$user['user_id']} berhasil di-hash<br>";
        } else {
            echo "Password user_id {$user['user_id']} sudah di-hash<br>";
        }
    }

    echo "<br><strong>Selesai! Semua password sudah di-hash.</strong>";
    echo "<br><br>Anda bisa login dengan password asli Anda.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
