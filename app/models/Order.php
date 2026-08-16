<?php

// Orders live in the session, seeded with a few sample past orders.
class Order extends Model
{
    private static function seed(): array
    {
        return [
            [
                'id'             => 9001,
                'user_id'        => 1,
                'status'         => 'delivered',
                'payment_method' => 'card',
                'prescription_id'=> null, // OTC-only order, no prescription involved
                'patient_id'     => 1,
                'patient_label'  => 'Nadeesha Perera',
                'items'          => [
                    ['medicine_id' => 5, 'name' => 'Vitamin C 1000mg', 'quantity' => 2, 'unit_price' => 690.00],
                    ['medicine_id' => 1, 'name' => 'Paracetamol 500mg', 'quantity' => 1, 'unit_price' => 250.00],
                ],
                'subtotal'    => 1630.00,
                'delivery_fee'=> 150.00,
                'tax'         => 32.00,
                'total'       => 1812.00,
                'placed_at'   => '2026-07-05 14:22:00',
                'address'     => '12 Galle Road, Colombo 03',
                'delivery_person' => 'Sunil Fernando',
                'tracking'    => [
                    ['label' => 'Order Received',   'time' => '2026-07-05 02:22 PM', 'done' => true],
                    ['label' => 'Pharmacist Review', 'time' => '2026-07-05 03:10 PM', 'done' => true],
                    ['label' => 'In Transit',        'time' => '2026-07-06 09:00 AM', 'done' => true],
                    ['label' => 'Delivered',         'time' => '2026-07-06 04:45 PM', 'done' => true],
                ],
            ],
            [
                'id'             => 9002,
                'user_id'        => 1,
                'status'         => 'processing',
                'payment_method' => 'cod',
                'prescription_id'=> null, // OTC-only order, no prescription involved
                'patient_id'     => 1,
                'patient_label'  => 'Nadeesha Perera',
                'items'          => [
                    ['medicine_id' => 12, 'name' => 'Omega-3 Fish Oil', 'quantity' => 1, 'unit_price' => 1250.00],
                ],
                'subtotal'    => 1250.00,
                'delivery_fee'=> 150.00,
                'tax'         => 25.00,
                'total'       => 1425.00,
                'placed_at'   => '2026-07-11 09:05:00',
                'address'     => 'No 10, Kandy Road, Kiribathgoda',
                'delivery_person' => 'Ravi Kumara',
                'tracking'    => [
                    ['label' => 'Order Received',   'time' => '2026-07-11 09:05 AM', 'done' => true],
                    ['label' => 'Pharmacist Review', 'time' => '2026-07-11 10:40 AM', 'done' => true],
                    ['label' => 'In Transit',        'time' => '2026-07-12 08:15 AM', 'done' => true, 'note' => 'Picked up from our Colombo pharmacy by the delivery rider.'],
                    ['label' => 'Out for Delivery',  'time' => 'Expected today', 'done' => false],
                ],
            ],
            [
                'id'             => 9003,
                'user_id'        => 1,
                'status'         => 'delivered',
                'payment_method' => 'card',
                'prescription_id'=> 501, // approved prescription used to place this Rx order
                'patient_id'     => 2,
                'patient_label'  => 'Father — Kamal Wijesinghe',
                'items'          => [
                    ['medicine_id' => 7, 'name' => 'Metformin 500mg', 'quantity' => 2, 'unit_price' => 410.00],
                ],
                'subtotal'    => 820.00,
                'delivery_fee'=> 150.00,
                'tax'         => 16.40,
                'total'       => 986.40,
                'placed_at'   => '2026-06-20 11:15:00',
                'address'     => '12 Galle Road, Colombo 03',
                'delivery_person' => 'Nimal Jayasuriya',
                'tracking'    => [
                    ['label' => 'Order Received',   'time' => '2026-06-20 11:15 AM', 'done' => true],
                    ['label' => 'Pharmacist Review', 'time' => '2026-06-20 12:00 PM', 'done' => true],
                    ['label' => 'In Transit',        'time' => '2026-06-21 09:00 AM', 'done' => true],
                    ['label' => 'Delivered',         'time' => '2026-06-21 03:40 PM', 'done' => true],
                ],
            ],
        ];
    }

    private function &store(): array
    {
        if (!isset($_SESSION['orders']) || !is_array($_SESSION['orders'])) {
            $_SESSION['orders'] = self::seed();
        }
        return $_SESSION['orders'];
    }

    public function forUser(int $userId): array
    {
        $orders = array_filter($this->store(), fn($o) => $o['user_id'] === $userId);
        usort($orders, fn($a, $b) => strtotime($b['placed_at']) <=> strtotime($a['placed_at']));
        return array_values($orders);
    }

    public function recentForUser(int $userId, int $limit = 3): array
    {
        return array_slice($this->forUser($userId), 0, $limit);
    }

    public function find(int $id): ?array
    {
        foreach ($this->store() as $o) {
            if ($o['id'] === $id) {
                return $o;
            }
        }
        return null;
    }

    public function create(array $data): array
    {
        $store = &$this->store();
        $id = 9000 + count($store) + 1;

        $method = ($data['delivery_method'] ?? 'delivery') === 'pickup' ? 'pickup' : 'delivery';

        // Pickup orders don't ship, so they get a different set of steps.
        if ($method === 'pickup') {
            $tracking = [
                ['label' => 'Order Received',    'time' => date('M j, g:i A'), 'done' => true],
                ['label' => 'Pharmacist Review', 'time' => 'Pending', 'done' => false],
                ['label' => 'Ready for Pickup',  'time' => 'Pending', 'done' => false],
                ['label' => 'Collected',         'time' => 'Pending', 'done' => false],
            ];
        } else {
            $tracking = [
                ['label' => 'Order Received',    'time' => date('M j, g:i A'), 'done' => true],
                ['label' => 'Pharmacist Review', 'time' => 'Pending', 'done' => false],
                ['label' => 'In Transit',        'time' => 'Pending', 'done' => false],
                ['label' => 'Out for Delivery',  'time' => 'Pending', 'done' => false],
            ];
        }

        $order = [
            'id'             => $id,
            'user_id'        => $data['user_id'],
            'status'         => 'pending',
            'payment_method' => $data['payment_method'],
            'prescription_id'=> $data['prescription_id'] ?? null,
            // Who the medicine is for. We keep the id (to link back to the
            // family profile) and a copy of the label, the same way we keep a
            // copy of the address: an old order should still read correctly
            // even if that family member is renamed or removed later.
            'patient_id'     => $data['patient_id'] ?? null,
            'patient_label'  => $data['patient_label'] ?? 'Not specified',
            'delivery_method'=> $method,
            'address'        => $method === 'pickup' ? null : ($data['address'] ?? null),
            'address_notes'  => $method === 'pickup' ? '' : ($data['address_notes'] ?? ''),
            'pickup'         => $method === 'pickup' ? ($data['pickup'] ?? null) : null,
            'items'          => $data['items'],
            'subtotal'       => $data['subtotal'],
            'discount'       => $data['discount'] ?? 0,
            'delivery_fee'   => $data['delivery_fee'],
            'tax'            => $data['tax'] ?? 0,
            'total'          => $data['total'],
            'placed_at'      => date('Y-m-d H:i:s'),
            'delivery_person' => $method === 'pickup' ? null : 'Not assigned yet',
            'tracking'       => $tracking,
        ];

        $store[] = $order;
        return $order;
    }

    public static function statusLabel(string $status): string
    {
        return ucfirst($status);
    }
}
