<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

  

    <div class="content-area">
        <div class="content-inner">

            <div class="page-header-block">
                <h1 class="page-title">Reports</h1>
                <p class="page-subtitle">Generate and view inventory reports for regulatory compliance and operational optimization.</p>
            </div>

            <div class="report-cards">

                <!-- Inventory Report -->
                <div class="report-card">
                    <div class="report-icon inventory"><?= icon('package', 'material-symbols-outlined') ?></div>
                    <h3>Inventory Report</h3>
                    <p class="report-card-desc">Complete snapshot of current stock levels across all categories and locations.</p>
                    <p class="report-includes-label">Report Includes</p>
                    <ul class="report-includes-list">
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> SKU Stock Status</li>
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> Category Distribution</li>
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> Total Asset Valuation</li>
                    </ul>
                    <div class="report-field">
                        <label>Date Range</label>
                        <div class="date-range-grid">
                            <input type="date">
                            <input type="date">
                        </div>
                    </div>
                    <button type="button" class="btn-generate inventory">
                        <?= icon('scroll-text', 'material-symbols-outlined') ?>
                        Generate Report
                    </button>
                </div>

                <!-- Expiry Report -->
                <div class="report-card">
                    <div class="report-icon expiry"><?= icon('clock', 'material-symbols-outlined') ?></div>
                    <h3>Expiry Report</h3>
                    <p class="report-card-desc">Identification of medications approaching their expiration dates within specific windows.</p>
                    <p class="report-includes-label">Report Includes</p>
                    <ul class="report-includes-list">
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> Near-Expiry Batches</li>
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> Disposal Forecasts</li>
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> Quality Control Audit</li>
                    </ul>
                    <div class="report-field">
                        <label>Expiry Window</label>
                        <select>
                            <option>Next 30 Days</option>
                            <option>Next 60 Days</option>
                            <option>Next 90 Days</option>
                            <option>Next 6 Months</option>
                        </select>
                    </div>
                    <button type="button" class="btn-generate expiry">
                        <?= icon('calendar', 'material-symbols-outlined') ?>
                        Generate Report
                    </button>
                </div>

                <!-- Purchase Order Report -->
                <div class="report-card">
                    <div class="report-icon po"><?= icon('scroll-text', 'material-symbols-outlined') ?></div>
                    <h3>Purchase Order Report</h3>
                    <p class="report-card-desc">Detailed analysis of procurement cycles, vendor fulfillment, and ordering costs.</p>
                    <p class="report-includes-label">Report Includes</p>
                    <ul class="report-includes-list">
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> PO Fulfillment Rates</li>
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> Supplier Lead Times</li>
                        <li><?= icon('circle-check', 'material-symbols-outlined') ?> Budget Utilization</li>
                    </ul>
                    <div class="report-field">
                        <label>Date Range</label>
                        <div class="date-range-grid">
                            <input type="date">
                            <input type="date">
                        </div>
                    </div>
                    <button type="button" class="btn-generate po">
                        <?= icon('scroll-text', 'material-symbols-outlined') ?>
                        Generate Report
                    </button>
                </div>

            </div>

            <div class="recent-header">
                <h3>Recently Generated Reports</h3>
                <button type="button" class="view-all-btn">
                    View All History
                    <?= icon('chevron-right', 'material-symbols-outlined') ?>
                </button>
            </div>

            <div class="reports-table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Report Name</th>
                            <th>Type</th>
                            <th>Generated On</th>
                            <th>Generated By</th>
                            <th class="align-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="file-cell">
                                    <?= icon('scroll-text', 'material-symbols-outlined') ?>
                                    <span class="file-name">Monthly_Inventory_Oct23.pdf</span>
                                </div>
                            </td>
                            <td><span class="type-tag inventory">Inventory</span></td>
                            <td class="td-muted">Oct 31, 2023 · 04:45 PM</td>
                            <td class="td-muted">IM User</td>
                            <td class="align-right">
                                <div class="row-actions">
                                    <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined') ?></button>
                                    <button class="icon-btn"><?= icon('scroll-text', 'material-symbols-outlined') ?></button>
                                    <button class="icon-btn"><?= icon('download', 'material-symbols-outlined') ?></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="file-cell">
                                    <?= icon('scroll-text', 'material-symbols-outlined') ?>
                                    <span class="file-name">Weekly_PO_Summary_W44.csv</span>
                                </div>
                            </td>
                            <td><span class="type-tag po">Purchase Order</span></td>
                            <td class="td-muted">Nov 02, 2023 · 09:12 AM</td>
                            <td class="td-muted">System Automated</td>
                            <td class="align-right">
                                <div class="row-actions">
                                    <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined') ?></button>
                                    <button class="icon-btn"><?= icon('scroll-text', 'material-symbols-outlined') ?></button>
                                    <button class="icon-btn"><?= icon('download', 'material-symbols-outlined') ?></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="file-cell">
                                    <?= icon('scroll-text', 'material-symbols-outlined') ?>
                                    <span class="file-name">Expiry_Risk_Audit_Q4.pdf</span>
                                </div>
                            </td>
                            <td><span class="type-tag expiry">Expiry</span></td>
                            <td class="td-muted">Oct 28, 2023 · 11:30 AM</td>
                            <td class="td-muted">IM User</td>
                            <td class="align-right">
                                <div class="row-actions">
                                    <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined') ?></button>
                                    <button class="icon-btn"><?= icon('scroll-text', 'material-symbols-outlined') ?></button>
                                    <button class="icon-btn"><?= icon('download', 'material-symbols-outlined') ?></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</main>
