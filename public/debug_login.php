<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;
use App\Repository\UserRepository;

$db = Database::getConnection('prod');
$repo = new UserRepository($db);

$email = 'test@example.com'; // Replace with a known email if possible, or list all users
$password = 'password'; // Replace with expected password

echo "Checking users...\n";
$stmt = $db->query("SELECT user_id, email, password FROM users LIMIT 5");
while ($row = $stmt->fetch()) {
    echo "User: " . $row['email'] . "\n";
    echo "Hash: " . $row['password'] . "\n";
    if (password_verify('password', $row['password'])) {
        echo "Password 'password' is VALID for this user.\n";
    } else {
        echo "Password 'password' is INVALID for this user.\n";
    }
    if (password_verify('123456', $row['password'])) {
        echo "Password '123456' is VALID for this user.\n";
    } else {
        echo "Password '123456' is INVALID for this user.\n";
    }
    echo "----------------\n";
}
