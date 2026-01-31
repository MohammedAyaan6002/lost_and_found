<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/header.php';
$baseUrl = APP_BASE_URL;

$stmt = $mysqli->prepare("SELECT * FROM items WHERE status = 'approved' ORDER BY created_at DESC");
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
?>
<div class="container">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">
        <div>
            <h2 class="mb-1 text-primary">Latest Listings</h2>
            <p class="text-muted mb-0">Approved lost and found submissions.</p>
        </div>
        <a href="<?php echo $baseUrl; ?>/pages/search.php" class="btn btn-outline-primary mt-3 mt-md-0">Advanced Search</a>
    </div>
    <div class="row g-4">
        <?php if (empty($items)): ?>
            <div class="col-12">
                <div class="alert alert-info">No listings yet. Be the first to submit!</div>
            </div>
        <?php endif; ?>
        <?php foreach ($items as $item): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm <?php echo $item['item_type'] === 'lost' ? 'card-lost' : 'card-found'; ?>">
                    <?php if (!empty($item['image_path'])): ?>
                        <img src="<?php echo APP_BASE_URL . $item['image_path']; ?>" class="card-img-top" alt="<?php echo $item['item_name']; ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <span class="badge bg-secondary status-badge text-uppercase"><?php echo $item['item_type']; ?></span>
                        <h5 class="card-title mt-2"><?php echo $item['item_name']; ?></h5>
                        <p class="card-text text-muted small mb-2">Location: <?php echo $item['location']; ?></p>
                        <p class="card-text"><?php echo substr($item['description'], 0, 120); ?>...</p>
                        <p class="card-text"><small class="text-muted">Reported on <?php echo format_date($item['created_at']); ?></small></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

