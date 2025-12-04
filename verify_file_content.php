<?php
$file = 'app/Repository/ModerationRepository.php';
if (file_exists($file)) {
    $lines = file($file);
    echo "Line 50: " . trim($lines[49]) . "\n"; // 0-indexed, so 49 is line 50
    
    // Search for any occurrence of 'pending' in the file
    $content = file_get_contents($file);
    if (strpos($content, "'pending'") !== false) {
        echo "WARNING: Found 'pending' in file!\n";
    } else {
        echo "Clean: No 'pending' string found in file.\n";
    }
} else {
    echo "File not found.\n";
}
