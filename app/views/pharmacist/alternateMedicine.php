<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - Alternative Medicine Review</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Pharmacist/alternateMedicine.css">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <?php include 'sidebar.php'; ?>

        <!-- Main Workspace -->
        <main class="main-content">
            <!-- Top App Header Section -->
            <?php 
                $pageTitle = "Altenate Medicne"; 
                include APP_PATH . '/views/Pharmacist/header.php'; 
            ?>

            <h2 class="page-title">Alternative Medicine Review</h2>

            <!-- Top Informational Notification Banner -->
            <div class="info-banner">
                <i data-lucide="info" class="banner-icon"></i>
                <p>The selected medicines were checked against current inventory. One or more medicines are unavailable or have insufficient stock. Please review the suggested alternatives before continuing.</p>
            </div>

            <!-- Two Column Core Split Grid -->
            <div class="workspace-grid">
                
                <!-- Left Column: Alternative Selection Blocks -->
                <div class="alternatives-stack">
                    <section class="card master-alternatives-card">
                        <div class="card-section-header">
                            <h3>Unavailable Medicines</h3>
                            <span class="badge-count">2 ITEMS IDENTIFIED</span>
                        </div>

                        <!-- Medicine Item 1 Block: Panadol -->
                        <div class="medicine-review-block">
                            <div class="med-status-row">
                                <div>
                                    <h4 class="med-title">Panadol 500mg Tablet</h4>
                                    <p class="med-qty-label">Requested Qty: 20 Tablets</p>
                                </div>
                                <span class="badge-stock stock-out">OUT OF STOCK</span>
                            </div>

                            <div class="alternatives-section">
                                <h5>SUGGESTED ALTERNATIVES</h5>
                                
                                <!-- Alternative Option 1: Selected Recommended -->
                                <label class="alt-option-card selected">
                                    <div class="option-left">
                                        <input type="radio" name="panadol-alt" checked>
                                        <span class="alt-name">Paracetamol 500mg</span>
                                    </div>
                                    <span class="badge-recommended">RECOMMENDED</span>
                                </label>

                                <!-- Alternative Option 2 -->
                                <label class="alt-option-card">
                                    <div class="option-left">
                                        <input type="radio" name="panadol-alt">
                                        <span class="alt-name">Tylenol Extra Strength</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Medicine Item 2 Block: Cetirizine -->
                        <div class="medicine-review-block no-border">
                            <div class="med-status-row">
                                <div>
                                    <h4 class="med-title">Cetirizine 10mg</h4>
                                    <p class="med-qty-label">Requested Qty: 30 Capsules</p>
                                </div>
                                <span class="badge-stock stock-insufficient">INSUFFICIENT STOCK (5/30)</span>
                            </div>

                            <!-- Empty Error Fallback Banner -->
                            <div class="error-inline-banner">
                                <i data-lucide="triangle-alert"></i>
                                <span>No suitable alternative medicine found in current system inventory.</span>
                            </div>

                            <!-- Local Component Control Actions -->
                            <div class="local-actions-row">
                                <button class="btn btn-secondary"><i data-lucide="package-open"></i> Continue as Partial Order</button>
                                <button class="btn btn-danger"><i data-lucide="trash-2"></i> Remove Medicine</button>
                            </div>
                            
                            <button class="btn-cancel-link">Cancel Processing</button>
                        </div>
                    </section>
                </div>

                <!-- Right Column: Cost and Order Metrics Sidebar Card -->
                <div class="metrics-sidebar">
                    <section class="card impact-card">
                        <h3>Order Impact</h3>
                        
                        <div class="impact-metrics-list">
                            <div class="metric-row">
                                <span class="metric-label">Original Total</span>
                                <span class="metric-val text-dark">Rs. 400</span>
                            </div>
                            <div class="metric-row highlight-row">
                                <span class="metric-label">Updated Total</span>
                                <span class="metric-val text-teal">Rs. --</span>
                            </div>
                        </div>

                        <div class="summary-counters">
                            <div class="counter-row">
                                <span>Substitutions Applied</span>
                                <span class="counter-num">1</span>
                            </div>
                            <div class="counter-row">
                                <span>Pending Resolution</span>
                                <span class="counter-num color-alert">1</span>
                            </div>
                        </div>
                    </section>
                </div>

            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>