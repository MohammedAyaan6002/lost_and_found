<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_post_method();

$payload = json_decode(file_get_contents('php://input'), true);
$itemId = isset($payload['id']) ? (int)$payload['id'] : 0;
$action = $payload['action'] ?? '';

if ($itemId <= 0 || !in_array($action, ['approve', 'reject'], true)) {
    json_response(['success' => false, 'message' => 'Invalid payload'], 422);
}

$status = $action === 'approve' ? 'approved' : 'rejected';
$stmt = $mysqli->prepare("UPDATE items SET status = ? WHERE id = ?");
$stmt->bind_param('si', $status, $itemId);

if ($stmt->execute()) {
    json_response(['success' => true, 'message' => "Item {$status}."]);
}

json_response(['success' => false, 'message' => 'Unable to update item.'], 500);

