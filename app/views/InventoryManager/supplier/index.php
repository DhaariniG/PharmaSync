<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">



    <div class="content-area">

        <div>
            <h1 class="page-title">Suppliers</h1>
            <p class="page-subtitle">Manage your medicine suppliers and contacts.</p>
        </div>

        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon total">
                    <?= icon('users', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="summary-label">Total Suppliers</p>
                    <p class="summary-value">12</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon active">
                    <?= icon('circle-check', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="summary-label">Active Suppliers</p>
                    <p class="summary-value">10</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-icon inactive">
                    <?= icon('x', 'material-symbols-outlined') ?>
                </div>
                <div>
                    <p class="summary-label">Inactive Suppliers</p>
                    <p class="summary-value">02</p>
                </div>
            </div>
        </div>

        <div class="action-bar">
            <div class="search-box">
                <?= icon('search', 'material-symbols-outlined') ?>
                <input type="text" placeholder="Search suppliers...">
            </div>
                    <a href="<?= url('/InventoryManager/suppliers/create') ?>" class="btn-add">
            <?= icon('plus', 'material-symbols-outlined') ?>
            Add Supplier
        </a>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Medicines Supplied</th>
                        <th>Status</th>
                        <th class="align-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="supplier-name-cell">
                                <span class="supplier-name">HealthCorp Pvt Ltd</span>
                                <span class="supplier-location">Colombo</span>
                            </div>
                        </td>
                        <td>+94 11 234 5678</td>
                        <td>contact@healthcorp.lk</td>
                        <td>
                            <div class="tag-list">
                                <span class="tag">Paracetamol</span>
                                <span class="tag">Amoxicillin</span>
                                <span class="tag">+2 more</span>
                            </div>
                        </td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="icon-btn view"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn edit"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="supplier-name-cell">
                                <span class="supplier-name">MediSupplies</span>
                                <span class="supplier-location">Kandy</span>
                            </div>
                        </td>
                        <td>+94 81 234 1122</td>
                        <td>sales@medisupplies.lk</td>
                        <td>
                            <div class="tag-list">
                                <span class="tag">Ibuprofen</span>
                                <span class="tag">Metformin</span>
                            </div>
                        </td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="icon-btn view"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn edit"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="supplier-name-cell">
                                <span class="supplier-name">PharmaLife</span>
                                <span class="supplier-location">Galle</span>
                            </div>
                        </td>
                        <td>+94 91 345 8899</td>
                        <td>info@pharmalife.com</td>
                        <td>
                            <div class="tag-list">
                                <span class="tag">Omeprazole</span>
                                <span class="tag">Amlodipine</span>
                            </div>
                        </td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="icon-btn view"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn edit"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="supplier-name-cell">
                                <span class="supplier-name">MedStock Lanka</span>
                                <span class="supplier-location">Negombo</span>
                            </div>
                        </td>
                        <td>+94 31 778 3344</td>
                        <td>admin@medstock.lk</td>
                        <td>
                            <div class="tag-list">
                                <span class="tag">Ciprofloxacin</span>
                            </div>
                        </td>
                        <td><span class="badge badge-gray">Inactive</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="icon-btn view"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn edit"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="supplier-name-cell">
                                <span class="supplier-name">CeyMed Distributors</span>
                                <span class="supplier-location">Kurunegala</span>
                            </div>
                        </td>
                        <td>+94 37 444 1122</td>
                        <td>ceymed@distributors.lk</td>
                        <td>
                            <div class="tag-list">
                                <span class="tag">Atorvastatin</span>
                                <span class="tag">Cetirizine</span>
                            </div>
                        </td>
                        <td><span class="badge badge-green">Active</span></td>
                        <td>
                            <div class="row-actions">
                                <button class="icon-btn view"><?= icon('eye', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn edit"><?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                                <button class="icon-btn delete"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="pagination-row">
                <p class="pagination-info">Showing <strong>1 to 5</strong> of <strong>12</strong> suppliers</p>
                <div class="pagination-buttons">
                    <button class="page-text-btn">Previous</button>
                    <button class="page-btn active">1</button>
                    <button class="page-btn">2</button>
                    <button class="page-btn">3</button>
                    <button class="page-text-btn">Next</button>
                </div>
            </div>
        </div>

    </div>
</main>
