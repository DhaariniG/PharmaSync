<!-- ============ MAIN CONTENT ============ -->
<div class="main-content">

    

    <div class="content-area">

        <div class="page-header-block">
            <h1 class="page-title">Stock Movements</h1>
            <p class="page-subtitle">Track all stock changes across all medicine batches.</p>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon total"><?= icon('activity', 'material-symbols-outlined') ?></div>
                <div>
                    <p class="summary-label">Total Movements</p>
                    <h3 class="summary-value">284</h3>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon in"><?= icon('plus', 'material-symbols-outlined') ?></div>
                <div>
                    <p class="summary-label">Stock In</p>
                    <h3 class="summary-value in">156</h3>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon out"><?= icon('minus', 'material-symbols-outlined') ?></div>
                <div>
                    <p class="summary-label">Stock Out</p>
                    <h3 class="summary-value out">128</h3>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <div class="action-bar-left">
                <div class="search-box">
                    <?= icon('search', 'material-symbols-outlined') ?>
                    <input type="text" placeholder="Search by medicine, batch, or reference...">
                </div>
                <select class="type-select">
                    <option>Movement Type</option>
                    <option>Stock In</option>
                    <option>Stock Out</option>
                    <option>Disposed</option>
                </select>
                <div class="date-range-chip">
                    <?= icon('calendar', 'material-symbols-outlined') ?>
                    <span>May 17, 2025 - May 22, 2025</span>
                </div>
            </div>
            <button class="btn-export">
                <?= icon('download', 'material-symbols-outlined') ?>
                Export CSV
            </button>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Batch Number</th>
                        <th>Movement Type</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Reference</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-name">Paracetamol 500mg</td>
                        <td class="td-muted">BATCH-2024-001</td>
                        <td><span class="movement-badge in">Stock In</span></td>
                        <td><span class="qty-value in">+200</span></td>
                        <td class="td-muted">May 22, 2025</td>
                        <td>PO#P00587</td>
                        <td><span class="td-note">Received from HealthCorp</span></td>
                    </tr>
                    <tr>
                        <td class="td-name">Amoxicillin 250mg</td>
                        <td class="td-muted">BATCH-2024-002</td>
                        <td><span class="movement-badge out">Stock Out</span></td>
                        <td><span class="qty-value out">-50</span></td>
                        <td class="td-muted">May 21, 2025</td>
                        <td>ORD#12342</td>
                        <td><span class="td-note">Dispensed for order</span></td>
                    </tr>
                    <tr>
                        <td class="td-name">Vitamin D3</td>
                        <td class="td-muted">BATCH-2024-003</td>
                        <td><span class="movement-badge in">Stock In</span></td>
                        <td><span class="qty-value in">+300</span></td>
                        <td class="td-muted">May 20, 2025</td>
                        <td>PO#P00585</td>
                        <td><span class="td-note">Received from PharmaLife</span></td>
                    </tr>
                    <tr>
                        <td class="td-name">Cetirizine 10mg</td>
                        <td class="td-muted">BATCH-2024-004</td>
                        <td><span class="movement-badge disposed">Disposed</span></td>
                        <td><span class="qty-value disposed">-15</span></td>
                        <td class="td-muted">May 19, 2025</td>
                        <td>DISP#001</td>
                        <td><span class="td-note">Expired batch disposed</span></td>
                    </tr>
                    <tr>
                        <td class="td-name">Metformin 500mg</td>
                        <td class="td-muted">BATCH-2024-005</td>
                        <td><span class="movement-badge out">Stock Out</span></td>
                        <td><span class="qty-value out">-80</span></td>
                        <td class="td-muted">May 18, 2025</td>
                        <td>ORD#12340</td>
                        <td><span class="td-note">Dispensed for order</span></td>
                    </tr>
                    <tr>
                        <td class="td-name">Salbutamol Inhaler</td>
                        <td class="td-muted">BATCH-2024-006</td>
                        <td><span class="movement-badge in">Stock In</span></td>
                        <td><span class="qty-value in">+80</span></td>
                        <td class="td-muted">May 17, 2025</td>
                        <td>PO#P00582</td>
                        <td><span class="td-note">Received from MedStock</span></td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination-row">
                <p class="pagination-info">Showing 1 to 6 of 284 movements</p>
                <div class="pagination-buttons">
                    <button class="page-btn" disabled><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px; transform:rotate(180deg);') ?></button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <span class="page-dots">...</span>
                    <button class="page-btn">48</button>
                    <button class="page-btn"><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px;') ?></button>
                </div>
            </div>
        </div>

        <div class="widgets-row">
            <div class="widget-card">
                <div class="widget-header">
                    <h3>Recent Batch Disposal</h3>
                    <a href="#" class="view-all-link">View All</a>
                </div>
                <div class="disposal-item">
                    <div class="disposal-item-left">
                        <div class="disposal-icon"><?= icon('trash-2', 'material-symbols-outlined') ?></div>
                        <div>
                            <p class="disposal-med-name">Vitamin C (Exp 05/25)</p>
                            <p class="disposal-batch">Batch: BATCH-VTC-90</p>
                        </div>
                    </div>
                    <div>
                        <p class="disposal-qty">-150 Units</p>
                        <p class="disposal-time">2 hours ago</p>
                    </div>
                </div>
            </div>

            <div class="widget-card">
                <div class="widget-header">
                    <h3>Supply Chain Status</h3>
                    <span class="live-update-tag">Live Update</span>
                </div>
                <div class="status-row">
                    <span class="label">Pending Orders</span>
                    <span class="value">12</span>
                </div>
                <div class="progress-track"><div class="progress-fill" style="width:65%;"></div></div>
                <div class="status-row">
                    <span class="label">Average Lead Time</span>
                    <span class="value">4.2 Days</span>
                </div>
                <div class="progress-track"><div class="progress-fill secondary" style="width:40%;"></div></div>
            </div>
        </div>

    </div>
</div>
