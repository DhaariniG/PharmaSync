<?php

// Prescriptions are kept in the session for now (no DB yet).
// status: pending, needs_alternative, prepared, approved, rejected
// alternative_decision: pending, approved, waiting
//
// What a prescription lets the customer buy:
//   Only the lines the pharmacist approved - 'prepared_items', each a
//   medicine and a number of packs. Quantities already ordered with the
//   prescription are taken off (cancelled orders give them back). So a
//   prescription for 2 strips of Metformin buys 2 strips of Metformin, once,
//   and nothing else. An approved prescription with no lines buys nothing.
//   In MySQL this is prescription_items.prescribed_quantity.
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
                // Already used in full by order #9003.
                'prepared_items' => [
                    ['medicine_id' => 7, 'quantity' => 2],
                ],
                'uploaded_at' => '2026-07-09 10:12:00',
            ],
            [
                'id'                   => 502,
                'user_id'              => 1,
                'patient_id'           => 2,
                'file_name'            => 'RX-88291.pdf',
                'status'               => 'needs_alternative',
                'notes'                => "The prescribed medicine 'Amoxicillin 500mg' is currently out of stock. We have suggested the same medicine and strength from another maker below.",
                'patient_name'         => 'Kamal Wijesinghe',
                'clinic'               => 'Central Hospital, Colombo',
                'prescribed_date'      => '2026-07-08',
                'requested_medicine_id'   => 3, // Amoxicillin 500mg
                'requested_quantity'      => 1,
                'unavailable_medicine_id' => 3, // kept for backward compatibility
                'alternative_id'          => 15, // Amoxicillin 500mg (CareGen) — same ingredient and strength
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
                'patient_name'          => 'Nadeesha Perera',
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
        // Only once, and only while the pharmacist is waiting for an answer.
        if (!$p || empty($p['alternative_id'])
            || $p['status'] !== 'needs_alternative'
            || ($p['alternative_decision'] ?? 'pending') !== 'pending') {
            return null;
        }

        // The alternative becomes the prescription's only line.
        $line = [
            'medicine_id' => (int) $p['alternative_id'],
            'quantity'    => (int) ($p['requested_quantity'] ?? 1),
        ];
        $this->update($id, [
            'alternative_decision' => 'approved',
            'status'               => 'approved',
            'prepared_items'       => [$line],
            'notes'                => 'You approved the suggested alternative. It has been added to your cart.',
        ]);

        return $line;
    }

    // Customer decides to wait for the original medicine instead.
    public function continueWaiting(int $id): void
    {
        $p = $this->find($id);
        if (!$p || $p['status'] !== 'needs_alternative') {
            return;
        }
        $this->update($id, [
            'alternative_decision' => 'waiting',
            'notes'                => 'You chose to wait for the original medicine to be restocked. We will notify you as soon as it is available.',
        ]);
    }

    // Customer confirms the prepared order. Returns the item lines for the cart.
    public function confirmPrepared(int $id): array
    {
        $p = $this->find($id);
        // Only a prescription still waiting for the customer's OK.
        if (!$p || $p['status'] !== 'prepared' || empty($p['prepared_items'])) {
            return [];
        }

        // Approved from now on: its lines can be ordered, up to the
        // quantities the pharmacist set (see coverage()).
        $this->update($id, [
            'status' => 'approved',
            'notes'  => 'You confirmed the prepared order. Items have been added to your cart.',
        ]);

        return $p['prepared_items'];
    }

    /* ==================================================================
     * What a prescription still allows (see the note at the top)
     * ================================================================== */

    /**
     * Per medicine: packs prescribed, packs already ordered, packs left.
     *   [7 => ['prescribed' => 2, 'ordered' => 2, 'left' => 0]]
     * Empty unless the prescription is approved.
     */
    public function coverage(array $p): array
    {
        if (($p['status'] ?? '') !== 'approved') {
            return [];
        }

        $ordered = (new Order())->quantitiesOrderedWith((int) $p['id']);
        $lines = [];
        foreach ($p['prepared_items'] ?? [] as $item) {
            $medicineId = (int) $item['medicine_id'];
            $prescribed = ($lines[$medicineId]['prescribed'] ?? 0) + (int) $item['quantity'];
            $lines[$medicineId] = ['prescribed' => $prescribed, 'ordered' => 0, 'left' => 0];
        }
        foreach ($lines as $medicineId => &$line) {
            $line['ordered'] = (int) ($ordered[$medicineId] ?? 0);
            $line['left']    = max(0, $line['prescribed'] - $line['ordered']);
        }
        unset($line);
        return $lines;
    }

    /** Packs of one medicine this prescription still allows. */
    public function leftFor(array $p, int $medicineId): int
    {
        return $this->coverage($p)[$medicineId]['left'] ?? 0;
    }

    /** True when everything on the prescription has been ordered. */
    public function isUsedUp(array $p): bool
    {
        $coverage = $this->coverage($p);
        return $coverage !== [] && array_sum(array_column($coverage, 'left')) === 0;
    }

    /**
     * Where one prescription stands, in words, for the "Prescriptions in
     * progress" list on My Orders. Null once there is nothing left to do
     * (used in full, or rejected).
     *   ['label' => 'Needs your approval', 'tone' => 'needs_alternative']
     * 'tone' is a ps-status-* colour class.
     */
    public function progress(array $p): ?array
    {
        $decision = $p['alternative_decision'] ?? 'pending';

        switch ($p['status']) {
            case 'pending':
                return ['label' => 'Waiting for the pharmacist', 'tone' => 'pending'];
            case 'prepared':
                return ['label' => 'Needs your confirmation', 'tone' => 'needs_alternative'];
            case 'needs_alternative':
                return $decision === 'waiting'
                    ? ['label' => 'Waiting for restock', 'tone' => 'pending']
                    : ['label' => 'Needs your approval', 'tone' => 'needs_alternative'];
            case 'approved':
                $left = array_sum(array_column($this->coverage($p), 'left'));
                return $left > 0
                    ? ['label' => 'Ready to buy', 'tone' => 'approved']
                    : null;
        }
        return null;
    }

    /** This customer's prescriptions that still need something, newest first. */
    public function inProgressFor(int $userId): array
    {
        $list = [];
        foreach ($this->forUser($userId) as $p) {
            $progress = $this->progress($p);
            if ($progress !== null) {
                $list[] = $p + ['progress' => $progress];
            }
        }
        return $list;
    }

    /** Packs of one medicine all of this customer's prescriptions still allow. */
    public function allowanceFor(int $userId, int $medicineId): int
    {
        $total = 0;
        foreach ($this->forUser($userId) as $p) {
            $total += $this->leftFor($p, $medicineId);
        }
        return $total;
    }

    /**
     * This customer's approved prescriptions that cover EVERY prescription
     * line in the cart on their own. $rxLines is [medicine_id => packs].
     * One order uses one prescription, so a cart that needs two can't be
     * checked out together.
     */
    public function covering(int $userId, array $rxLines): array
    {
        return array_values(array_filter($this->forUser($userId), function ($p) use ($rxLines) {
            foreach ($rxLines as $medicineId => $qty) {
                if ($this->leftFor($p, (int) $medicineId) < $qty) {
                    return false;
                }
            }
            return $rxLines !== [] && ($p['status'] ?? '') === 'approved';
        }));
    }
}
