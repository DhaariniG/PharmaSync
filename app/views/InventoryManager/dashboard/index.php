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
                    <p class="stat-value">452</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon error">
                    <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="stat-label">Low Stock</p>
                    <p class="stat-value">23</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon warning">
                    <?= icon('calendar', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="stat-label">Near Expiry</p>
                    <p class="stat-value">17</p>
                </div>
            </div>
            <div class="card stat-card">
                <div class="stat-icon primary">
                    <?= icon('store', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="stat-label">Total Suppliers</p>
                    <p class="stat-value">12</p>
                </div>
            </div>
        </div>

        <!-- Row 2: chart + low stock list -->
        <div class="row-10col">
            <div class="card col-span-6">
                <div class="card-header">
                    <h3>Stock Overview</h3>
                    <span class="page-subtitle">Last 7 Days</span>
                </div>
                <svg viewBox="0 0 700 200" style="width:100%; height:200px;">
                    <path d="M0,160 Q100,120 150,140 T300,80 T450,110 T600,60 T700,90"
                          fill="none" stroke="#008B8B" stroke-width="3" stroke-linejoin="round"/>
                    <path d="M0,160 Q100,120 150,140 T300,80 T450,110 T600,60 T700,90 L700,200 L0,200 Z"
                          fill="#008B8B" fill-opacity="0.1"/>
                    <circle cx="150" cy="140" r="4" fill="#008B8B"/>
                    <circle cx="300" cy="80" r="4" fill="#008B8B"/>
                    <circle cx="450" cy="110" r="4" fill="#008B8B"/>
                    <circle cx="600" cy="60" r="4" fill="#008B8B"/>
                </svg>
                <div class="chart-days">
                    <span>Mon</span><span>Tue</span><span>Wed</span><span>Thu</span>
                    <span>Fri</span><span>Sat</span><span>Sun</span>
                </div>
            </div>

            <div class="card col-span-4">
                <div class="card-header">
                    <h3>Low Stock Alerts</h3>
                    <a href="<?= url('/InventoryManager/low-stock') ?>" class="view-all-link">View All</a>
                </div>

                <div class="alert-item">
                    <div class="alert-item-left">
                        <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                        <div>
                            <p class="alert-med-name">Paracetamol</p>
                            <p class="alert-med-detail">500mg Tablet</p>
                        </div>
                    </div>
                    <div>
                        <p class="alert-qty">120 Units</p>
                        <p class="alert-min">Min: 500</p>
                    </div>
                </div>
                <div class="alert-item">
                    <div class="alert-item-left">
                        <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                        <div>
                            <p class="alert-med-name">Amoxicillin</p>
                            <p class="alert-med-detail">250mg Capsule</p>
                        </div>
                    </div>
                    <div>
                        <p class="alert-qty">45 Units</p>
                        <p class="alert-min">Min: 200</p>
                    </div>
                </div>
                <div class="alert-item">
                    <div class="alert-item-left">
                        <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                        <div>
                            <p class="alert-med-name">Cetirizine</p>
                            <p class="alert-med-detail">10mg Syrup</p>
                        </div>
                    </div>
                    <div>
                        <p class="alert-qty">15 Units</p>
                        <p class="alert-min">Min: 50</p>
                    </div>
                </div>
                <div class="alert-item">
                    <div class="alert-item-left">
                        <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                        <div>
                            <p class="alert-med-name">Salbutamol</p>
                            <p class="alert-med-detail">Inhaler 100mcg</p>
                        </div>
                    </div>
                    <div>
                        <p class="alert-qty">8 Units</p>
                        <p class="alert-min">Min: 30</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: expiry table + predictive card -->
        <div class="row-10col">
            <div class="card table-card col-span-6">
                <div class="table-card-header">
                    <h3>Expiry Alerts</h3>
                    <div>
                        <button class="btn-outline">Download CSV</button>
                        <button class="btn-primary">Filter</button>
                    </div>
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
                            <td>Insulin Glargine</td>
                            <td>BATCH-8921</td>
                            <td>Oct 24, 2024</td>
                            <td>150</td>
                            <td class="align-right"><span class="status-badge critical">Critical</span></td>
                        </tr>
                        <tr>
                            <td>Metformin HCL</td>
                            <td>BATCH-7742</td>
                            <td>Nov 12, 2024</td>
                            <td>2,400</td>
                            <td class="align-right"><span class="status-badge expiring-soon">Expiring Soon</span></td>
                        </tr>
                        <tr>
                            <td>Atorvastatin</td>
                            <td>BATCH-4410</td>
                            <td>Dec 05, 2024</td>
                            <td>850</td>
                            <td class="align-right"><span class="status-badge expiring-soon">Expiring Soon</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card col-span-4">
                <div class="predictive-card-header">
                    <div>
                        <h3>Predictive Inventory</h3>
                        <p class="predictive-subtitle">AI-driven restocking insights</p>
                    </div>
                    <?= icon('flask-conical', 'material-symbols-outlined') ?>
                </div>

                <div class="predictive-item">
                    <div class="predictive-item-top">
                        <p class="predictive-med-name">Lisinopril 10mg</p>
                        <span class="predictive-tag">
                            <?= icon('activity', 'material-symbols-outlined', 'font-size:14px;') ?>
                            Recommended
                        </span>
                    </div>
                    <p class="predictive-note">Usage increased by 14% this month. Order 500 units to avoid shortfall by week 3.</p>
                </div>
                <div class="predictive-item">
                    <div class="predictive-item-top">
                        <p class="predictive-med-name">Amlodipine 5mg</p>
                        <span class="predictive-tag">
                            <?= icon('activity', 'material-symbols-outlined', 'font-size:14px;') ?>
                            Recommended
                        </span>
                    </div>
                    <p class="predictive-note">Lead time for Supplier X has increased. Suggest ordering 2 weeks earlier than scheduled.</p>
                </div>

                
            </div>
        </div>

    </div>
</main>

<button class="fab">
    <?= icon('plus', 'material-symbols-outlined', 'font-size:28px;') ?>
</button>
