<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

    

    <div class="content-area">

        <?php require __DIR__ . '/../partials/flash.php'; ?>

        <?php /* Sample data - the same POs appear on the Admin's PO Approvals page. */ ?>
        <div class="page-header-row">
            <div>
                <h2 class="page-title">Purchase Orders</h2>
                <p class="page-subtitle">Manage and track all medicine purchase orders.</p>
            </div>
                <a href="<?= url('/InventoryManager/purchase-orders/create') ?>" class="btn-add">
            <?= icon('plus', 'material-symbols-outlined', 'font-size:20px;') ?>
            Raise Purchase Order
        </a>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div>
                    <p class="summary-label">Total Orders</p>
                    <h3 class="summary-value">8</h3>
                </div>
                <div class="summary-icon total"><?= icon('shopping-cart', 'material-symbols-outlined') ?></div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Pending</p>
                    <h3 class="summary-value">3</h3>
                </div>
                <div class="summary-icon pending"><?= icon('clock', 'material-symbols-outlined') ?></div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Approved</p>
                    <h3 class="summary-value">2</h3>
                </div>
                <div class="summary-icon approved"><?= icon('circle-check', 'material-symbols-outlined') ?></div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Received</p>
                    <h3 class="summary-value">2</h3>
                </div>
                <div class="summary-icon received"><?= icon('package', 'material-symbols-outlined') ?></div>
            </div>
        </div>

        <div class="action-bar">
            <div class="search-box">
                <?= icon('search', 'material-symbols-outlined') ?>
                <input type="text" placeholder="Search orders...">
            </div>
            <div class="status-select-wrap">
                <select>
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Received</option>
                    <option>Rejected</option>
                </select>
                <?= icon('chevron-down', 'material-symbols-outlined') ?>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Medicine</th>
                        <th class="align-center">Quantity</th>
                        <th>Order Date</th>
                        <th>Status</th>
                        <th class="align-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="td-po-number">PO-2026-0112</td>
                        <td>Ceylon Pharma Distributors</td>
                        <td>
                            <span class="td-medicine-name">Amoxicillin 500mg &times; 300</span>
                            <span class="td-medicine-category">Antibiotic</span>
                            <span class="td-medicine-name">Azithromycin 250mg &times; 120</span>
                            <span class="td-medicine-category">Antibiotic</span>
                        </td>
                        <td class="td-center">420 Units</td>
                        <td>Sep 22, 2026</td>
                        <td><span class="status-pill pending">PENDING</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO-2026-0111</td>
                        <td>MedSupply Lanka (Pvt) Ltd</td>
                        <td>
                            <span class="td-medicine-name">Paracetamol 500mg</span>
                            <span class="td-medicine-category">Pain Relief</span>
                        </td>
                        <td class="td-center">500 Units</td>
                        <td>Sep 20, 2026</td>
                        <td><span class="status-pill pending">PENDING</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO-2026-0110</td>
                        <td>Colombo Wholesale Pharmaceuticals</td>
                        <td>
                            <span class="td-medicine-name">Salbutamol Inhaler 100mcg &times; 60</span>
                            <span class="td-medicine-category">Respiratory</span>
                            <span class="td-medicine-name">Cetirizine 10mg &times; 200</span>
                            <span class="td-medicine-category">Antihistamine</span>
                        </td>
                        <td class="td-center">260 Units</td>
                        <td>Sep 17, 2026</td>
                        <td><span class="status-pill pending">PENDING</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO-2026-0109</td>
                        <td>MedSupply Lanka (Pvt) Ltd</td>
                        <td>
                            <span class="td-medicine-name">Metformin 500mg</span>
                            <span class="td-medicine-category">Diabetes</span>
                        </td>
                        <td class="td-center">400 Units</td>
                        <td>Sep 12, 2026</td>
                        <td><span class="status-pill approved">APPROVED</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO-2026-0108</td>
                        <td>Colombo Wholesale Pharmaceuticals</td>
                        <td>
                            <span class="td-medicine-name">Vitamin C 1000mg</span>
                            <span class="td-medicine-category">Supplements</span>
                        </td>
                        <td class="td-center">600 Units</td>
                        <td>Sep 08, 2026</td>
                        <td><span class="status-pill approved">APPROVED</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO-2026-0107</td>
                        <td>Ceylon Pharma Distributors</td>
                        <td>
                            <span class="td-medicine-name">Ibuprofen 400mg &times; 250</span>
                            <span class="td-medicine-category">Pain Relief</span>
                            <span class="td-medicine-name">Paracetamol 500mg &times; 300</span>
                            <span class="td-medicine-category">Pain Relief</span>
                        </td>
                        <td class="td-center">550 Units</td>
                        <td>Sep 02, 2026</td>
                        <td><span class="status-pill received">RECEIVED</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO-2026-0106</td>
                        <td>MedSupply Lanka (Pvt) Ltd</td>
                        <td>
                            <span class="td-medicine-name">Cetirizine 10mg</span>
                            <span class="td-medicine-category">Antihistamine</span>
                        </td>
                        <td class="td-center">150 Units</td>
                        <td>Aug 27, 2026</td>
                        <td><span class="status-pill received">RECEIVED</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO-2026-0105</td>
                        <td>Colombo Wholesale Pharmaceuticals</td>
                        <td>
                            <span class="td-medicine-name">Azithromycin 250mg</span>
                            <span class="td-medicine-category">Antibiotic</span>
                        </td>
                        <td class="td-center">80 Units</td>
                        <td>Aug 21, 2026</td>
                        <td><span class="status-pill rejected">REJECTED</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination-row">
                <p class="pagination-info">Showing 1 to 8 of 8 orders</p>
                <div class="pagination-buttons">
                    <button class="page-text-btn">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-text-btn">Next</button>
                </div>
            </div>
        </div>

    </div>
</main>
