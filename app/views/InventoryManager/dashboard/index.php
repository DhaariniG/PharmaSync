<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

    <div class="content-area">

        <div>
            <h2 class="page-title">Inventory Overview</h2>
            <p class="page-subtitle">Real-time status of your pharmaceutical stock and supply chain.</p>
        </div>

        <!-- Row 1: stat cards -->
        <div class="stats-grid">
            <div class="card stat-card">
                <div class="stat-icon primary">
                    <?= icon('briefcase-medical', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="stat-label">Total Medicines</p>
                    <p class="stat-value">8</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon error">
                    <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="stat-label">Low Stock</p>
                    <p class="stat-value">2</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon warning">
                    <?= icon('calendar', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="stat-label">Near Expiry</p>
                    <p class="stat-value">2</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon primary">
                    <?= icon('store', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="stat-label">Total Suppliers</p>
                    <p class="stat-value">3</p>
                </div>
            </div>
        </div>

        <!-- Row 2: Low Stock Alerts + Expiry Alerts -->
        <div class="row-10col">
            <div class="card col-span-4">
                <div class="card-header">
                    <h3>Low Stock Alerts</h3>
                    <a href="<?= url('/InventoryManager/low-stock') ?>" class="view-all-link">View All</a>
                </div>

                <div class="alert-item">
                    <div class="alert-item-left">
                        <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                        <div>
                            <p class="alert-med-name">Amoxicillin 500mg</p>
                            <p class="alert-med-detail">Antibiotic</p>
                        </div>
                    </div>
                    <div>
                        <p class="alert-qty">12 Units</p>
                        <p class="alert-min">Min: 30</p>
                    </div>
                </div>
                <div class="alert-item">
                    <div class="alert-item-left">
                        <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                        <div>
                            <p class="alert-med-name">Cetirizine 10mg</p>
                            <p class="alert-med-detail">Antihistamine</p>
                        </div>
                    </div>
                    <div>
                        <p class="alert-qty">8 Units</p>
                        <p class="alert-min">Min: 25</p>
                    </div>
                </div>
            </div>

            <div class="card table-card col-span-6">
                <div class="table-card-header">
                    <h3>Expiry Alerts</h3>
                    <a href="<?= url('/InventoryManager/expiry-alerts') ?>" class="view-all-link">View All</a>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Medicine</th>
                            <th>Batch No</th>
                            <th>Expiry Date</th>
                            <th>Qty</th>
                            <th class="align-right">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Metformin 500mg</td>
                            <td>MET-2026-01</td>
                            <td>Oct 04, 2026</td>
                            <td>200</td>
                            <td class="align-right"><span class="status-badge critical">Critical</span></td>
                        </tr>
                        <tr>
                            <td>Amoxicillin 500mg</td>
                            <td>AMX-2026-01</td>
                            <td>Oct 14, 2026</td>
                            <td>12</td>
                            <td class="align-right"><span class="status-badge expiring-soon">Expiring Soon</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<a class="fab" href="<?= url('/InventoryManager/medicines/create') ?>" title="Add Medicine" aria-label="Add Medicine">
    <?= icon('plus', 'material-symbols-outlined', 'font-size:28px;') ?>
</a>
