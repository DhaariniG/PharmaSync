            <section class="card history-card">
                <div class="card-title-group">
                    <h3>All Orders (Physical & Online)</h3>
                </div>

                <div class="filter-bar">
                    <div class="search-field-wrapper">
                        <i data-lucide="search" class="search-inside-icon"></i>
                        <input type="text" id="historySearch" placeholder="Search by customer name or order ID..." onkeyup="filterHistoryTable()">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="data-table" id="historyTable">
                        <thead>
                            <tr>
                                <th>CHANNEL</th>
                                <th>ORDER ID</th>
                                <th>CUSTOMER NAME</th>
                                <th>DATE & TIME</th>
                                <th>TOTAL AMOUNT</th>
                                <th>PAYMENT METHOD</th>
                                <th>STATUS</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($history)): ?>
                                <?php foreach ($history as $row): ?>
                                    <tr>
                                        <td>
                                            <span class="batch-badge <?= $row['type'] === 'Physical' ? 'channel-physical' : 'channel-online' ?>">
                                                <i data-lucide="<?= $row['type'] === 'Physical' ? 'shopping-bag' : 'globe' ?>" class="badge-icon"></i>
                                                <?= e($row['type']) ?>
                                            </span>
                                        </td>
                                        <td class="order-id"><?= e($row['order_id']) ?></td>
                                        <td class="customer-name"><?= e($row['customer_name']) ?></td>
                                        <td class="date-cell"><?= e($row['date']) ?></td>
                                        <td class="amount-cell"><?= e($row['total_amount']) ?></td>
                                        <td class="payment-cell"><?= e($row['payment_method']) ?></td>
                                        <td>
                                            <?php 
                                                $statusClass = 'completed';
                                                if ($row['status'] === 'Cancelled') $statusClass = 'cancelled';
                                                if ($row['status'] === 'Refunded') $statusClass = 'refunded';
                                            ?>
                                            <span class="status-tag <?= $statusClass ?>">
                                                <?= e($row['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['type'] === 'Physical' && $row['raw_id'] > 0): ?>
                                                <?php if ($row['status'] === 'Cancelled' || $row['status'] === 'Refunded'): ?>
                                                    <span class="no-actions-text">No actions available</span>
                                                <?php else: ?>
                                                    <div class="action-group">
                                                        <!-- View Bill Receipt -->
                                                        <a href="<?= url('/pharmacist/sales/' . (int) $row['raw_id'] . '/bill') ?>" class="btn-action-view" title="View Printable Bill">
                                                            <i data-lucide="file-text" class="action-icon"></i> View Bill
                                                        </a>

                                                        <!-- Edit Order (Stock Delta Adjustments) -->
                                                        <a href="<?= url('/pharmacist/sales/' . (int) $row['raw_id'] . '/edit') ?>" class="btn-action-edit" title="Edit Quantities">
                                                            <i data-lucide="edit-3" class="action-icon"></i> Edit
                                                        </a>

                                                        <!-- Cancel / Refund Order Form -->
                                                        <form action="<?= url('/pharmacist/sales/' . (int) $row['raw_id'] . '/cancel') ?>" method="POST" onsubmit="return handleCancellation(this, '<?= (int) $row['raw_id'] ?>');" class="action-form">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="action_type" value="Cancelled">
                                                            <input type="hidden" name="cancellation_reason" value="">
                                                            <button type="submit" class="btn-action-void" title="Cancel or Refund Order">
                                                                <i data-lucide="slash" class="action-icon"></i> Cancel
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="sync-text">Online Sync</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="empty-table-cell">
                                        No sales or prescription history records found.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        

<script>
        // Live Table Search Filter
        function filterHistoryTable() {
            const input = document.getElementById('historySearch');
            const filter = input.value.toLowerCase();
            const tr = document.getElementById('historyTable').getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                const tdOrderId = tr[i].getElementsByTagName('td')[1];
                const tdCustomer = tr[i].getElementsByTagName('td')[2];
                if (tdOrderId || tdCustomer) {
                    const txtOrderId = tdOrderId.textContent || tdOrderId.innerText;
                    const txtCustomer = tdCustomer.textContent || tdCustomer.innerText;
                    tr[i].style.display = (txtOrderId.toLowerCase().includes(filter) || txtCustomer.toLowerCase().includes(filter)) ? "" : "none";
                }
            }
        }

        // Cancel / Refund Prompt Handler
        function handleCancellation(form, orderId) {
            const isCancel = confirm("Click OK to mark Order #POS-" + orderId + " as CANCELLED (Inventory Restored).\nClick Cancel to mark as REFUNDED.");
            const actionType = isCancel ? 'Cancelled' : 'Refunded';
            
            const reason = prompt("Enter reason for " + actionType.toLowerCase() + " order #POS-" + orderId + ":", "Customer request at counter");
            
            if (reason !== null && reason.trim() !== "") {
                form.querySelector('input[name="action_type"]').value = actionType;
                form.querySelector('input[name="cancellation_reason"]').value = reason.trim();
                return true;
            }
            return false;
        }
</script>
