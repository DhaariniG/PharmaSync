<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

   
    <div class="content-area">
        <div class="content-inner">

            <div class="page-header-row">
                <div>
                    <h1 class="page-title">Add New Batch</h1>
                    <p class="page-subtitle">Add a new stock batch for a medicine.</p>
                </div>
                        <a href="<?= url('/InventoryManager/batches') ?>" class="btn-back">
            <?= icon('arrow-right', 'material-symbols-outlined', 'font-size:20px; transform:rotate(180deg);') ?>
            Back to Stock &amp; Batches
        </a>
            </div>

            <div class="form-card">
                <form>
                    <div class="form-grid">

                        <!-- Left column -->
                        <div class="form-column">
                            <div class="form-field">
                                <label for="medicine_id">Select Medicine</label>
                                <div class="select-wrap">
                                    <select id="medicine_id" name="medicine_id">
                                        <option value="">Select a medicine...</option>
                                        <option value="paracetamol">Paracetamol 500mg</option>
                                        <option value="amoxicillin">Amoxicillin 250mg</option>
                                        <option value="ibuprofen">Ibuprofen 400mg</option>
                                        <option value="metformin">Metformin 850mg</option>
                                    </select>
                                    <?= icon('chevron-down', 'material-symbols-outlined') ?>
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="batch_number">Batch Number</label>
                                <input type="text" id="batch_number" name="batch_number" placeholder="e.g. BATCH-2024-001">
                            </div>

                            <div class="form-field">
                                <label for="quantity">Quantity</label>
                                <div class="input-suffix-wrap">
                                    <input type="number" id="quantity" name="quantity" placeholder="0">
                                    <span class="suffix-label">Units</span>
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="mfg_date">Manufacturing Date</label>
                                <input type="date" id="mfg_date" name="mfg_date">
                            </div>
                        </div>

                        <!-- Right column -->
                        <div class="form-column">
                            <div class="form-field">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="date" id="expiry_date" name="expiry_date">
                            </div>

                            <div class="form-field">
                                <label for="supplier_id">Supplier</label>
                                <div class="select-wrap">
                                    <select id="supplier_id" name="supplier_id">
                                        <option value="">Select a supplier...</option>
                                        <option value="healthcorp">HealthCorp Pharmaceuticals</option>
                                        <option value="medisupplies">MediSupplies Ltd.</option>
                                        <option value="bioaura">BioAura Global</option>
                                        <option value="panacea">Panacea Logistics</option>
                                    </select>
                                    <?= icon('chevron-down', 'material-symbols-outlined') ?>
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="notes">Notes</label>
                                <textarea id="notes" name="notes" placeholder="Add any specific instructions or notes about this batch..."></textarea>
                            </div>

                            <div class="form-field">
                                <label>Status</label>
                                <div class="option-group">
                                    <label class="option-radio selected">
                                        <div class="radio-dot-outer"><div class="radio-dot-inner"></div></div>
                                        <span>Active</span>
                                        <input type="radio" name="status" value="active" checked style="display:none;">
                                    </label>
                                    <label class="option-radio">
                                        <div class="radio-dot-outer"><div class="radio-dot-inner"></div></div>
                                        <span>Inactive</span>
                                        <input type="radio" name="status" value="inactive" style="display:none;">
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

                <div class="form-footer">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <button type="button" class="btn-save">
                        <?= icon('plus', 'material-symbols-outlined', 'font-size:20px;') ?>
                        Save Batch
                    </button>
                </div>
            </div>

            

        </div>
    </div>
</main>

<script>
    // Same "selected" class-toggle pattern as Add Medicine screen -
    // one shared idea across the whole module, not something reinvented per screen.
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
