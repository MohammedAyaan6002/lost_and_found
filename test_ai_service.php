<?php
/**
 * Test script to check if AI service is running and accessible
 */
require_once __DIR__ . '/includes/config.php';

$aiServiceUrl = AI_SERVICE_URL;
echo "Testing AI Service at: $aiServiceUrl\n";
echo str_repeat("=", 60) . "\n\n";

// Test 1: Check if service is reachable
echo "1. Testing connectivity...\n";
$ch = curl_init($aiServiceUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'query' => 'test query',
        'items' => [
            ['id' => 1, 'item_name' => 'Test Item', 'description' => 'A test item', 'location' => 'Test Location', 'item_type' => 'found']
        ]
    ]),
    CURLOPT_TIMEOUT => 5,
    CURLOPT_CONNECTTIMEOUT => 3
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
$curlErrno = curl_errno($ch);
curl_close($ch);

if ($curlErrno) {
    echo "❌ Connection failed!\n";
    echo "   Error: $curlError\n";
    echo "   Error Code: $curlErrno\n\n";
    echo "SOLUTION:\n";
    echo "   → The Flask AI service is not running.\n";
    echo "   → Run 'START_FLASK_AI.bat' to start the service.\n";
    echo "   → Make sure Python is installed and dependencies are installed.\n";
    exit(1);
}

echo "✅ Connection successful (HTTP $httpCode)\n\n";

// Test 2: Check health endpoint
echo "2. Testing health endpoint...\n";
$healthUrl = str_replace('/match', '/health', $aiServiceUrl);
$ch = curl_init($healthUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 5
]);
$healthResponse = curl_exec($ch);
$healthCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($healthCode === 200) {
    echo "✅ Health check passed\n";
    echo "   Response: $healthResponse\n\n";
} else {
    echo "⚠️  Health check returned HTTP $healthCode\n\n";
}

// Test 3: Test actual matching
echo "3. Testing AI matching...\n";
if ($httpCode === 200) {
    $data = json_decode($response, true);
    if ($data && isset($data['matches'])) {
        echo "✅ AI matching is working!\n";
        echo "   Found " . count($data['matches']) . " match(es)\n";
        if (!empty($data['matches'])) {
            echo "   Sample match score: " . ($data['matches'][0]['score'] * 100) . "%\n";
        }
    } else {
        echo "⚠️  Got response but format may be incorrect\n";
        echo "   Response: " . substr($response, 0, 200) . "\n";
    }
} else {
    echo "❌ Matching failed (HTTP $httpCode)\n";
    echo "   Response: " . substr($response, 0, 200) . "\n";
}

echo "\n" . str_repeat("=", 60) . "\n";
echo "✅ All tests completed!\n";


