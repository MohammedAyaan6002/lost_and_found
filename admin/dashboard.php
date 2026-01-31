<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/header.php';

$pendingStmt = $mysqli->prepare("SELECT * FROM items WHERE status = 'pending' ORDER BY created_at ASC");
$pendingStmt->execute();
$pendingItems = $pendingStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$recentStmt = $mysqli->prepare("SELECT * FROM items ORDER BY created_at DESC LIMIT 5");
$recentStmt->execute();
$recentItems = $recentStmt->get_result()->fetch_all(MYSQLI_ASSOC);

$matchStmt = $mysqli->prepare("SELECT * FROM match_logs ORDER BY created_at DESC LIMIT 5");
$matchStmt->execute();
$matchLogs = $matchStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 admin-sidebar py-4">
            <h5 class="px-3 mb-4">Admin Panel</h5>
            <a href="#" class="active">Dashboard</a>
            <a href="#pending">Pending Items</a>
            <a href="#matches">AI Suggestions</a>
            <a href="#recent">Recent Activity</a>
        </aside>
        <section class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4 text-primary">Welcome, Admin</h2>
            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Pending Approval</h5>
                            <p class="display-6 mb-0"><?php echo count($pendingItems); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Recent Items</h5>
                            <p class="display-6 mb-0"><?php echo count($recentItems); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">AI Matches Logged</h5>
                            <p class="display-6 mb-0"><?php echo count($matchLogs); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pending" class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Pending Items</span>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Type</th>
                                <th>Submitted</th>
                                <th>Submitted By</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($pendingItems)): ?>
                            <tr><td colspan="5" class="text-center py-4">No pending items.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($pendingItems as $item): ?>
                            <tr>
                                <td><?php echo $item['item_name']; ?></td>
                                <td><span class="badge bg-secondary text-uppercase"><?php echo $item['item_type']; ?></span></td>
                                <td><?php echo format_date($item['created_at']); ?></td>
                                <td><?php echo $item['contact_email']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-success me-2 btnApprove" data-id="<?php echo $item['id']; ?>">Approve</button>
                                    <button class="btn btn-sm btn-outline-danger btnReject" data-id="<?php echo $item['id']; ?>">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="matches" class="card shadow-sm mb-4">
                <div class="card-header bg-success text-white">AI Match Suggestions</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                        <tr>
                            <th>Lost Item</th>
                            <th>Found Item</th>
                            <th>Score</th>
                            <th>Logged</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($matchLogs)): ?>
                            <tr><td colspan="4" class="text-center py-4">No AI matches yet.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($matchLogs as $log): ?>
                            <tr>
                                <td><?php echo $log['lost_item_name']; ?></td>
                                <td><?php echo $log['found_item_name']; ?></td>
                                <td><?php echo number_format($log['score'] * 100, 1); ?>%</td>
                                <td><?php echo format_date($log['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="recent" class="card shadow-sm">
                <div class="card-header bg-light">Recent Activity</div>
                <div class="list-group list-group-flush">
                    <?php foreach ($recentItems as $item): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo $item['item_name']; ?></strong>
                                <div class="small text-muted"><?php echo ucfirst($item['item_type']); ?> · <?php echo $item['status']; ?></div>
                            </div>
                            <small><?php echo format_date($item['created_at']); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
document.querySelectorAll('.btnApprove, .btnReject').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id = btn.dataset.id;
        const action = btn.classList.contains('btnApprove') ? 'approve' : 'reject';
        const baseUrl = document.body.dataset.baseUrl || '';
        try {
            const response = await fetch(`${baseUrl}/api/moderate-item.php`, {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ id, action })
            });
            const data = await response.json();
            alert(data.message);
            if (data.success) {
                window.location.reload();
            }
        } catch (error) {
            alert('Failed to moderate item. Check server logs.');
        }
    });
});
</script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

