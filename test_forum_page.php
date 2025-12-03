<?php
// Quick test to check forum page
$ch = curl_init('http://localhost:8000/forum');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";

if ($httpCode == 200) {
    echo "Forum page loads successfully!\n";
} else {
    echo "Error loading forum page\n";
    if (strpos($response, 'Fatal error') !== false) {
        preg_match('/<b>Fatal error<\/b>:(.+?)<br/', $response, $matches);
        if (isset($matches[1])) {
            echo "Error: " . strip_tags($matches[1]) . "\n";
        }
    }
    // Show first 500 chars of response
    echo "\nResponse preview:\n" . substr(strip_tags($response), 0, 500) . "\n";
}
