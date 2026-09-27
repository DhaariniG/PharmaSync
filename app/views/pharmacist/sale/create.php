<style>
    /* Print Styles for Quotation */
    @media print {
        body * { visibility: hidden; }
        .workspace-grid, .workspace-grid * { visibility: visible; }
        .workspace-grid { position: absolute; left: 0; top: 0; width: 100%; display: block; }
        .top-navbar, .sidebar, .btn, .search-input-wrapper, .action-cell, .file-dropzone, .payment-toggle-grid { display: none !important; }
        .card { border: none !important; box-shadow: none !important; }
    }
</style>

<form action="<?= url("/pharmacist/sales") ?>" method="POST" id="physicalSaleForm">
    <?= csrf_field() ?>
    <div class="workspace-grid">
        <div class="left-stack">
            <section class="card workflow-card">
                <div class="workflow-header">
                    <div class="workflow-title-group">
                        <i data-lucide="plus-circle" class="icon-teal"></i>
                        <h3>Add Medicines</h3>
                    </div>
                    <span class="terminal-id">Terminal ID: PS-002</span>
                </div>

                <div class="search-input-wrapper">
                    <i data-lucide="search" class="search-inside-icon"></i>
                    <input type="text" placeholder="Search medicine to add..." class="med-search-field" id="medSearch" autocomplete="off" onkeydown="if(event.key === 'Enter') event.preventDefault();">
                    <div class="kbd-shortcuts">
                        <kbd>ALT</kbd>
                        <kbd>S</kbd>
                    </div>
                    <div id="searchResults" class="search-dropdown-results"></div>
                </div>

                <div class="sales-table">
                    <!-- Updated Table Header with Prescribed Qty & Frequency Info -->
                    <div class="table-header" style="display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1fr 1.5fr auto; gap: 8px; font-size: 11px; font-weight: 700; color: #64748b; padding: 8px 12px; border-bottom: 1px solid #e2e8f0;">
                        <div>MEDICINE NAME</div>
                        <div>BATCH / EXPIRY</div>
                        <div>UNIT PRICE</div>
                        <div class="text-center">DISPENSED QTY</div>
                        <div class="text-center">PRESCRIBED QTY</div>
                        <div>FREQUENCY</div>
                        <div></div>
                    </div>

                    <div id="orderItemsContainer" style="display: flex; flex-direction: column; gap: 8px; margin-top: 8px;"></div>
                </div>
            </section>
        </div>

        <div class="right-stack">
            <section class="card summary-panel-card">
                <h3>Order Summary</h3>

                <div class="pricing-ledger">
                    <div class="ledger-row">
                        <span>Subtotal</span>
                        <span class="ledger-unit" id="summarySubtotal">Rs. 0.00</span>
                    </div>
                </div>

                <div class="grand-total-row">
                    <span class="total-label">Total</span>
                    <span class="total-amount" id="summaryTotal">Rs. 0.00</span>
                </div>

                <div class="form-section">
                    <h4>CUSTOMER INFORMATION</h4>
                    <div class="form-group">
                        <label>Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" placeholder="Walk-in Customer" required>
                    </div>
                    <div class="form-group">
                        <label>Notes / Remarks</label>
                        <input type="text" name="notes" class="form-control" placeholder="Optional notes">
                    </div>
                </div>

                <div class="form-section">
                    <h4>PAYMENT METHOD</h4>
                    <div class="payment-toggle-grid">
                        <label class="payment-option selected" onclick="selectPayment(this)">
                            <input type="radio" name="payment_method" value="Cash" checked class="hidden-radio">
                            <i data-lucide="banknote"></i>
                            <span>Cash</span>
                        </label>
                        <label class="payment-option" onclick="selectPayment(this)">
                            <input type="radio" name="payment_method" value="Card" class="hidden-radio">
                            <i data-lucide="credit-card"></i>
                            <span>Card (Sandbox)</span>
                        </label>
                    </div>
                </div>

                <div class="summary-actions-group">
                    <button type="submit" class="btn btn-complete-sale">Complete Sale <i data-lucide="arrow-right"></i></button>
                    <button type="button" class="btn btn-print-quotation" onclick="window.print()">Print Quotation</button>
                </div>
            </section>
        </div>
    </div>
</form>

<script>
    const availableStock = <?= json_encode($stock ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
    let itemIndex = 0;

    // Escape text from the database before putting it into innerHTML
    function esc(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    const searchInput = document.getElementById('medSearch');
    const searchResults = document.getElementById('searchResults');
    const orderContainer = document.getElementById('orderItemsContainer');

    if (searchInput && searchResults) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            searchResults.innerHTML = '';

            if (query.length < 1) {
                searchResults.style.display = 'none';
                searchResults.classList.remove('active');
                return;
            }

            const matches = availableStock.filter(item => 
                (item.name && item.name.toLowerCase().includes(query)) || 
                (item.batch_number && item.batch_number.toLowerCase().includes(query))
            );

            if (matches.length === 0) {
                searchResults.innerHTML = '<div style="padding: 12px; text-align: center; color: #94a3b8; font-size: 12px;">No matching stock found.</div>';
            } else {
                matches.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.style = 'padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;';
                    div.innerHTML = `
                        <div class="search-item-info">
                            <span class="search-item-name" style="font-weight: 600; color: #0f172a; display: block;">${esc(item.name)}</span>
                            <span class="search-item-meta" style="font-size: 11px; color: #64748b;">Batch: ${esc(item.batch_number)} | Exp: ${esc(item.expiry_date)} | Stock: ${item.quantity}</span>
                        </div>
                        <span class="search-item-price" style="font-weight: 700; color: #0d9488;">Rs. ${parseFloat(item.unit_price).toFixed(2)}</span>
                    `;
                    div.addEventListener('click', () => {
                        addMedicineToOrder(item);
                        searchInput.value = '';
                        searchResults.style.display = 'none';
                        searchResults.classList.remove('active');
                    });
                    searchResults.appendChild(div);
                });
            }
            searchResults.style.display = 'block';
            searchResults.classList.add('active');
        });

        // Hide search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
                searchResults.classList.remove('active');
            }
        });
    }

    function addMedicineToOrder(item) {
        const existingRow = document.querySelector(`.table-row[data-batch-id="${item.batch_id}"]`);
        if (existingRow) {
            const qtyInput = existingRow.querySelector('.item-qty-input');
            const currentQty = parseInt(qtyInput.value) || 1;
            if (currentQty < item.quantity) {
                qtyInput.value = currentQty + 1;
                recalculateTotals();
            } else {
                alert('Maximum available stock limit reached for this batch.');
            }
            return;
        }

        const row = document.createElement('div');
        row.className = 'table-row';
        row.dataset.unitPrice = item.unit_price;
        row.dataset.batchId = item.batch_id;
        row.style = 'display: grid; grid-template-columns: 2fr 1.5fr 1fr 1fr 1fr 1.5fr auto; gap: 8px; align-items: center; background: #f8fafc; padding: 10px 12px; border-radius: 6px; border: 1px solid #e2e8f0; margin-bottom: 6px;';

        row.innerHTML = `
            <input type="hidden" name="items[${itemIndex}][medicine_id]" value="${item.medicine_id}">
            <input type="hidden" name="items[${itemIndex}][batch_id]" value="${item.batch_id}">
            <input type="hidden" name="items[${itemIndex}][unit_price]" value="${item.unit_price}">

            <div class="med-identity">
                <span class="med-name" style="font-weight: 700; color: #1e293b;">${esc(item.name)}</span>
                <span class="med-meta" style="font-size: 11px; color: #64748b; display: block;">${esc(item.description || '')}</span>
            </div>
            <div>
                <div class="batch-badge" style="background: #fef08a; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: 700; color: #854d0e; display: inline-block;">${esc(item.batch_number)}</div>
                <div class="exp-date text-orange" style="font-size: 11px; color: #c2410c; margin-top: 2px;">${esc(item.expiry_date)}</div>
            </div>
            <div class="price-cell" style="font-size: 12px; font-weight: 600; color: #0d9488;">Rs. ${parseFloat(item.unit_price).toFixed(2)}</div>
            <div class="qty-control-cell text-center">
                <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" max="${item.quantity}" class="qty-number item-qty-input form-control" style="width: 60px; text-align: center; padding: 6px; margin: 0 auto;" oninput="recalculateTotals()">
            </div>
            <div class="text-center">
                <input type="number" name="items[${itemIndex}][prescribed_quantity]" placeholder="Qty" min="1" class="form-control" style="width: 60px; text-align: center; padding: 6px; margin: 0 auto;">
            </div>
            <div>
                <input type="text" name="items[${itemIndex}][frequency]" placeholder="e.g. 1 tab twice daily" class="form-control" style="width: 100%; padding: 6px 8px; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 12px;">
            </div>
            <div class="subtotal-cell row-subtotal" style="display:none;">Rs. ${parseFloat(item.unit_price).toFixed(2)}</div>
            <div class="action-cell">
                <button type="button" class="btn-icon-delete" style="background: none; border: none; color: #ef4444; cursor: pointer;" onclick="removeRow(this)"><i data-lucide="trash-2" style="width: 16px; height: 16px;"></i></button>
            </div>
        `;

        orderContainer.appendChild(row);
        itemIndex++;
        if (typeof lucide !== 'undefined') lucide.createIcons();
        recalculateTotals();
    }

    function removeRow(btn) {
        btn.closest('.table-row').remove();
        recalculateTotals();
    }

    function recalculateTotals() {
        let total = 0;
        document.querySelectorAll('.table-row').forEach(row => {
            const price = parseFloat(row.dataset.unitPrice) || 0;
            const qtyInput = row.querySelector('.item-qty-input');
            const qty = parseInt(qtyInput ? qtyInput.value : 1) || 0;
            const rowSub = price * qty;

            const subElem = row.querySelector('.row-subtotal');
            if (subElem) subElem.textContent = 'Rs. ' + rowSub.toFixed(2);

            total += rowSub;
        });

        const subtotalElem = document.getElementById('summarySubtotal');
        const totalElem = document.getElementById('summaryTotal');
        if (subtotalElem) subtotalElem.textContent = 'Rs. ' + total.toFixed(2);
        if (totalElem) totalElem.textContent = 'Rs. ' + total.toFixed(2);
    }

    function selectPayment(elem) {
        document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
        elem.classList.add('selected');
        elem.querySelector('input[type="radio"]').checked = true;
    }
</script>