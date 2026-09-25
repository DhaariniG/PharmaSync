<!-- ============ MAIN CONTENT ============ -->
<div class="main-content">

   

    <div class="content-area">

        <div class="page-header-row">
            <div>
                <h1 class="page-title">Raise Purchase Order</h1>
                <p class="page-subtitle">Create a new purchase order for medicine restocking.</p>
            </div>
                <a href="<?= url('/InventoryManager/purchase-orders') ?>" class="btn-back-link">
            <?= icon('arrow-right', 'material-symbols-outlined', 'transform:rotate(180deg);') ?>
            Back to Purchase Orders
        </a>
        </div>

        <form class="po-grid" method="post" action="<?= url('/InventoryManager/purchase-orders') ?>">
            <?= csrf_field() ?>

            <!-- Left: form -->
            <div class="form-column">

                <section class="form-section">
                    <div class="section-header">
                        <?= icon('truck', 'material-symbols-outlined') ?>
                        <h4>Supplier Details</h4>
                    </div>

                    <div class="form-field">
                        <label for="supplier_id">Select Supplier</label>
                        <select id="supplier_id" name="supplier_id">
                            <option>MedSupply Lanka (Pvt) Ltd</option>
                            <option>Ceylon Pharma Distributors</option>
                            <option>Colombo Wholesale Pharmaceuticals</option>
                        </select>
                    </div>

                    <div class="supplier-info-box">
                        <div class="supplier-icon">
                            <?= icon('store', 'material-symbols-outlined') ?>
                        </div>
                        <div class="supplier-info-grid">
                            <div>
                                <p class="info-label">Email</p>
                                <p class="info-value">orders@medsupplylanka.lk</p>
                            </div>
                            <div>
                                <p class="info-label">Phone</p>
                                <p class="info-value">+94 11 234 5001</p>
                            </div>
                            <div class="full-width">
                                <p class="info-label">Address</p>
                                <p class="info-value">221 Negombo Road, Colombo 14</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-header with-action">
                        <div style="display:flex; align-items:center; gap: var(--space-sm);">
                            <?= icon('pill', 'material-symbols-outlined') ?>
                            <h4>Medicine Items</h4>
                        </div>
                        <button type="button" class="btn-add-pill">
                            <?= icon('plus', 'material-symbols-outlined', 'font-size:18px;') ?>
                            Add Medicine
                        </button>
                    </div>

                    <div class="item-rows-header">
                        <span>Medicine Name</span>
                        <span>Quantity</span>
                        <span>Unit</span>
                        <span></span>
                    </div>

                    <div class="item-row">
                        <select>
                            <option>Paracetamol 500mg</option>
                            <option>Amoxicillin 500mg</option>
                            <option>Vitamin C 1000mg</option>
                        </select>
                        <input type="number" value="200">
                        <select class="unit-select">
                            <option>Units</option>
                            <option>Strips</option>
                            <option>Boxes</option>
                        </select>
                        <div class="item-row-delete">
                            <button type="button"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                        </div>
                    </div>

                    <div class="item-row">
                        <select>
                            <option>Amoxicillin 500mg</option>
                            <option>Paracetamol 500mg</option>
                            <option>Vitamin C 1000mg</option>
                        </select>
                        <input type="number" value="150">
                        <select class="unit-select">
                            <option>Units</option>
                            <option>Strips</option>
                            <option>Boxes</option>
                        </select>
                        <div class="item-row-delete">
                            <button type="button"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                        </div>
                    </div>

                    <div class="item-row">
                        <select>
                            <option>Vitamin C 1000mg</option>
                            <option>Paracetamol 500mg</option>
                            <option>Amoxicillin 500mg</option>
                        </select>
                        <input type="number" value="300">
                        <select class="unit-select">
                            <option>Units</option>
                            <option>Strips</option>
                            <option>Boxes</option>
                        </select>
                        <div class="item-row-delete">
                            <button type="button"><?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?></button>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="section-header">
                        <?= icon('scroll-text', 'material-symbols-outlined') ?>
                        <h4>Additional Notes</h4>
                    </div>
                    <textarea rows="4" placeholder="Enter special handling instructions or procurement notes..."></textarea>
                </section>

            </div>

            <!-- Right: order summary -->
            <div class="summary-column">
                <div class="summary-card">
                    <div class="summary-header">
                        <h4>Order Summary</h4>
                        <span class="status-tag-draft">Draft</span>
                    </div>

                    <div class="summary-rows">
                        <div class="summary-row">
                            <span class="label">PO Number</span>
                            <span class="value">PO-2026-0113</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Supplier</span>
                            <span class="value" style="font-weight:500;">MedSupply Lanka (Pvt) Ltd</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Order Date</span>
                            <span class="value" style="font-weight:500;">Sep 25, 2026</span>
                        </div>
                        <div class="summary-row">
                            <span class="label">Status</span>
                            <span class="status-pending-chip"><span class="dot"></span> Pending</span>
                        </div>
                    </div>

                    <hr class="summary-divider">

                    <div>
                        <p class="summary-items-title">Medicines Ordered</p>
                        <div class="summary-item-list">
                            <div class="summary-item-row">
                                <span>Paracetamol 500mg</span>
                                <span class="qty">200 Units</span>
                            </div>
                            <div class="summary-item-row">
                                <span>Amoxicillin 500mg</span>
                                <span class="qty">150 Units</span>
                            </div>
                            <div class="summary-item-row">
                                <span>Vitamin C 1000mg</span>
                                <span class="qty">300 Units</span>
                            </div>
                        </div>
                    </div>

                    <div class="total-box">
                        <div class="sub-row">
                            <span>Total Unique Items</span>
                            <span>3 Medicines</span>
                        </div>
                        <div class="total-row">
                            <span class="total-label">Total Quantity</span>
                            <span class="total-value">650 Units</span>
                        </div>
                    </div>

                    <div class="summary-actions">
                        <button type="submit" class="btn-submit">Submit Purchase Order</button>
                        <button type="button" class="btn-draft">Save as Draft</button>
                        <button type="button" class="btn-cancel-link">Cancel Order</button>
                    </div>
                </div>

                <div class="info-note" style="margin-top: var(--space-lg);">
                    <?= icon('info', 'material-symbols-outlined') ?>
                    <p>Submitted purchase orders go to the Admin for approval. Once approved, the PO is sent to the supplier's registered email.</p>
                </div>
            </div>

        </form>
    </div>
</div>
