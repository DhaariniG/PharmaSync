<?php

// Prescriptions are kept in the session for now (no DB yet).
// status: pending, needs_alternative, prepared, approved, rejected
// alternative_decision: pending, approved, waiting
class Prescription extends Model
{
    private static function seed(): array
    {
        return [
            [
                'id'          => 501,
                'user_id'     => 1,
                'patient_id'  => 2, // Kamal Wijesinghe (Father) — matches order #9003
                'file_name'   => 'prescription_dr_silva.jpg',
                'status'      => 'approved',
                'notes'       => 'Approved by Dr. Silva — ready for order.',
                'uploaded_at' => '2026-07-09 10:12:00',
            ],
            [
                'id'                   => 502,
                'user_id'              => 1,
                'patient_id'           => 2,
                'file_name'            => 'RX-88291.pdf',
                'status'               => 'needs_alternative',
                'notes'                => "The prescribed medicine 'Amoxicillin' is currently out of stock. We have suggested a high-quality alternative below which has the same therapeutic effect.",
                'patient_name'         => 'Kamal Wijesinghe',
                'clinic'               => 'Central Hospital, Colombo',
                'prescribed_date'      => '2026-07-08',
                'requested_medicine_id'   => 3, // Amoxicillin 500mg
                'requested_quantity'      => 1,
                'unavailable_medicine_id' => 3, // kept for backward compatibility
                'alternative_id'          => 13, // Clarithromycin 500mg — pharmacist-chosen substitute
                'alternative_decision'    => 'pending',
                'pharmacist_name'      => 'Dr. Aruni Perera',
                'uploaded_at'          => '2026-07-13 09:12:00',
            ],
            [
                'id'                    => 503,
                'user_id'               => 1,
                'patient_id'            => 2,
                'file_name'             => 'RX-90144.jpg',
                'status'                => 'prepared',
                'notes'                 => 'Your prescription has been reviewed and prepared. Please confirm to add these items to your cart.',
                'patient_name'          => 'Kamal Wijesinghe',
                'clinic'                => 'Central Hospital, Colombo',
                'prescribed_date'       => '2026-07-14',
                'requested_medicine_id' => 7, // Metformin 500mg
                'requested_quantity'    => 2,
                'pharmacist_name'       => 'Dr. Aruni Perera',
                'prepared_items'        => [
                    ['medicine_id' => 7, 'quantity' => 2],
                ],
                'uploaded_at'           => '2026-07-14 11:40:00',
            ],
            [
                'id'                    => 504,
                'user_id'               => 1,
                'patient_id'            => 1,
                'file_name'             => 'RX-91055.pdf',
                'status'                => 'pending',
                'notes'                 => 'Awaiting pharmacist review.',
                'patient_name'          => 'Kamal Wijesinghe',
                'requested_medicine_id' => 11, // Azithromycin 250mg
                'requested_quantity'    => 1,
                'uploaded_at'           => '2026-07-16 16:05:00',
            ],
        ];
    }

    private function &store(): array
    {
        if (!isset($_SESSION['prescriptions']) || !is_array($_SESSION['prescriptions'])) {
            $_SESSION['prescriptions'] = self::seed();
        }
        return $_SESSION['prescriptions'];
    }

    public function forUser(int $userId): array
    {
        $items = array_filter($this->store(), fn($p) => $p['user_id'] === $userId);
        usort($items, fn($a, $b) => strtotime($b['uploaded_at']) <=> strtotime($a['uploaded_at']));
        return array_values($items);
    }

    public function find(int $id): ?array
    {
        foreach ($this->store() as $p) {
            if ($p['id'] === $id) {
                return $p;
            }
        }
        return null;
    }

    public function create(array $data): array
    {
        $store = &$this->store();
        $id = 500 + count($store) + 1;

        $prescription = [
            'id'                    => $id,
            'user_id'               => $data['user_id'],
            'file_name'             => $data['file_name'],
            'status'                => 'pending',
            'notes'                 => 'Awaiting pharmacist review.',
            'pharmacy_notes'        => $data['pharmacy_notes'] ?? '',
            'urgent'                => $data['urgent'] ?? false,
            'patient_id'            => $data['patient_id'] ?? null,
            'requested_medicine_id' => $data['requested_medicine_id'] ?? null,
            'requested_quantity'    => $data['requested_quantity'] ?? 1,
            'uploaded_at'           => date('Y-m-d H:i:s'),
        ];

        $store[] = $prescription;
        return $prescription;
    }

    // Merge some fields into a stored prescription.
    private function update(int $id, array $changes): ?array
    {
        $store = &$this->store();
        foreach ($store as &$p) {
            if ($p['id'] === $id) {
                $p = array_merge($p, $changes);
                return $p;
            }
        }
        return null;
    }

    // Customer accepts the suggested alternative. Returns the [medicine_id,
    // quantity] to add to the cart.
    public function approveAlternative(int $id): ?array
    {
        $p = $this->find($id);
        if (!$p || empty($p['alternative_id'])) {
            return null;
        }

        // Keep it 'approved' so it can still be used for later orders.
        $this->update($id, [
            'alternative_decision' => 'approved',
            'status'               => 'approved',
            'notes'                => 'You approved the suggested alternative. It has been added to your cart.',
        ]);

        return [
            'medicine_id' => (int) $p['alternative_id'],
            'quantity'    => (int) ($p['requested_quantity'] ?? 1),
        ];
    }

    // Customer decides to wait for the original medicine instead.
    public function continueWaiting(int $id): void
    {
        $this->update($id, [
            'alternative_decision' => 'waiting',
            'notes'                => 'You chose to wait for the original medicine to be restocked. We will notify you as soon as it is available.',
        ]);
    }

    // Customer confirms the prepared order. Returns the item lines for the cart.
    public function confirmPrepared(int $id): array
    {
        $p = $this->find($id);
        if (!$p || empty($p['prepared_items'])) {
            return [];
        }

        // Keep it 'approved' so it can still be used for later orders.
        $this->update($id, [
            'status' => 'approved',
            'notes'  => 'You confirmed the prepared order. Items have been added to your cart.',
        ]);

        return $p['prepared_items'];
    }
}
