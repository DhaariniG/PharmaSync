<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

    

    <div class="content-area">

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
                    <h3 class="summary-value">48</h3>
                </div>
                <div class="summary-icon total"><?= icon('shopping-cart', 'material-symbols-outlined') ?></div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Pending</p>
                    <h3 class="summary-value">5</h3>
                </div>
                <div class="summary-icon pending"><?= icon('clock', 'material-symbols-outlined') ?></div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Approved</p>
                    <h3 class="summary-value">38</h3>
                </div>
                <div class="summary-icon approved"><?= icon('circle-check', 'material-symbols-outlined') ?></div>
            </div>
            <div class="summary-card">
                <div>
                    <p class="summary-label">Received</p>
                    <h3 class="summary-value">33</h3>
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
                        <td class="td-po-number">PO#P00587</td>
                        <td>HealthCorp Pvt Ltd (Colombo)</td>
                        <td>
                            <span class="td-medicine-name">Paracetamol 500mg</span>
                            <span class="td-medicine-category">Pain Relief</span>
                        </td>
                        <td class="td-center">200 Units</td>
                        <td>May 21, 2025</td>
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
                        <td class="td-po-number">PO#P00586</td>
                        <td>MediSupplies (Kandy)</td>
                        <td>
                            <span class="td-medicine-name">Amoxicillin 250mg</span>
                            <span class="td-medicine-category">Antibiotic</span>
                        </td>
                        <td class="td-center">150 Units</td>
                        <td>May 20, 2025</td>
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
                        <td class="td-po-number">PO#P00585</td>
                        <td>PharmaLife (Galle)</td>
                        <td>
                            <span class="td-medicine-name">Vitamin D3</span>
                            <span class="td-medicine-category">Supplements</span>
                        </td>
                        <td class="td-center">300 Units</td>
                        <td>May 19, 2025</td>
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
                        <td class="td-po-number">PO#P00584</td>
                        <td>MedStock Lanka (Negombo)</td>
                        <td>
                            <span class="td-medicine-name">Cetirizine 10mg</span>
                            <span class="td-medicine-category">Antihistamine</span>
                        </td>
                        <td class="td-center">100 Units</td>
                        <td>May 18, 2025</td>
                        <td><span class="status-pill rejected">REJECTED</span></td>
                        <td class="align-right">
                            <div class="row-actions">
                                <button class="icon-btn"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td-po-number">PO#P00583</td>
                        <td>CeyMed Distributors (Kurunegala)</td>
                        <td>
                            <span class="td-medicine-name">Metformin 500mg</span>
                            <span class="td-medicine-category">Diabetes</span>
                        </td>
                        <td class="td-center">250 Units</td>
                        <td>May 17, 2025</td>
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
                        <td class="td-po-number">PO#P00582</td>
                        <td>HealthCorp Pvt Ltd (Colombo)</td>
                        <td>
                            <span class="td-medicine-name">Salbutamol Inhaler</span>
                            <span class="td-medicine-category">Respiratory</span>
                        </td>
                        <td class="td-center">80 Units</td>
                        <td>May 16, 2025</td>
                        <td><span class="status-pill received">RECEIVED</span></td>
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
                <p class="pagination-info">Showing 1 to 6 of 48 orders</p>
                <div class="pagination-buttons">
                    <button class="page-text-btn">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-btn dots">...</button>
                    <button class="page-btn">8</button>
                    <button class="page-text-btn">Next</button>
                </div>
            </div>
        </div>

    </div>
</main>
