<!-- ============ MAIN CONTENT ============ -->
<div class="main-content">

 

    <div class="content-area">
        <div class="content-inner">

            <div class="page-header-row">
                <div>
                    <h1 class="page-title">Add Supplier</h1>
                    <p class="page-subtitle">Add a new supplier to the system.</p>
                </div>
                        <a href="<?= url('/InventoryManager/suppliers') ?>" class="btn-back">
            <?= icon('arrow-right', 'material-symbols-outlined', 'font-size:20px; transform:rotate(180deg);') ?>
            Back to Suppliers
        </a>
            </div>

            <!-- Tabs: visual only for now - "Edit Supplier" isn't wired to anything yet,
                 since editing requires a real supplier id, which needs the database (Phase 4) -->
            <div class="form-tabs">
                <div class="form-tab active">Add Supplier</div>
                <div class="form-tab">Edit Supplier</div>
            </div>

            <div class="form-card">
                <form>
                    <div class="form-grid">

                        <!-- Left column -->
                        <div class="form-column">
                            <div class="form-field">
                                <label for="supplier_name">Supplier Name</label>
                                <input type="text" id="supplier_name" name="supplier_name" placeholder="e.g. Healthline Pharmaceuticals">
                            </div>

                            <div class="form-field">
                                <label for="phone">Phone Number</label>
                                <div class="phone-input-wrap">
                                    <span class="phone-prefix">+94</span>
                                    <input type="tel" id="phone" name="phone" placeholder="77 123 4567">
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="email">Email Address</label>
                                <div class="icon-input-wrap">
                                    <?= icon('mail', 'material-symbols-outlined') ?>
                                    <input type="email" id="email" name="email" placeholder="contact@supplier.com">
                                </div>
                            </div>

                            <div class="form-field">
                                <label>Status</label>
                                <div class="option-group">
                                    <label class="option-radio selected">
                                        <div class="option-left">
                                            <?= icon('circle-check', 'material-symbols-outlined') ?>
                                            <span>Active</span>
                                        </div>
                                        <div class="radio-dot-outer"><div class="radio-dot-inner"></div></div>
                                        <input type="radio" name="status" value="active" checked style="display:none;">
                                    </label>
                                    <label class="option-radio">
                                        <div class="option-left">
                                            <?= icon('x', 'material-symbols-outlined') ?>
                                            <span>Inactive</span>
                                        </div>
                                        <div class="radio-dot-outer"><div class="radio-dot-inner"></div></div>
                                        <input type="radio" name="status" value="inactive" style="display:none;">
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="form-column">
                            <div class="form-field">
                                <label for="address">Address</label>
                                <textarea id="address" name="address" placeholder="Enter full office address..."></textarea>
                            </div>

                            <div class="form-field">
                                <label for="notes">Notes</label>
                                <textarea id="notes" name="notes" class="tall" placeholder="Add any specific delivery instructions or preferred contact times..."></textarea>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="form-footer">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <button type="button" class="btn-save">
                        <?= icon('save', 'material-symbols-outlined', 'font-size:20px;') ?>
                        Save Supplier
                    </button>
                </div>
            </div>

            
        </div>
    </div>
</div>

<script>
    // Same shared "selected" toggle pattern used on Add Medicine and Add Batch
    const optionGroups = document.querySelectorAll('.option-group');
    optionGroups.forEach(function (group) {
        const radios = group.querySelectorAll('input[type="radio"]');
        radios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                group.querySelectorAll('.option-radio').forEach(function (label) {
                    label.classList.remove('selected');
                });
                radio.closest('.option-radio').classList.add('selected');
            });
        });
    });
</script>
