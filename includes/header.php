<?php
require_once __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loyola Lost &amp; Found</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo APP_BASE_URL; ?>/assets/css/style.css">
</head>
<body data-base-url="<?php echo APP_BASE_URL; ?>">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo APP_BASE_URL; ?>/index.php">Loyola Lost &amp; Found</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="<?php echo APP_BASE_URL; ?>/pages/report-lost.php">Report Lost</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo APP_BASE_URL; ?>/pages/report-found.php">Report Found</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo APP_BASE_URL; ?>/pages/listings.php">Listings</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo APP_BASE_URL; ?>/pages/search.php">Search</a></li>
                <li class="nav-item"><a class="nav-link" href="<?php echo APP_BASE_URL; ?>/admin/dashboard.php">Admin</a></li>
            </ul>
        </div>
    </div>
</nav>
<main class="py-5 flex-grow-1">

