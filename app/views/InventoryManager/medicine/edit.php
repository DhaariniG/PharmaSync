<!-- ============ MAIN CONTENT ============ -->
<main class="main-content">
    <div class="content-area">

        <a href="<?= url('/InventoryManager/medicines') ?>" class="btn-back">
            <?= icon('arrow-right', 'material-symbols-outlined', 'font-size:18px; transform:rotate(180deg);') ?>
            Back to Medicines
        </a>

        <div>
            <h1 class="page-title">Edit Medicine</h1>
            <p class="page-subtitle">Change the details of <?= e($medicine['name']) ?>.</p>
        </div>

        <?php
        $formAction  = url('/InventoryManager/medicines/' . (int) $medicine['medicine_id'] . '/update');
        $submitLabel = 'Save Changes';
        $cancelUrl   = url('/InventoryManager/medicines');
        require __DIR__ . '/_form.php';
        ?>

    </div>
</main>
