<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

    

    <div class="content-area">

        <div class="page-header-block">
            <h2 class="page-title">Expiry Alerts</h2>
            <p class="page-subtitle">Medicine batches that are expired or expiring soon.</p>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon expired"><?= icon('calendar', 'material-symbols-outlined') ?></div>
                <div>
                    <p class="summary-label">Already Expired</p>
                    <p class="summary-value expired">5</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon soon30"><?= icon('triangle-alert', 'material-symbols-outlined') ?></div>
                <div>
                    <p class="summary-label">Expiring in 30 Days</p>
                    <p class="summary-value soon30">12</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon soon60"><?= icon('clock', 'material-symbols-outlined') ?></div>
                <div>
                    <p class="summary-label">Expiring in 60 Days</p>
                    <p class="summary-value soon60">17</p>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <div class="search-box">
                <?= icon('search', 'material-symbols-outlined') ?>
                <input type="text" placeholder="Search medicines or batches...">
            </div>
            <select class="status-filter">
                <option>All Status</option>
                <option>Expired</option>
                <option>Expiring Soon</option>
            </select>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Batch Number</th>
                        <th>Expiry Date</th>
                        <th>Quantity</th>
                        <th>Days Until Expiry</th>
                        <th>Status</th>
                        <th class="align-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-name">Insulin Glargine</td>
                        <td>BATCH-8921</td>
                        <td class="td-expiry-critical">Oct 24, 2024</td>
                        <td>150</td>
                        <td><span class="days-badge expired">Expired</span></td>
                        <td><span class="status-flag expired"><span class="status-dot expired"></span>Expired</span></td>
                        <td class="align-right"><button class="btn-dispose">Mark Disposed</button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Metformin HCL</td>
                        <td>BATCH-7742</td>
                        <td class="td-expiry-critical">Nov 12, 2024</td>
                        <td>2400</td>
                        <td><span class="days-badge expired">Expired</span></td>
                        <td><span class="status-flag expired"><span class="status-dot expired"></span>Expired</span></td>
                        <td class="align-right"><button class="btn-dispose">Mark Disposed</button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Atorvastatin</td>
                        <td>BATCH-4410</td>
                        <td class="td-expiry-warning">Dec 05, 2024</td>
                        <td>850</td>
                        <td><span class="days-badge soon30">12 Days</span></td>
                        <td><span class="status-flag soon30"><span class="status-dot soon30"></span>Expiring Soon</span></td>
                        <td class="align-right"><button class="btn-dispose">Mark Disposed</button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Paracetamol 500mg</td>
                        <td>BATCH-2024-001</td>
                        <td class="td-expiry-warning">Jun 15, 2026</td>
                        <td>50</td>
                        <td><span class="days-badge soon30">24 Days</span></td>
                        <td><span class="status-flag soon30"><span class="status-dot soon30"></span>Expiring Soon</span></td>
                        <td class="align-right"><button class="btn-dispose">Mark Disposed</button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Vitamin D3</td>
                        <td>BATCH-2024-005</td>
                        <td class="td-expiry-notice">Jul 01, 2026</td>
                        <td>30</td>
                        <td><span class="days-badge soon60">40 Days</span></td>
                        <td><span class="status-flag soon60"><span class="status-dot soon60"></span>Expiring</span></td>
                        <td class="align-right"><button class="btn-dispose">Mark Disposed</button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Amoxicillin 250mg</td>
                        <td>BATCH-2024-003</td>
                        <td class="td-expiry-notice">Jul 10, 2026</td>
                        <td>20</td>
                        <td><span class="days-badge soon60">49 Days</span></td>
                        <td><span class="status-flag soon60"><span class="status-dot soon60"></span>Expiring</span></td>
                        <td class="align-right"><button class="btn-dispose">Mark Disposed</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</main>
