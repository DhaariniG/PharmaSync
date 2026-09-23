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
                                <div class="table-header">
                                    <div>MEDICINE NAME</div>
                                    <div>BATCH / EXPIRY</div>
                                    <div>UNIT PRICE</div>
                                    <div class="text-center">QUANTITY</div>
                                    <div>SUBTOTAL</div>
                                    <div></div>
                                </div>

                                <div id="orderItemsContainer"></div>
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
        

<script>        const availableStock = <?= json_encode($stock ?? [], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
        let itemIndex = 0;

        // Escape text from the database before putting it into innerHTML.
        function esc(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        const searchInput = document.getElementById('medSearch');
        const searchResults = document.getElementById('searchResults');
        const orderContainer = document.getElementById('orderItemsContainer');

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            searchResults.innerHTML = '';

            if (query.length < 1) {
                searchResults.classList.remove('active');
                return;
            }

            const matches = availableStock.filter(item => 
                item.name.toLowerCase().includes(query) || 
                (item.batch_number && item.batch_number.toLowerCase().includes(query))
            );

            if (matches.length === 0) {
                searchResults.innerHTML = '<div style="padding: 12px; text-align: center; color: #94a3b8; font-size: 12px;">No matching stock found.</div>';
            } else {
                matches.forEach(item => {
                    const div = document.createElement('div');
                    div.className = 'search-result-item';
                    div.innerHTML = `
                        <div class="search-item-info">
                            <span class="search-item-name">${esc(item.name)}</span>
                            <span class="search-item-meta">Batch: ${esc(item.batch_number)} | Exp: ${esc(item.expiry_date)} | Stock: ${item.quantity}</span>
                        </div>
                        <span class="search-item-price">Rs. ${parseFloat(item.unit_price).toFixed(2)}</span>
                    `;
                    div.addEventListener('click', () => {
                        addMedicineToOrder(item);
                        searchInput.value = '';
                        searchResults.classList.remove('active');
                    });
                    searchResults.appendChild(div);
                });
            }
            searchResults.classList.add('active');
        });

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

            row.innerHTML = `
                <input type="hidden" name="items[${itemIndex}][medicine_id]" value="${item.medicine_id}">
                <input type="hidden" name="items[${itemIndex}][batch_id]" value="${item.batch_id}">
                <input type="hidden" name="items[${itemIndex}][unit_price]" value="${item.unit_price}">

                <div class="med-identity">
                    <span class="med-name">${esc(item.name)}</span>
                    <span class="med-meta">${esc(item.description || '')}</span>
                </div>
                <div>
                    <div class="batch-badge">${esc(item.batch_number)}</div>
                    <div class="exp-date text-orange">${esc(item.expiry_date)}</div>
                </div>
                <div class="price-cell">Rs. ${parseFloat(item.unit_price).toFixed(2)}</div>
                <div class="qty-control-cell">
                    <input type="number" name="items[${itemIndex}][quantity]" value="1" min="1" max="${item.quantity}" class="qty-number item-qty-input" oninput="recalculateTotals()">
                </div>
                <div class="subtotal-cell row-subtotal">Rs. ${parseFloat(item.unit_price).toFixed(2)}</div>
                <div class="action-cell">
                    <button type="button" class="btn-icon-delete" onclick="removeRow(this)"><i data-lucide="trash-2"></i></button>
                </div>
            `;

            orderContainer.appendChild(row);
            itemIndex++;
            lucide.createIcons();
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

            document.getElementById('summarySubtotal').textContent = 'Rs. ' + total.toFixed(2);
            document.getElementById('summaryTotal').textContent = 'Rs. ' + total.toFixed(2);
        }

        function selectPayment(elem) {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('selected'));
            elem.classList.add('selected');
            elem.querySelector('input[type="radio"]').checked = true;
        }
</script>
