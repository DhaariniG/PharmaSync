<?php
/**
 * InventoryManagerMedicineController - real CRUD for medicines.
 *
 *   index()        list all medicines
 *   create()       show the empty Add form
 *   store()        POST: validate, insert, redirect to the list
 *   edit($id)      show the form pre-filled
 *   update($id)    POST: validate, save changes, redirect to the list
 *   delete($id)    POST: remove the row, redirect to the list
 *
 * No SQL here - everything goes through the Medicine model.
 */
class InventoryManagerMedicineController extends Controller
{
    protected string $viewBase = 'InventoryManager';

    /** Largest allowed unit price (the column is DECIMAL(10,2)). */
    private const MAX_PRICE = 999999.99;

    /** GET /InventoryManager/medicines */
    public function index(): void
    {
        $this->requireRole('Inventory_Manager');

        $model   = new Medicine();
        $perPage = 10;
        $total   = $model->countAll();
        $pages   = max(1, (int) ceil($total / $perPage));
        // Clamp so ?page=999 or ?page=-1 still lands on a real page.
        $page    = min($pages, max(1, (int) ($_GET['page'] ?? 1)));

        $this->render('medicine/index', [
            'medicines'   => $model->all($perPage, ($page - 1) * $perPage),
            'total'       => $total,
            'page'        => $page,
            'pages'       => $pages,
            'per_page'    => $perPage,
            'page_title'  => 'Medicines',
            'active_page' => 'medicines',
            'page_css'    => 'medicines_list.css',
        ]);
    }

    /** GET /InventoryManager/medicines/create */
    public function create(): void
    {
        $this->requireRole('Inventory_Manager');

        $this->showForm('create', [], []);
    }

    /** POST /InventoryManager/medicines */
    public function store(): void
    {
        $this->verifyCsrf();
        $this->requireRole('Inventory_Manager');

        $model = new Medicine();
        [$data, $errors] = $this->readForm($model);

        // Something was wrong: show the form again with the messages,
        // keeping what the user already typed.
        if ($errors) {
            $this->showForm('create', $_POST, $errors);
            return;
        }

        $model->create($data);
        $this->flash('success', 'Medicine "' . $data['name'] . '" was added.');
        $this->redirect('/InventoryManager/medicines');
    }

    /** GET /InventoryManager/medicines/{id}/edit */
    public function edit(string $id): void
    {
        $this->requireRole('Inventory_Manager');

        $medicine = (new Medicine())->find((int) $id);
        if (!$medicine) {
            $this->notFound();
        }

        $this->showForm('edit', $medicine, []);
    }

    /** POST /InventoryManager/medicines/{id}/update */
    public function update(string $id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Inventory_Manager');

        $id    = (int) $id;
        $model = new Medicine();

        $existing = $model->find($id);
        if (!$existing) {
            $this->notFound();
        }

        [$data, $errors] = $this->readForm($model);

        if ($errors) {
            // Keep the id so the form still posts to the right update URL.
            $this->showForm('edit', $_POST + ['medicine_id' => $id, 'name' => $existing['name']], $errors);
            return;
        }

        $model->update($id, $data);
        $this->flash('success', 'Medicine "' . $data['name'] . '" was updated.');
        $this->redirect('/InventoryManager/medicines');
    }

    /** POST /InventoryManager/medicines/{id}/delete */
    public function delete(string $id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Inventory_Manager');

        $model    = new Medicine();
        $medicine = $model->find((int) $id);
        if (!$medicine) {
            $this->notFound();
        }

        try {
            $model->delete((int) $id);
            $this->flash('success', 'Medicine "' . $medicine['name'] . '" was deleted.');
        } catch (PDOException $e) {
            // MySQL refuses to delete a medicine that batches, orders or
            // prescriptions still point at. Tell the user what to do instead.
            $this->flash('error', '"' . $medicine['name'] . '" is used by other records (batches or orders) '
                . 'and cannot be deleted. Set its status to Discontinued instead.');
        }

        $this->redirect('/InventoryManager/medicines');
    }

    /* ------------------------------------------------------------------
     * Private helpers
     * ------------------------------------------------------------------ */

    /** Render the create or edit screen (they share one form). */
    private function showForm(string $mode, array $medicine, array $errors): void
    {
        $this->render('medicine/' . $mode, [
            'medicine'    => $medicine,
            'errors'      => $errors,
            'categories'  => (new Medicine())->categories(),
            'page_title'  => $mode === 'create' ? 'Add Medicine' : 'Edit Medicine',
            'active_page' => 'medicines',
            'page_css'    => 'medicines_add.css',
        ]);
    }

    /**
     * Read and check the submitted form.
     * Returns [$data, $errors]: $data has real column names ready for the
     * model, $errors is empty when everything is valid.
     */
    private function readForm(Medicine $model): array
    {
        // Base rules from Controller::validate() (required / int / numeric / max)
        $errors = $this->validate($_POST, [
            'name'          => 'required|max:150',
            'category_id'   => 'required|int',
            'unit_price'    => 'required|numeric',
            'reorder_level' => 'required|int',
            'description'   => 'max:2000',
        ]);

        // Extra checks validate() does not cover
        if (isset($errors['category_id'])) {
            $errors['category_id'] = 'Please choose a category.';   // friendlier than "Category id is required."
        }
        if (!isset($errors['category_id']) &&!$model->categoryExists((int) $_POST['category_id'])) {
            $errors['category_id'] = 'Choose a category from the list.';
        }
        if (!isset($errors['unit_price'])) {
            $price = $this->text('unit_price');
            // Plain decimal only: no exponent (1e999), no hex, no sign tricks.
            if (!preg_match('/^-?\d+(\.\d+)?$/', $price)) {
                $errors['unit_price'] = 'Enter the price as a plain number, e.g. 12.50.';
            } elseif ((float) $price <= 0) {
                $errors['unit_price'] = 'Price must be greater than 0.';
            } elseif ((float) $price > self::MAX_PRICE) {
                $errors['unit_price'] = 'Price cannot be more than ' . number_format(self::MAX_PRICE, 2) . '.';
            } elseif (strlen(explode('.', $price)[1] ?? '') > 2) {
                $errors['unit_price'] = 'Price can have at most 2 decimal places.';
            }
        }
        if (!isset($errors['reorder_level']) && (int) $_POST['reorder_level'] < 0) {
            $errors['reorder_level'] = 'Reorder level cannot be negative.';
        }

        $status = $this->text('status');
        if (!in_array($status, ['Active', 'Inactive', 'Discontinued'], true)) {
            $errors['status'] = 'Choose a status.';
        }

        // Prescription is a yes/no radio: anything other than "1" counts as No.
        $requiresPrescription = $this->text('requires_prescription') === '1' ? 1 : 0;

        $description = $this->text('description');

        $data = [
            'name'                  => $this->text('name'),
            'category_id'           => (int) $this->text('category_id'),
            'unit_price'            => (float) $this->text('unit_price'),
            'reorder_level'         => (int) $this->text('reorder_level'),
            'description'           => $description === '' ? null : $description,
            'requires_prescription' => $requiresPrescription,
            'status'                => $status,
        ];

        return [$data, $errors];
    }
}
