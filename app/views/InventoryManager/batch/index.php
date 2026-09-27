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
                <div>
                    <p class="summary-label">Total Batches</p>
                    <h3 class="summary-value">10</h3>
                </div>
                <div class="summary-icon total">
                    <?= icon('package', 'material-symbols-outlined') ?>
                </div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Expiring Soon</p>
                    <h3 class="summary-value">2</h3>
                </div>
                <div class="summary-icon warning">
                    <?= icon('triangle-alert', 'material-symbols-outlined') ?>
                </div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Expired Batches</p>
                    <h3 class="summary-value">0</h3>
                </div>
                <div class="summary-icon danger">
                    <?= icon('circle-alert', 'material-symbols-outlined') ?>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <div class="search-box">
                <?= icon('search', 'material-symbols-outlined') ?>
                <input type="text" placeholder="Search batches...">
            </div>
            <div class="status-select-wrap">
                <select>
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Expiring Soon</option>
                    <option>Expired</option>
                </select>
                <?= icon('chevron-down', 'material-symbols-outlined') ?>
            </div>
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
                        <td class="td-muted">PCM-2026-01</td>
                        <td class="td-muted">26/07/2026</td>
                        <td class="td-muted">21/07/2027</td>
                        <td class="td-center">250</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Paracetamol 500mg</td>
                        <td class="td-muted">PCM-2026-02</td>
                        <td class="td-muted">14/09/2026</td>
                        <td class="td-muted">12/04/2027</td>
                        <td class="td-center">100</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Ibuprofen 400mg</td>
                        <td class="td-muted">IBU-2026-01</td>
                        <td class="td-muted">10/08/2026</td>
                        <td class="td-muted">01/06/2027</td>
                        <td class="td-center">180</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Amoxicillin 500mg</td>
                        <td class="td-muted">AMX-2026-01</td>
                        <td class="td-muted">27/04/2026</td>
                        <td class="td-warning">14/10/2026</td>
                        <td class="td-center td-danger">12</td>
                        <td><span class="badge badge-amber">Expiring Soon</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Azithromycin 250mg</td>
                        <td class="td-muted">AZI-2026-01</td>
                        <td class="td-muted">25/08/2026</td>
                        <td class="td-muted">21/02/2027</td>
                        <td class="td-center">150</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Cetirizine 10mg</td>
                        <td class="td-muted">CTZ-2026-01</td>
                        <td class="td-muted">26/06/2026</td>
                        <td class="td-muted">29/10/2027</td>
                        <td class="td-center td-danger">8</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Metformin 500mg</td>
                        <td class="td-muted">MET-2026-01</td>
                        <td class="td-muted">16/06/2026</td>
                        <td class="td-warning">04/10/2026</td>
                        <td class="td-center">200</td>
                        <td><span class="badge badge-amber">Expiring Soon</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Metformin 500mg</td>
                        <td class="td-muted">MET-2026-02</td>
                        <td class="td-muted">04/09/2026</td>
                        <td class="td-muted">21/07/2027</td>
                        <td class="td-center">50</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Salbutamol Inhaler 100mcg</td>
                        <td class="td-muted">SAL-2026-01</td>
                        <td class="td-muted">15/08/2026</td>
                        <td class="td-muted">23/03/2027</td>
                        <td class="td-center">60</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-name">Vitamin C 1000mg</td>
                        <td class="td-muted">VTC-2026-01</td>
                        <td class="td-muted">04/09/2026</td>
                        <td class="td-muted">24/09/2027</td>
                        <td class="td-center">500</td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-row">
            <p class="pagination-info">Showing 1 to 10 of 10 batches</p>
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
