<?php
/**
 * The Add / Edit medicine form. create.php and edit.php both include this,
 * so the two screens can never drift apart.
 *
 * Variables it expects:
 *   $medicine     - values to pre-fill (empty defaults when adding)
 *   $errors       - validation messages keyed by field name
 *   $categories   - rows from medicine_categories (for the dropdown)
 *   $formAction   - URL the form posts to
 *   $submitLabel  - text on the save button
 *   $cancelUrl    - where Cancel goes
 */
?>
        <div class="form-card">
            <form method="post" action="<?= e($formAction) ?>">
                <?= csrf_field() ?>
                <div class="form-grid">

                    <!-- Left column -->
                    <div class="form-column">
                        <div class="form-field">
                            <label for="name">Medicine Name</label>
                            <input type="text" id="name" name="name" placeholder="Enter medicine name"
                                   value="<?= old('name', $medicine) ?>">
                            <?php if (isset($errors['name'])): ?><p class="field-error"><?= e($errors['name']) ?></p><?php endif; ?>
                        </div>

                        <div class="form-field">
                            <label for="category_id">Category</label>
                            <select id="category_id" name="category_id">
                                <option value="">Select a category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= (int) $category['category_id'] ?>"
                                        <?= (string) ($medicine['category_id'] ?? '') === (string) $category['category_id'] ? 'selected' : '' ?>>
                                        <?= e($category['category_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['category_id'])): ?><p class="field-error"><?= e($errors['category_id']) ?></p><?php endif; ?>
                        </div>

                        <div class="form-field">
                            <label for="unit_price">Price (Rs.)</label>
                            <div class="input-group">
                                <span class="input-group-prefix">Rs.</span>
                                <input type="number" id="unit_price" name="unit_price" step="0.01" min="0"
                                       value="<?= old('unit_price', $medicine) ?>">
                            </div>
                            <?php if (isset($errors['unit_price'])): ?><p class="field-error"><?= e($errors['unit_price']) ?></p><?php endif; ?>
                        </div>

                        <div class="form-field">
                            <label for="reorder_level">Reorder Level</label>
                            <p class="field-hint">Minimum stock before alert triggers</p>
                            <input type="number" id="reorder_level" name="reorder_level" placeholder="0" min="0"
                                   value="<?= old('reorder_level', $medicine) ?>">
                            <?php if (isset($errors['reorder_level'])): ?><p class="field-error"><?= e($errors['reorder_level']) ?></p><?php endif; ?>
                        </div>
                    </div>

                    <!-- Right column -->
                    <div class="form-column">
                        <div class="form-field">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" placeholder="Enter medicine description..."><?= old('description', $medicine) ?></textarea>
                            <?php if (isset($errors['description'])): ?><p class="field-error"><?= e($errors['description']) ?></p><?php endif; ?>
                        </div>

                        <?php $rx = (string) ($medicine['requires_prescription'] ?? '0'); ?>
                        <div class="form-field">
                            <label>Requires Prescription</label>
                            <div class="option-group">
                                <label class="option-radio <?= $rx === '0' ? 'selected' : '' ?>">
                                    <div class="option-left">
                                        <?= icon('shopping-cart', 'material-symbols-outlined') ?>
                                        <span>No</span>
                                    </div>
                                    <input type="radio" name="requires_prescription" value="0" <?= $rx === '0' ? 'checked' : '' ?>>
                                </label>
                                <label class="option-radio <?= $rx === '1' ? 'selected' : '' ?>">
                                    <div class="option-left">
                                        <?= icon('scroll-text', 'material-symbols-outlined') ?>
                                        <span>Yes</span>
                                    </div>
                                    <input type="radio" name="requires_prescription" value="1" <?= $rx === '1' ? 'checked' : '' ?>>
                                </label>
                            </div>
                        </div>

                        <?php $status = $medicine['status'] ?? 'Active'; ?>
                        <div class="form-field">
                            <label>Status</label>
                            <div class="option-group">
                                <label class="option-radio <?= $status === 'Active' ? 'selected' : '' ?>">
                                    <div class="option-left">
                                        <?= icon('circle-check', 'material-symbols-outlined') ?>
                                        <span>Active</span>
                                    </div>
                                    <input type="radio" name="status" value="Active" <?= $status === 'Active' ? 'checked' : '' ?>>
                                </label>
                                <label class="option-radio <?= $status === 'Inactive' ? 'selected' : '' ?>">
                                    <div class="option-left">
                                        <?= icon('x', 'material-symbols-outlined') ?>
                                        <span>Inactive</span>
                                    </div>
                                    <input type="radio" name="status" value="Inactive" <?= $status === 'Inactive' ? 'checked' : '' ?>>
                                </label>
                                <?php if ($status === 'Discontinued'): ?>
                                <!-- Only shown when editing a medicine that is already Discontinued,
                                     so saving it does not silently change its status -->
                                <label class="option-radio selected">
                                    <div class="option-left">
                                        <?= icon('minus', 'material-symbols-outlined') ?>
                                        <span>Discontinued</span>
                                    </div>
                                    <input type="radio" name="status" value="Discontinued" checked>
                                </label>
                                <?php endif; ?>
                            </div>
                            <?php if (isset($errors['status'])): ?><p class="field-error"><?= e($errors['status']) ?></p><?php endif; ?>
                        </div>
                    </div>

                </div>

                <div class="form-footer">
                    <button type="button" class="btn-cancel" onclick="window.location.href='<?= e($cancelUrl) ?>'">Cancel</button>
                    <button type="submit" class="btn-save">
                        <?= icon('plus', 'material-symbols-outlined', 'font-size:20px;') ?>
                        <?= e($submitLabel) ?>
                    </button>
                </div>
            </form>
        </div>

<script>
    // When a radio option is clicked, mark its label as "selected"
    // and un-mark the other options in the same group.
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
