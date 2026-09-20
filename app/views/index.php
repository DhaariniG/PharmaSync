<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - Online Pharmacy & Healthcare Portal</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/index.css">
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="logo">
            <span class="brand">PharmaSync</span>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>/index.php">Home</a>
            <a href="#catalog">Browse Medicines</a>
            <a href="#contact">Contact Us</a>
        </nav>
        <div class="auth-buttons">
            <!-- Fixed link always pointing to login -->
            <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="btn-primary">Sign In</a>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Fast, Reliable Prescription & Medicine Delivery</h1>
            <p>Check medicine availability, upload prescriptions, and track your health orders online.</p>
            <div class="hero-actions">
                <a href="#catalog" class="btn-primary">Search Available Medicines</a>
                <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="btn-secondary">Upload Prescription</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="contact" class="footer">
        <div class="footer-info">
            <h3>PharmaSync Pharmacy</h3>
            <p>📞 Emergency Support: 011 1234567</p>
            <p>✉️ Email: support@pharmasync.com</p>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> PharmaSync. All rights reserved.</p>
        </div>
    </footer>

</body>
</html>