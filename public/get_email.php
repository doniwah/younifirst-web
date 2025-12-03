<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

$db = Database::getConnection('prod');
$stmt = $db->query("SELECT email FROM users LIMIT 1");
echo $stmt->fetch()['email'];
