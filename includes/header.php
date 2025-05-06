<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domino's Inspired Platform</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- PWA Manifest Link -->
    <link rel="manifest" href="/manifest.json">
    <!-- Additional PWA Meta Tags -->
    <meta name="theme-color" content="#d32f2f">
    <meta name="description" content="Domino's Pizza inspired online platform">
    <!-- iOS Support -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <link rel="apple-touch-icon" href="/images/icon-192x192.png">
</head>
<body>
    <header>
        <h1>Domino's Pizza</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="menu.php">Menu</a>
            <a href="cart.php">Cart</a>
            <a href="promotions.php">Promotions</a>
            <a href="reviews.php">Reviews</a>
            <a href="customer_support.php">Support</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">
    <!-- Load all JavaScript files -->
    <script src="js/script.js"></script>
    <script src="js/menu.js"></script>
    <script src="js/cart.js"></script>
    <script src="js/tracker.js"></script>
    <script src="js/geolocation.js"></script>
    <script src="js/chat.js"></script>
    <script src="js/promotions.js"></script>
    <script src="js/validation.js"></script>
    <script src="js/toast.js"></script>
    <script>
        // Register the service worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('Service worker registered:', registration);
                    })
                    .catch(error => {
                        console.error('Service worker registration failed:', error);
                    });
            });
        }
    </script>