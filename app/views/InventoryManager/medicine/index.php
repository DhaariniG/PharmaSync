<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">

   

    <div class="content-area">

        <?php require __DIR__ . '/../partials/flash.php'; ?>

        <div class="page-header-row">
            <div>
                <h2 class="page-title">Medicines</h2>
                <p class="page-subtitle">Manage all medicine records in the system.</p>
            </div>
                <a href="<?= url('/InventoryManager/medicines/create') ?>" class="btn-add">
            <?= icon('plus', 'material-symbols-outlined') ?>
            Add Medicine
        </a>
        </div>

        <div class="action-bar">
            <div class="search-box">
                <?= icon('search', 'material-symbols-outlined') ?>
                <input type="text" placeholder="Search medicines...">
            </div>
            <div class="action-buttons">
                <button class="btn-secondary-outline">
                    <?= icon('list', 'material-symbols-outlined', 'font-size:20px;') ?>
                    Filters
                </button>
                <button class="btn-secondary-outline">
                    <?= icon('download', 'material-symbols-outlined', 'font-size:20px;') ?>
                    Export
                </button>
            </div>
        </div>

        <div class="table-card">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Medicine Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Reorder Level</th>
                        <th>Prescription</th>
                        <th>Status</th>
                        <th class="align-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($medicines)): ?>
                    <tr>
                        <td colspan="8" class="td-muted">No medicines yet. Click "Add Medicine" to create the first one.</td>
                    </tr>
                    <?php endif; ?>

                    <?php foreach ($medicines as $medicine): ?>
                    <tr>
                        <td class="td-medicine-name"><?= e($medicine['name']) ?></td>
                        <td class="td-muted"><?= e($medicine['category_name']) ?></td>
                        <td><?= money($medicine['unit_price']) ?></td>
                        <!-- Stock turns red when it is below the reorder level -->
                        <td<?= $medicine['stock'] < $medicine['reorder_level'] ? ' class="td-danger"' : '' ?>><?= e($medicine['stock']) ?></td>
                        <td><?= e($medicine['reorder_level']) ?></td>
                        <td>
                            <?php if ($medicine['requires_prescription']): ?>
                                <span class="badge badge-red">Yes</span>
                            <?php else: ?>
                                <span class="badge badge-green">No</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            // Active = green, Inactive = gray, Discontinued = red
                            $statusBadge = ['Active' => 'badge-green', 'Inactive' => 'badge-gray', 'Discontinued' => 'badge-red'];
                            ?>
                            <span class="badge <?= e($statusBadge[$medicine['status']] ?? 'badge-gray') ?>"><?= e($medicine['status']) ?></span>
                        </td>
                        <td class="align-right">
                            <div class="row-actions">
                                <a class="icon-btn" href="<?= url('/InventoryManager/medicines/' . (int) $medicine['medicine_id'] . '/edit') ?>" title="Edit">
                                    <?= icon('pencil', 'material-symbols-outlined', 'font-size:20px;') ?>
                                </a>
                                <!-- Delete is a POST form (not a link) so a crawler or a stray click cannot delete data -->
                                <form method="post" action="<?= url('/InventoryManager/medicines/' . (int) $medicine['medicine_id'] . '/delete') ?>"
                                      onsubmit="return confirm('Delete this medicine? This cannot be undone.');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="icon-btn icon-btn-danger" title="Delete">
                                        <?= icon('trash-2', 'material-symbols-outlined', 'font-size:20px;') ?>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination-row">
                <?php
                $from = $total ? ($page - 1) * $per_page + 1 : 0;
                $to   = $total ? $from + count($medicines) - 1 : 0;
                $pageUrl = fn(int $p) => url('/InventoryManager/medicines') . '?page=' . $p;
                ?>
                <span class="pagination-info">Showing <?= $from ?> to <?= $to ?> of <?= $total ?> medicines</span>
                <?php if ($pages > 1): ?>
                <div class="pagination-buttons">
                    <?php if ($page > 1): ?>
                        <a class="page-btn" href="<?= e($pageUrl($page - 1)) ?>" title="Previous"><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px; transform:rotate(180deg);') ?></a>
                    <?php else: ?>
                        <button class="page-btn" disabled><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px; transform:rotate(180deg);') ?></button>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $pages; $p++): ?>
                        <a class="page-btn<?= $p === $page ? ' active' : '' ?>" href="<?= e($pageUrl($p)) ?>"><?= $p ?></a>
                    <?php endfor; ?>

                    <?php if ($page < $pages): ?>
                        <a class="page-btn" href="<?= e($pageUrl($page + 1)) ?>" title="Next"><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px;') ?></a>
                    <?php else: ?>
                        <button class="page-btn" disabled><?= icon('chevron-right', 'material-symbols-outlined', 'font-size:18px;') ?></button>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="widgets-grid">
            <div class="widget-card">
                <div class="widget-top">
                    <span class="widget-label">Critical Low Stock</span>
                    <?= icon('triangle-alert', 'material-symbols-outlined', 'color:var(--color-error);') ?>
                </div>
                <p class="widget-value">08</p>
                <p class="widget-note">Medicines below reorder level</p>
            </div>
            <div class="widget-card widget-warning">
                <div class="widget-top">
                    <span class="widget-label">Expiring Soon</span>
                    <?= icon('calendar', 'material-symbols-outlined', 'color:#F59E0B;') ?>
                </div>
                <p class="widget-value">12</p>
                <p class="widget-note">Within next 30 days</p>
            </div>
            <div class="widget-card widget-info">
                <div class="widget-top">
                    <span class="widget-label">Total Inventory Value</span>
                    <?= icon('credit-card', 'material-symbols-outlined', 'color:var(--color-secondary);') ?>
                </div>
                <p class="widget-value">Rs. 1.2M</p>
                <p class="widget-note">Current valuation of medicines</p>
            </div>
        </div>

    </div>
</main>
