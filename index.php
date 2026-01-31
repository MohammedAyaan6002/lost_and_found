<?php
require_once __DIR__ . '/includes/header.php';
$baseUrl = APP_BASE_URL;
?>
<section class="hero-section text-white text-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Loyola Lost &amp; Found Hub</h1>
        <p class="lead mt-3">Report, search, and match lost or found items across campus with AI-powered assistance.</p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3 mt-4">
            <a href="<?php echo $baseUrl; ?>/pages/report-lost.php" class="btn btn-success btn-lg">Report Lost Item</a>
            <a href="<?php echo $baseUrl; ?>/pages/report-found.php" class="btn btn-outline-light btn-lg">Report Found Item</a>
        </div>
    </div>
</section>

<section class="py-5 bg-light">
    <div class="container">
        <h2 class="mb-4 text-center">Quick Actions</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Browse Listings</h5>
                        <p class="card-text">View approved lost and found reports submitted by Loyola students and staff.</p>
                        <a href="<?php echo $baseUrl; ?>/pages/listings.php" class="btn btn-primary w-100">View Listings</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Search Items</h5>
                        <p class="card-text">Use keywords to find matching descriptions and see AI-powered suggestions.</p>
                        <a href="<?php echo $baseUrl; ?>/pages/search.php" class="btn btn-primary w-100">Search Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Receive Alerts</h5>
                        <p class="card-text">Get notified instantly via email when the system detects a possible match.</p>
                        <a href="<?php echo $baseUrl; ?>/pages/notifications.php" class="btn btn-primary w-100">View Notifications</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-md-6">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?auto=format&fit=crop&w=900&q=60" class="img-fluid rounded shadow" alt="Students collaborating">
            </div>
            <div class="col-md-6">
                <h2>Smart Matching with AI</h2>
                <p>The platform analyzes descriptions using NLP, TF-IDF, and cosine similarity to surface potential matches between lost and found reports. Admins can review suggestions, approve items, and notify owners.</p>
                <ul class="list-unstyled">
                    <li>✔️ Accurate similarity scoring</li>
                    <li>✔️ Admin review queue</li>
                    <li>✔️ Automatic notification pipeline</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<?php require_once __DIR__ . '/includes/footer.php'; ?>

