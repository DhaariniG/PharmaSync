<?php

class CustomerPrescriptionController extends Controller
{
    protected string $viewBase = 'customer';

    public function uploadForm(): void
    {
        $this->requireRole('Customer');

        $user = $this->currentUser();
        $history = (new Prescription())->forUser($user['id'] ?? 0);

        // If the customer came from a specific Rx medicine, keep that medicine
        // in context so the form can show and submit it.
        $medicineId = (int) $this->input('medicine_id', 0);
        $requestedMedicine = $medicineId > 0 ? (new CustomerMedicine())->find($medicineId) : null;
        $requestedQuantity = max(1, (int) $this->input('quantity', 1));

        $this->render('prescription.upload', [
            'error'              => $this->flash('error'),
            'members'            => (new FamilyMember())->forUser($user['id'] ?? 0),
            'history'            => $history,
            'requestedMedicine'  => $requestedMedicine,
            'requestedQuantity'  => $requestedQuantity,
        ]);
    }

    public function upload(): void
    {
        $this->requireRole('Customer');
        $this->verifyCsrf();
        $user = $this->currentUser();

        if (empty($_FILES['prescription_file']) || $_FILES['prescription_file']['error'] !== UPLOAD_ERR_OK) {
            $this->flash('error', 'Please choose a file to upload.');
            $this->redirect('/customer/prescription/upload');
            return;
        }

        // Check who it is for before touching the file, so a bad choice does
        // not leave an orphaned upload on disk. The patient must be someone
        // on this account.
        $patient = (new FamilyMember())->find($user['id'], (int) $this->input('patient_id', 0));
        if (!$patient) {
            $this->flash('error', 'Please choose who this prescription is for.');
            $this->redirect('/customer/prescription/upload');
            return;
        }

        $file = $_FILES['prescription_file'];

        if ($file['size'] > PRESCRIPTION_MAX_SIZE) {
            $this->flash('error', 'File is too large. Max size is 5MB.');
            $this->redirect('/customer/prescription/upload');
            return;
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, PRESCRIPTION_ALLOWED_TYPES, true)) {
            $this->flash('error', 'Invalid file type. Please upload a JPG, PNG or PDF.');
            $this->redirect('/customer/prescription/upload');
            return;
        }

        if (!is_dir(PRESCRIPTION_UPLOAD_DIR)) {
            mkdir(PRESCRIPTION_UPLOAD_DIR, 0755, true);
        }

        // Use the extension from the checked MIME type, not the user's filename.
        $ext = PRESCRIPTION_MIME_EXT[$mime] ?? 'bin';
        $safeName = 'rx_' . ($user['id'] ?? 0) . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = PRESCRIPTION_UPLOAD_DIR . $safeName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $this->flash('error', 'Upload failed. Please try again.');
            $this->redirect('/customer/prescription/upload');
            return;
        }

        $requestedMedicineId = (int) $this->input('requested_medicine_id', 0);

        $prescription = (new Prescription())->create([
            'user_id'               => $user['id'],
            'file_name'             => $safeName,
            'patient_id'            => $patient['id'],
            'pharmacy_notes'        => trim((string) $this->input('pharmacy_notes', '')),
            'urgent'                => (bool) $this->input('urgent_refill', false),
            'requested_medicine_id' => $requestedMedicineId > 0 ? $requestedMedicineId : null,
            'requested_quantity'    => max(1, (int) $this->input('requested_quantity', 1)),
        ]);

        $this->flash('success', 'Prescription uploaded. A pharmacist will review it shortly.');
        $this->redirect('/customer/prescription/status/' . $prescription['id']);
    }

    public function status($id): void
    {
        $this->requireRole('Customer');

        $prescription = (new Prescription())->find((int) $id);
        if (!$prescription || $prescription['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/prescription/upload');
            return;
        }

        $medicineModel = new CustomerMedicine();

        $requestedMedicine = !empty($prescription['requested_medicine_id'])
            ? $medicineModel->find((int) $prescription['requested_medicine_id'])
            : null;

        // The pharmacist's chosen substitute (view-only; the customer can only
        // approve it or keep waiting).
        $alternativeMedicine = !empty($prescription['alternative_id'])
            ? $medicineModel->find((int) $prescription['alternative_id'])
            : null;

        $preparedItems = [];
        foreach ($prescription['prepared_items'] ?? [] as $line) {
            $m = $medicineModel->find((int) $line['medicine_id']);
            if ($m) {
                $preparedItems[] = ['medicine' => $m, 'quantity' => $line['quantity']];
            }
        }

        // What this prescription still allows: prescribed / ordered / left.
        $coverage = [];
        foreach ((new Prescription())->coverage($prescription) as $medicineId => $line) {
            $m = $medicineModel->find((int) $medicineId);
            if ($m) {
                $coverage[] = $line + ['medicine' => $m];
            }
        }

        $this->render('prescription.status', [
            'coverage'            => $coverage,
            'usedInOrders'        => (new Order())->forPrescription((int) $prescription['id']),
            'prescription'        => $prescription,
            'patientLabel'        => (new FamilyMember())->label(
                                        $this->currentUser()['id'],
                                        $prescription['patient_id'] ?? null),
            'requestedMedicine'   => $requestedMedicine,
            'alternativeMedicine' => $alternativeMedicine,
            'preparedItems'       => $preparedItems,
        ]);
    }

    // Approve the suggested alternative and add it to the cart.
    public function approveAlternative($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $prescriptionId = (int) $id;
        $prescription = (new Prescription())->find($prescriptionId);
        if (!$prescription || $prescription['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/prescription/upload');
            return;
        }

        $model = new Prescription();
        if ($model->approveAlternative($prescriptionId)) {
            $this->putInCart($model->find($prescriptionId));
            $this->flash('success', 'Alternative approved and added to your cart.');
        }

        $this->redirect('/customer/prescription/status/' . $prescriptionId);
    }

    // Keep waiting for the original medicine instead of the alternative.
    public function continueWaiting($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $prescriptionId = (int) $id;
        $prescription = (new Prescription())->find($prescriptionId);
        if (!$prescription || $prescription['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/prescription/upload');
            return;
        }

        (new Prescription())->continueWaiting($prescriptionId);
        $this->flash('success', "We'll notify you as soon as the original medicine is back in stock.");
        $this->redirect('/customer/prescription/status/' . $prescriptionId);
    }

    // Confirm the prepared order; its items go into the normal cart.
    public function confirmPrepared($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $prescriptionId = (int) $id;
        $prescription = (new Prescription())->find($prescriptionId);
        if (!$prescription || $prescription['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/prescription/upload');
            return;
        }

        // Works once: after this the prescription is 'approved', and asking
        // again does nothing (it used to add the same items every time).
        $model = new Prescription();
        if ($model->confirmPrepared($prescriptionId)) {
            $this->putInCart($model->find($prescriptionId));
            $this->flash('success', 'Prepared order confirmed and added to your cart.');
        }

        $this->redirect('/customer/cart');
    }

    // Serve a prescription file, but only to the customer who owns it.
    public function file($id): void
    {
        $this->requireRole('Customer');

        $prescription = (new Prescription())->find((int) $id);
        if (!$prescription || $prescription['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            http_response_code(404);
            echo 'Not found';
            return;
        }

        $path = PRESCRIPTION_UPLOAD_DIR . basename($prescription['file_name']);
        if (!is_file($path)) {
            http_response_code(404);
            echo 'File missing';
            return;
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        header('Content-Disposition: inline; filename="' . basename($prescription['file_name']) . '"');
        header('X-Content-Type-Options: nosniff');
        readfile($path);
        exit;
    }

    /**
     * Put whatever an approved prescription still allows into the cart -
     * e.g. after items were removed from the cart, or an order using it was
     * cancelled. Sets the quantity rather than adding, so pressing it twice
     * changes nothing.
     */
    public function addToCart($id): void
    {
        $this->verifyCsrf();
        $this->requireRole('Customer');

        $prescriptionId = (int) $id;
        $prescription = (new Prescription())->find($prescriptionId);
        if (!$prescription || $prescription['user_id'] !== ($this->currentUser()['id'] ?? null)) {
            $this->redirect('/customer/prescription/upload');
            return;
        }

        if ($this->putInCart($prescription) > 0) {
            $this->flash('success', 'The items left on this prescription are in your cart.');
            $this->redirect('/customer/cart');
            return;
        }

        $this->flash('error', 'Nothing left to add - this prescription has been used in full, or the items are out of stock.');
        $this->redirect('/customer/prescription/status/' . $prescriptionId);
    }

    /**
     * Cart quantity for each line = what this prescription still allows,
     * never more than stock. Returns how many lines went in.
     */
    private function putInCart(?array $prescription): int
    {
        if (!$prescription) {
            return 0;
        }

        $userId = $this->currentUser()['id'] ?? null;
        $model = new Prescription();
        $medicines = new CustomerMedicine();
        $cart = new Cart();
        $added = 0;

        foreach ($model->coverage($prescription) as $medicineId => $line) {
            $medicine = $medicines->find((int) $medicineId);
            if (!$medicine || $line['left'] < 1) {
                continue;
            }
            $result = $cart->setWithinLimit($medicine, $line['left'], $userId);
            if ($result['quantity'] > 0) {
                $added++;
            }
        }
        return $added;
    }
}
