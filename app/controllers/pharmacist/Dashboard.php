<?php

class Dashboard extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }

        $userRole = $_SESSION['role'] ?? $_SESSION['user_role'] ?? '';
        if (strtolower($userRole) !== 'pharmacist') {
            header('Location: ' . BASE_URL . '/index.php?url=auth/login');
            exit;
        }
    }

    /** Home Overview Screen */
    public function index(): void
    {
        $this->view('Pharmacist/dashboard');
    }

    /** GET: Render POS Physical Sale view */
    public function sales(): void
    {
        require_once APP_PATH . '/models/PhysicalSale.php';
        $saleModel = new PhysicalSale();

        $stock = $saleModel->getAvailableStock();

        $this->view('Pharmacist/physicalSale', [
            'stock' => $stock
        ]);
    }

    /** POST: Create Sale */
    public function addSale(): void
    {
        if ($this->isPost()) {
            require_once APP_PATH . '/models/PhysicalSale.php';
            $saleModel = new PhysicalSale();

            $pharmacistId  = (int)($_SESSION['user_id'] ?? $_SESSION['id'] ?? $_SESSION['user']['id'] ?? 1);
            $customerName  = trim($_POST['customer_name'] ?? '');
            $items         = $_POST['items'] ?? [];
            $paymentMethod = $_POST['payment_method'] ?? 'Cash';
            $notes         = trim($_POST['notes'] ?? '');

            $uploadedFilePath = null;
            if (isset($_FILES['prescription_file']) && $_FILES['prescription_file']['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath   = $_FILES['prescription_file']['tmp_name'];
                $fileName      = $_FILES['prescription_file']['name'];
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'pdf'])) {
                    $uploadDir = PUBLIC_PATH . '/uploads/prescriptions/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

                    if (move_uploaded_file($fileTmpPath, $uploadDir . $newFileName)) {
                        $uploadedFilePath = 'uploads/prescriptions/' . $newFileName;
                    }
                }
            }

            if (!empty($items) && is_array($items) && $pharmacistId > 0) {
                $orderId = $saleModel->createSale($pharmacistId, $customerName, $items, $paymentMethod, $notes, $uploadedFilePath);
                if ($orderId) {
                    header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/saleCompleted/' . $orderId);
                    exit;
                }
            }
        }

        header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/sales');
        exit;
    }

    /** GET: Render Receipt/Invoice Completion View */
    public function saleCompleted(int $orderId = 0): void
    {
        if ($orderId === 0) {
            header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/sales');
            exit;
        }

        require_once APP_PATH . '/models/PhysicalSale.php';
        $saleModel = new PhysicalSale();

        $orderDetails = $saleModel->getOrderDetails($orderId);
        if (!$orderDetails) {
            header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/sales');
            exit;
        }

        $this->view('Pharmacist/saleCompleted', [
            'order' => $orderDetails
        ]);
    }

    /** GET: View historical sale receipt */
    public function viewBill(int $orderId = 0): void
    {
        if ($orderId === 0) {
            header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/history');
            exit;
        }

        require_once APP_PATH . '/models/PhysicalSale.php';
        $saleModel = new PhysicalSale();

        $orderDetails = $saleModel->getOrderDetails($orderId);
        if (!$orderDetails) {
            header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/history');
            exit;
        }

        $this->view('Pharmacist/viewBill', [
            'order' => $orderDetails
        ]);
    }

    /** GET: Consolidated History (Physical Sales + Online Prescriptions) */
    public function history(): void
    {
        require_once APP_PATH . '/models/PhysicalSale.php';
        $saleModel = new PhysicalSale();

        $dbPhysicalSales = $saleModel->getAllPhysicalSales();
        $dbOnlineSales   = $saleModel->getAllOnlinePrescriptions();

        $unifiedHistory = [];

        foreach ($dbPhysicalSales as $sale) {
            $unifiedHistory[] = [
                'type'           => 'Physical',
                'customer_name'  => $sale['customer_name'],
                'order_id'       => '#POS-' . $sale['order_id'],
                'raw_id'         => $sale['order_id'],
                'total_amount'   => 'Rs. ' . number_format($sale['total_amount'], 2),
                'status'         => $sale['status'] ?? 'Completed',
                'date'           => date('M d, Y h:i A', strtotime($sale['sale_date'])),
                'payment_method' => $sale['payment_method'] ?? 'Cash'
            ];
        }

        foreach ($dbOnlineSales as $online) {
            $unifiedHistory[] = [
                'type'           => 'Online',
                'customer_name'  => $online['patient_name'],
                'order_id'       => '#RX-' . $online['prescription_id'],
                'raw_id'         => 0,
                'total_amount'   => 'Rs. ' . number_format($online['total_amount'], 2),
                'status'         => $online['status'] ?? 'Approved',
                'date'           => date('M d, Y h:i A', strtotime($online['order_date'])),
                'payment_method' => $online['payment_method'] ?? 'Card Online'
            ];
        }

        $this->view('Pharmacist/prescriptionHistory', [
            'history' => $unifiedHistory
        ]);
    }

    /** POST: Process Order Cancellation or Refund */
    public function cancelSale(): void
    {
        if ($this->isPost()) {
            require_once APP_PATH . '/models/PhysicalSale.php';
            $saleModel = new PhysicalSale();

            $orderId    = (int)($_POST['order_id'] ?? 0);
            $actionType = $_POST['action_type'] ?? 'Cancelled';
            $reason     = trim($_POST['cancellation_reason'] ?? 'Pharmacist Voided');
            $userId     = (int)($_SESSION['user_id'] ?? $_SESSION['id'] ?? 1);

            if ($orderId > 0) {
                $saleModel->cancelSale($orderId, $userId, $actionType, $reason);
            }
        }
        header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/history');
        exit;
    }

    /** GET: Render Order Edit Screen for Stock Delta Adjustments */
    public function editSale(int $orderId = 0): void
    {
        if ($orderId === 0) {
            header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/history');
            exit;
        }

        require_once APP_PATH . '/models/PhysicalSale.php';
        $saleModel = new PhysicalSale();

        $order = $saleModel->getOrderDetails($orderId);
        if (!$order || ($order['status'] ?? '') === 'Cancelled' || ($order['status'] ?? '') === 'Refunded') {
            header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/history');
            exit;
        }

        $this->view('Pharmacist/editSale', ['order' => $order]);
    }

    /** POST: Update Order Quantities via Delta Adjustment */
    /** POST: Update Order Quantities via Delta Adjustment */
    public function updateSale(): void
    {
        if ($this->isPost()) {
            require_once APP_PATH . '/models/PhysicalSale.php';
            $saleModel = new PhysicalSale();

            $orderId = (int)($_POST['order_id'] ?? 0);
            $items   = $_POST['items'] ?? [];
            $userId  = (int)($_SESSION['user_id'] ?? $_SESSION['id'] ?? 1);

            if ($orderId > 0 && !empty($items)) {
                foreach ($items as $itemId => $newQty) {
                    $saleModel->updateOrderQuantity($orderId, (int)$itemId, (int)$newQty, $userId);
                }
            }
            
            header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/viewBill/' . $orderId);
            exit;
        }

        header('Location: ' . BASE_URL . '/index.php?url=pharmacist/dashboard/history');
        exit;
    }
    /** GET: Database-connected Queue View */
    public function prescriptions(): void
    {
        require_once APP_PATH . '/models/PhysicalSale.php';
        $saleModel = new PhysicalSale();

        $queueData = $saleModel->getPendingPrescriptionQueue();

        $this->view('Pharmacist/prescriptionQueue', [
            'queue' => $queueData
        ]);
    }

    public function medicines(): void { $this->view('Pharmacist/medicineAvailability'); }
    public function notifications(): void { $this->view('Pharmacist/notification'); }
    public function settings(): void { $this->view('Pharmacist/settings'); }
}