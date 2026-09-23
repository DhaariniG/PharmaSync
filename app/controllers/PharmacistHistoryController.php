<?php
/**
 * PharmacistHistoryController - counter sales and online prescriptions in one list.
 */
class PharmacistHistoryController extends Controller
{
    protected string $viewBase = 'pharmacist';

    /** GET /pharmacist/history */
    public function index(): void
    {
        $this->requireRole('Pharmacist');

        $history = [];

        foreach ((new PhysicalSale())->getAllPhysicalSales() as $sale) {
            $history[] = [
                'type'           => 'Physical',
                'customer_name'  => $sale['customer_name'],
                'order_id'       => '#POS-' . $sale['order_id'],
                'raw_id'         => (int) $sale['order_id'],
                'total_amount'   => money($sale['total_amount']),
                'status'         => $sale['status'],
                'date'           => dt($sale['sale_date'], 'M d, Y h:i A'),
                'payment_method' => $sale['payment_method'] ?? '-',
            ];
        }

        foreach ((new PharmacistPrescription())->getAllForHistory() as $rx) {
            $history[] = [
                'type'           => 'Online',
                'customer_name'  => $rx['patient_name'],
                'order_id'       => '#RX-' . $rx['prescription_id'],
                'raw_id'         => 0,
                // Only shown when an online order exists for the prescription.
                'total_amount'   => $rx['total_amount'] === null ? '-' : money($rx['total_amount']),
                'status'         => $rx['status'],
                'date'           => dt($rx['order_date'], 'M d, Y h:i A'),
                'payment_method' => $rx['payment_method'] === null ? '-' : str_replace('_', ' ', $rx['payment_method']),
            ];
        }

        $this->render('history.index', [
            'history'         => $history,
            'page_title'      => 'Prescription History',
            'active_page'     => 'history',
            'page_css'        => 'prescriptionHistory.css',
            'container_class' => 'dashboard-container',
        ]);
    }
}
