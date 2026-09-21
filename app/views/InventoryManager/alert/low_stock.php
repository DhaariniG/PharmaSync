<!-- ============ MAIN CONTENT ============ -->
<div class="main-content">

   

    <div class="content-area">
        <div class="content-inner">

            <div class="page-header-row">
                <div>
                    <h1 class="page-title">Low Stock Alerts</h1>
                    <p class="page-subtitle">Medicines that have fallen below their reorder level.</p>
                </div>
                        <a href="<?= url('/InventoryManager/purchase-orders/create') ?>" class="btn-bulk-po">
            <?= icon('shopping-cart', 'material-symbols-outlined') ?>
            Raise Bulk Purchase Order
        </a>
            </div>

            <div class="summary-cards">
                <div class="summary-card">
                    <div class="summary-icon-box critical"><?= icon('triangle-alert', 'material-symbols-outlined') ?></div>
                    <div>
                        <p class="summary-label">Total Low Stock</p>
                        <h3 class="summary-value critical">23</h3>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon-box warning"><?= icon('circle-alert', 'material-symbols-outlined') ?></div>
                    <div>
                        <p class="summary-label">Critical Items</p>
                        <h3 class="summary-value warning">8</h3>
                    </div>
                </div>
                <div class="summary-card">
                    <div class="summary-icon-box success"><?= icon('shield-check', 'material-symbols-outlined') ?></div>
                    <div>
                        <p class="summary-label">POs Raised</p>
                        <h3 class="summary-value success">5</h3>
                    </div>
                </div>
            </div>

            <div class="action-bar">
                <div class="search-box">
                    <?= icon('search', 'material-symbols-outlined') ?>
                    <input type="text" placeholder="Search medicine name...">
                </div>
                <div class="filter-group">
                    <label>Filter by Category:</label>
                    <select>
                        <option>All Categories</option>
                        <option>Antibiotics</option>
                        <option>Analgesics</option>
                        <option>Respiratory</option>
                        <option>Diabetes</option>
                    </select>
                </div>
            </div>

            <div class="table-card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Category</th>
                            <th>Current Stock</th>
                            <th class="align-center">Reorder Level</th>
                            <th class="align-center">Shortage</th>
                            <th>Severity</th>
                            <th class="align-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="td-name">Paracetamol 500mg</td>
                            <td class="td-muted">Analgesics</td>
                            <td>
                                <div class="stock-bar-wrap">
                                    <p class="stock-bar-label critical">40 / 100</p>
                                    <div class="stock-bar-track"><div class="stock-bar-fill" style="width:40%;"></div></div>
                                </div>
                            </td>
                            <td class="td-center">100 Units</td>
                            <td class="td-center"><span class="shortage-value critical">-60</span></td>
                            <td><span class="severity-badge critical">Critical</span></td>
                            <td class="align-right"><button class="btn-raise-po">Raise PO</button></td>
                        </tr>
                        <tr>
                            <td class="td-name">Amoxicillin 250mg</td>
                            <td class="td-muted">Antibiotics</td>
                            <td>
                                <div class="stock-bar-wrap">
                                    <p class="stock-bar-label critical">30 / 80</p>
                                    <div class="stock-bar-track"><div class="stock-bar-fill" style="width:37.5%;"></div></div>
                                </div>
                            </td>
                            <td class="td-center">80 Units</td>
                            <td class="td-center"><span class="shortage-value critical">-50</span></td>
                            <td><span class="severity-badge critical">Critical</span></td>
                            <td class="align-right"><button class="btn-raise-po">Raise PO</button></td>
                        </tr>
                        <tr>
                            <td class="td-name">Cetirizine 10mg</td>
                            <td class="td-muted">Allergy</td>
                            <td>
                                <div class="stock-bar-wrap">
                                    <p class="stock-bar-label warning">45 / 60</p>
                                    <div class="stock-bar-track warning"><div class="stock-bar-fill warning" style="width:75%;"></div></div>
                                </div>
                            </td>
                            <td class="td-center">60 Units</td>
                            <td class="td-center"><span class="shortage-value warning">-15</span></td>
                            <td><span class="severity-badge low">Low</span></td>
                            <td class="align-right"><button class="btn-raise-po">Raise PO</button></td>
                        </tr>
                        <tr>
                            <td class="td-name">Salbutamol Inhaler</td>
                            <td class="td-muted">Respiratory</td>
                            <td>
                                <div class="stock-bar-wrap">
                                    <p class="stock-bar-label critical">15 / 45</p>
                                    <div class="stock-bar-track"><div class="stock-bar-fill" style="width:33.3%;"></div></div>
                                </div>
                            </td>
                            <td class="td-center">45 Units</td>
                            <td class="td-center"><span class="shortage-value critical">-30</span></td>
                            <td><span class="severity-badge critical">Critical</span></td>
                            <td class="align-right"><button class="btn-raise-po">Raise PO</button></td>
                        </tr>
                        <tr>
                            <td class="td-name">Metformin 500mg</td>
                            <td class="td-muted">Diabetes</td>
                            <td>
                                <div class="stock-bar-wrap">
                                    <p class="stock-bar-label warning">55 / 70</p>
                                    <div class="stock-bar-track warning"><div class="stock-bar-fill warning" style="width:78.5%;"></div></div>
                                </div>
                            </td>
                            <td class="td-center">70 Units</td>
                            <td class="td-center"><span class="shortage-value warning">-15</span></td>
                            <td><span class="severity-badge low">Low</span></td>
                            <td class="align-right"><button class="btn-raise-po">Raise PO</button></td>
                        </tr>
                        <tr>
                            <td class="td-name">Insulin Glargine</td>
                            <td class="td-muted">Diabetes</td>
                            <td>
                                <div class="stock-bar-wrap">
                                    <p class="stock-bar-label critical">10 / 50</p>
                                    <div class="stock-bar-track"><div class="stock-bar-fill" style="width:20%;"></div></div>
                                </div>
                            </td>
                            <td class="td-center">50 Units</td>
                            <td class="td-center"><span class="shortage-value critical">-40</span></td>
                            <td><span class="severity-badge critical">Critical</span></td>
                            <td class="align-right"><button class="btn-raise-po">Raise PO</button></td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination-row">
                    <p class="pagination-info">Showing 1 to 6 of 23 low stock items</p>
                    <div class="pagination-buttons">
                        <button class="page-arrow-btn" disabled><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px; transform:rotate(180deg);') ?></button>
                        <button class="page-btn active">1</button>
                        <button class="page-btn">2</button>
                        <button class="page-btn">3</button>
                        <button class="page-arrow-btn"><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px;') ?></button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
