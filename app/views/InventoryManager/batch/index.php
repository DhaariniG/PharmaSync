<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

    <div class="content-area">

        <div class="page-header-row">
            <div>
                <h2 class="page-title">Stock &amp; Batches</h2>
                <p class="page-subtitle">Track all medicine batches and stock levels.</p>
            </div>
                    <a href="<?= url('/InventoryManager/batches/create') ?>" class="btn-add">
            <?= icon('plus', 'material-symbols-outlined') ?>
            Add New Batch
        </a>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon total">
                    <?= icon('package', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="summary-label">Total Batches</p>
                    <h3 class="summary-value">124</h3>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon warning">
                    <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="summary-label">Expiring Soon</p>
                    <h3 class="summary-value">17</h3>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon danger">
                    <?= icon('circle-alert', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="summary-label">Expired Batches</p>
                    <h3 class="summary-value">5</h3>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <div class="search-box">
                <?= icon('search', 'material-symbols-outlined') ?>
                <input type="text" placeholder="Search batches...">
            </div>
            <button class="filter-btn">
                <span>All Status</span>
                <?= icon('chevron-down', 'material-symbols-outlined') ?>
            </button>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Batch Number</th>
                        <th>Mfg. Date</th>
                        <th>Expiry Date</th>
                        <th class="align-center">Quantity</th>
                        <th>Status</th>
                        <th class="align-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-name">Paracetamol 500mg</td>
                        <td class="td-muted">PRC2023-001</td>
                        <td class="td-muted">12/10/2023</td>
                        <td class="td-muted">11/10/2025</td>
                        <td class="td-center">450</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right"><button class="icon-btn"><?= icon('ellipsis-vertical', 'material-symbols-outlined') ?></button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Amoxicillin 250mg</td>
                        <td class="td-muted">AMX2024-042</td>
                        <td class="td-muted">01/01/2024</td>
                        <td class="td-warning">15/05/2024</td>
                        <td class="td-center td-danger">30</td>
                        <td><span class="badge badge-amber">Expiring Soon</span></td>
                        <td class="align-right"><button class="icon-btn"><?= icon('ellipsis-vertical', 'material-symbols-outlined') ?></button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Vitamin D3</td>
                        <td class="td-muted">VD3-BN-99</td>
                        <td class="td-muted">15/08/2023</td>
                        <td class="td-muted">14/08/2026</td>
                        <td class="td-center">1,200</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right"><button class="icon-btn"><?= icon('ellipsis-vertical', 'material-symbols-outlined') ?></button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Cetirizine 10mg</td>
                        <td class="td-muted">CET-EXP-00</td>
                        <td class="td-muted">20/02/2022</td>
                        <td class="td-danger">01/03/2024</td>
                        <td class="td-center">215</td>
                        <td><span class="badge badge-red">Expired</span></td>
                        <td class="align-right"><button class="icon-btn"><?= icon('ellipsis-vertical', 'material-symbols-outlined') ?></button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Metformin 500mg</td>
                        <td class="td-muted">MET2024-11</td>
                        <td class="td-muted">10/01/2024</td>
                        <td class="td-muted">09/01/2027</td>
                        <td class="td-center">800</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right"><button class="icon-btn"><?= icon('ellipsis-vertical', 'material-symbols-outlined') ?></button></td>
                    </tr>
                    <tr>
                        <td class="td-name">Salbutamol Inhaler</td>
                        <td class="td-muted">SLB-INH-77</td>
                        <td class="td-muted">05/11/2023</td>
                        <td class="td-warning">10/06/2024</td>
                        <td class="td-center td-danger">8</td>
                        <td><span class="badge badge-amber">Expiring Soon</span></td>
                        <td class="align-right"><button class="icon-btn"><?= icon('ellipsis-vertical', 'material-symbols-outlined') ?></button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-row">
            <p class="pagination-info">Showing 1 to 6 of 124 batches</p>
            <div class="pagination-buttons">
                <button class="page-btn"><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px; transform:rotate(180deg);') ?></button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <span class="page-dots">...</span>
                <button class="page-btn">21</button>
                <button class="page-btn"><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px;') ?></button>
            </div>
        </div>

    </div>
</main>
