<?php
// Jalankan file ini SEKALI untuk hash semua password di database
require_once '../vendor/autoload.php';
require_once '../app/Model/Database.php';

use App\Model\Database;

try {
    $db = Database::getInstance();


    $stmt = $db->query("SELECT user_id, password FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($users as $user) {
        // Cek apakah password sudah di-hash (password hash panjangnya 60 karakter)
        if (strlen($user['password']) < 60) {
            $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);

            $updateStmt = $db->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
            $updateStmt->execute([
                ':password' => $hashedPassword,
                ':user_id' => $user['user_id']
            ]);

            echo "Password untuk user_id {$user['user_id']} berhasil di-hash<br>";
        } else {
            echo "Password untuk user_id {$user['user_id']} sudah di-hash<br>";
        }
    }

    echo "<br><strong>Selesai! Semua password sudah di-hash.</strong>";
    echo "<br><br>Sekarang Anda bisa login dengan password asli Anda.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
