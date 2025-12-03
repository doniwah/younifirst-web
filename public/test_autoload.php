<?php
require_once __DIR__ . '/../vendor/autoload.php';
use App\Controller\Api\EventApiController;

echo "Checking EventApiController...\n";
if (class_exists(EventApiController::class)) {
    echo "Class found: " . EventApiController::class . "\n";
} else {
    echo "Class NOT found\n";
}
