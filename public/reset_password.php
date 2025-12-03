<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

$db = Database::getConnection('prod');

// Get the first user
$stmt = $db->query("SELECT user_id, email FROM users LIMIT 1");
$user = $stmt->fetch();

if ($user) {
    $newPassword = password_hash('password', PASSWORD_DEFAULT);
    $updateStmt = $db->prepare("UPDATE users SET password = ? WHERE user_id = ?");
    $updateStmt->execute([$newPassword, $user['user_id']]);
    
    echo "Password for user {$user['email']} has been reset to 'password'.\n";
} else {
    echo "No users found in the database.\n";
}
