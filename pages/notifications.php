<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/header.php';

$stmt = $mysqli->prepare("SELECT n.*, i.item_name FROM notifications n INNER JOIN items i ON n.item_id = i.id ORDER BY n.created_at DESC");
$stmt->execute();
$notifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="mb-1 text-primary">Notifications</h2>
            <p class="text-muted mb-0">Alerts sent when a match is found.</p>
        </div>
        <button class="btn btn-outline-success mt-3 mt-md-0" data-demo-alert>Send Demo Alert</button>
    </div>
    <?php if (empty($notifications)): ?>
        <div class="alert alert-info">No notifications yet.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($notifications as $note): ?>
                <div class="list-group-item d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1"><?php echo $note['item_name']; ?></h6>
                        <p class="mb-1 small text-muted"><?php echo $note['message']; ?></p>
                        <span class="badge bg-secondary"><?php echo $note['channel']; ?></span>
                    </div>
                    <small><?php echo format_date($note['created_at']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

