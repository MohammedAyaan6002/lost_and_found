<?php
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_post_method();

$payload = json_decode(file_get_contents('php://input'), true);
$description = sanitize_input($payload['description'] ?? '');

if (empty($description)) {
    json_response(['success' => false, 'message' => 'Description required'], 422);
}

$stmt = $mysqli->prepare("SELECT id, item_name, description, location, item_type FROM items WHERE status = 'approved'");
$stmt->execute();
$items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$ch = curl_init(AI_SERVICE_URL);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode([
        'query' => $description,
        'items' => $items
    ]),
    CURLOPT_TIMEOUT => 10
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

// Check for connection errors first
if ($curlError) {
    json_response([
        'success' => false, 
        'message' => 'AI service is not running. Please start the Flask AI service.', 
        'error' => $curlError,
        'help' => 'Run START_FLASK_AI.bat in the project root to start the AI service on port 5001'
    ], 502);
}

// Check HTTP status code
if ($httpCode !== 200) {
    json_response([
        'success' => false, 
        'message' => 'AI service returned an error', 
        'http_code' => $httpCode,
        'response' => $response
    ], $httpCode >= 500 ? 502 : 400);
}

// Check if response is empty
if (empty($response)) {
    json_response([
        'success' => false, 
        'message' => 'AI service returned empty response. Make sure Flask service is running on port 5001.'
    ], 502);
}

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    json_response([
        'success' => false, 
        'message' => 'Invalid AI response', 
        'error' => json_last_error_msg(),
        'raw_response' => substr($response, 0, 200)
    ], 500);
}

if (!empty($data['matches'])) {
    $stmtLog = $mysqli->prepare("INSERT INTO match_logs (lost_item_name, found_item_name, score) VALUES (?, ?, ?)");
    $notificationStmt = $mysqli->prepare("INSERT INTO notifications (item_id, channel, message) VALUES (?, 'email', ?)");
    foreach ($data['matches'] as $match) {
        $lostName = $match['query_label'] ?? 'Search';
        $foundName = $match['item_name'];
        $score = $match['score'];
        $stmtLog->bind_param('ssd', $lostName, $foundName, $score);
        $stmtLog->execute();

        if ($score >= 0.6 && isset($match['item_id'])) {
            $message = sprintf('Potential match found: %s (score %.1f%%)', $foundName, $score * 100);
            $notificationStmt->bind_param('is', $match['item_id'], $message);
            $notificationStmt->execute();
        }
    }
}

json_response(['success' => true, 'matches' => $data['matches'] ?? []]);

