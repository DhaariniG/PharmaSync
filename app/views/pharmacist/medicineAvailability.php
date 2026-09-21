<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PharmaSync - Medicine Availability</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/Pharmacist/medicineAvailability.css">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar Navigation -->
        <?php include 'sidebar.php'; ?>
        <!-- Main Dashboard Content -->
        <main class="main-content">
            <!-- Top Header Navbar -->
            <?php 
                $pageTitle = "Medicine Availability"; 
                include APP_PATH . '/views/Pharmacist/header.php'; 
            ?>

            <!-- Search and Filter Panel -->
            <section class="card filter-card">
                <div class="filter-flex-container">
                    <div class="search-input-wrapper">
                        <i data-lucide="search" class="search-icon"></i>
                        <input type="text" placeholder="Search medicine by name, generic name, or category...">
                    </div>
                    <div class="select-wrapper">
                        <select>
                            <option>All Categories</option>
                        </select>
                        <i data-lucide="chevron-down" class="dropdown-chevron"></i>
                    </div>
                    <button class="btn-apply"><i data-lucide="sliders-horizontal"></i> Apply Filters</button>
                </div>
            </section>

            <!-- Product Status Grid Row -->
            <section class="medicine-status-grid">
                <!-- Card 1 -->
                <div class="card medicine-card">
                    <div class="med-card-header">
                        <div>
                            <h3 class="med-name">Sertraline 50mg</h3>
                            <p class="med-generic">Generic: Sertraline Hydrochloride</p>
                        </div>
                        <span class="badge badge-antidepressant">Antidepressant</span>
                    </div>
                    <div class="stock-info">
                        <div>
                            <p class="stock-label">CURRENT STOCK</p>
                            <p class="stock-count count-low">12 <span class="unit">units</span></p>
                        </div>
                        <span class="status-indicator ind-low"><span class="dot">•</span> Low Stock</span>
                    </div>
                    <div class="med-card-footer">
                        <span class="expiry-warn"><i data-lucide="triangle-alert"></i> Expi: Oct 28, 2023</span>
                        <a href="#" class="link-batches">View Batches <i data-lucide="chevron-right"></i></a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card medicine-card">
                    <div class="med-card-header">
                        <div>
                            <h3 class="med-name">Metformin 500mg</h3>
                            <p class="med-generic">Generic: Metformin Hydrochloride</p>
                        </div>
                        <span class="badge badge-antidiabetic">Antidiabetic</span>
                    </div>
                    <div class="stock-info">
                        <div>
                            <p class="stock-label">CURRENT STOCK</p>
                            <p class="stock-count count-low">45 <span class="unit">units</span></p>
                        </div>
                        <span class="status-indicator ind-low"><span class="dot">•</span> Low Stock</span>
                    </div>
                    <div class="med-card-footer">
                        <span class="expiry-warn"><i data-lucide="triangle-alert"></i> Expi: Nov 10, 2023</span>
                        <a href="#" class="link-batches">View Batches <i data-lucide="chevron-right"></i></a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card medicine-card">
                    <div class="med-card-header">
                        <div>
                            <h3 class="med-name">Insulin Glargine</h3>
                            <p class="med-generic">Generic: Insulin Glargine Injection</p>
                        </div>
                        <span class="badge badge-diabetes">Diabetes</span>
                    </div>
                    <div class="stock-info">
                        <div>
                            <p class="stock-label">CURRENT STOCK</p>
                            <p class="stock-count count-out">0 <span class="unit">units</span></p>
                        </div>
                        <span class="status-indicator ind-out"><span class="dot">•</span> Out of Stock</span>
                    </div>
                    <div class="med-card-footer">
                        <span class="expiry-normal"><i data-lucide="calendar"></i> Expi: N/A</span>
                        <a href="#" class="link-batches">View Batches <i data-lucide="chevron-right"></i></a>
                    </div>
                </div>
            </section>

            <!-- Table Header Sub Bar -->
            <div class="widget-subheader">
                <div class="sub-title"><i data-lucide="layers"></i> Recent Batch Details</div>
                <a href="#" class="btn-export">Export Report <i data-lucide="download"></i></a>
            </div>

            <!-- Recent Batch Details Table Container -->
            <section class="card table-card">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>BATCH NO.</th>
                                <th>MEDICINE NAME</th>
                                <th>QUANTITY</th>
                                <th>EXPIRY DATE</th>
                                <th>STORAGE ZONE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-medium">B-2023-045</td>
                                <td>Metformin 500mg</td>
                                <td>45 units</td>
                                <td><span class="td-expiry-warn"><i data-lucide="triangle-alert"></i> Nov 10, 2023</span></td>
                                <td><span class="zone-badge">Zone A-4</span></td>
                                <td><button class="btn-dots"><i data-lucide="more-vertical"></i></button></td>
                            </tr>
                            <tr>
                                <td class="font-medium">B-2023-098</td>
                                <td>Sertraline 50mg</td>
                                <td>12 units</td>
                                <td><span class="td-expiry-warn"><i data-lucide="triangle-alert"></i> Oct 28, 2023</span></td>
                                <td><span class="zone-badge">Zone C-2</span></td>
                                <td><button class="btn-dots"><i data-lucide="more-vertical"></i></button></td>
                            </tr>
                            <tr>
                                <td class="font-medium">B-2024-001</td>
                                <td>Amoxicillin 500mg</td>
                                <td>640 units</td>
                                <td class="text-muted">Dec 15, 2024</td>
                                <td><span class="zone-badge">Zone B-1</span></td>
                                <td><button class="btn-dots"><i data-lucide="more-vertical"></i></button></td>
                            </tr>
                            <tr>
                                <td class="font-medium">B-2024-012</td>
                                <td>Lisinopril 10mg</td>
                                <td>850 units</td>
                                <td class="text-muted">Jan 20, 2024</td>
                                <td><span class="zone-badge">Zone A-1</span></td>
                                <td><button class="btn-dots"><i data-lucide="more-vertical"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>