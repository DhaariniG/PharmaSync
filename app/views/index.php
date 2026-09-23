<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - Modern Online Pharmacy</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/index.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>

    <!-- ANNOUNCEMENT BAR -->
    <div class="top-bar">
        ✨ Free doorstep delivery on all prescription orders over Rs. 2,500!
    </div>

    <!-- NAVBAR -->
    <header class="navbar">
        <a href="<?= BASE_URL ?>/index.php" class="logo">
            <div class="logo-badge">
                <i data-lucide="pill"></i>
            </div>
            PharmaSync
        </a>
        <nav class="nav-links">
            <a href="<?= BASE_URL ?>/index.php" class="active">Home</a>
            <a href="#services">Our Services</a>
            <a href="#contact">Contact Support</a>
        </nav>
        <div>
            <a href="<?= url(AUTH_SLUG . '/login') ?>" class="btn-sign-in">
    <i data-lucide="user"></i>
    <span>Sign In</span>
</a>
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i data-lucide="heart-pulse" style="width: 16px; height: 16px;"></i> Certified Digital Healthcare
                </div>
                <h1>Care Made Simple, <br><span>Medicines Made Fast.</span></h1>
                <p>Check live stock availability, upload prescriptions for instant verification, and enjoy friendly doorstep delivery from licensed pharmacists.</p>
                
                <div class="hero-actions">
                    <a href="<?= BASE_URL ?>/index.php?url=auth/login" class="btn-primary">
                        <i data-lucide="file-up"></i> Upload Prescription
                    </a>
                    <a href="#services" class="btn-secondary">
                        <i data-lucide="shield-check"></i> Explore Services
                    </a>
                </div>
            </div>

            <div class="hero-image-box">
                <img src="https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=1000&auto=format&fit=crop" alt="Pharmacy Care" class="hero-img">
                
                <div class="floating-badge top-left">
                    <div class="badge-icon">
                        <i data-lucide="shield-check"></i>
                    </div>
                    <div class="badge-text">
                        <strong>100% Certified</strong>
                        <span>Licensed Pharmacists</span>
                    </div>
                </div>

                <div class="floating-badge bottom-right">
                    <div class="badge-icon green">
                        <i data-lucide="truck"></i>
                    </div>
                    <div class="badge-text">
                        <strong>Express Dispatch</strong>
                        <span>Doorstep Delivery</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES SECTION -->
    <section id="services" class="services">
        <div class="section-title">
            <h2>Healthcare Built Around You</h2>
            <p>Managing your health and prescriptions has never been this seamless.</p>
        </div>

        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i data-lucide="file-text"></i>
                </div>
                <h3>Prescription Verification</h3>
                <p>Upload doctor slips or digital prescriptions. Our qualified pharmacists review and prepare your order promptly.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i data-lucide="search"></i>
                </div>
                <h3>Live Inventory Check</h3>
                <p>Never worry about availability. Check live stock levels and alternative medicine options before placing an order.</p>
            </div>

            <div class="service-card">
                <div class="service-icon">
                    <i data-lucide="truck"></i>
                </div>
                <h3>Safe Doorstep Delivery</h3>
                <p>Temperature-managed packaging and verified delivery partners ensure your health orders arrive in perfect condition.</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="contact" class="footer">
        <div class="footer-container">
            <div class="footer-brand">
                <h3><i data-lucide="pill"></i> PharmaSync</h3>
                <p>Your trusted digital pharmacy platform. Connecting patients, pharmacists, and healthcare providers seamlessly.</p>
            </div>
            
            <div class="footer-contact">
                <h4>Customer Care</h4>
                <p><i data-lucide="phone"></i> 011 1234567 (24/7 Support)</p>
                <p><i data-lucide="mail"></i> support@pharmasync.com</p>
                <p><i data-lucide="map-pin"></i> 123 Health Way, Colombo, Sri Lanka</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?= date('Y'); ?> PharmaSync Pharmacy. All rights reserved.</p>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>