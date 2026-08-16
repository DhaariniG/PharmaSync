<?php

// Saved delivery addresses (session-backed for now).
class Address extends Model
{
    private static function seed(int $userId): array
    {
        return [
            ['id' => 1, 'user_id' => $userId, 'label' => 'Home', 'line1' => '12 Galle Road, Colombo 03', 'city' => 'Colombo', 'phone' => '+94 77 123 4567', 'is_default' => true],
            ['id' => 2, 'user_id' => $userId, 'label' => 'Work', 'line1' => 'No 10, Kandy Road, Kiribathgoda', 'city' => 'Kiribathgoda', 'phone' => '+94 77 123 4567', 'is_default' => false],
        ];
    }

    private function &store(int $userId): array
    {
        if (!isset($_SESSION['addresses']) || !is_array($_SESSION['addresses'])) {
            $_SESSION['addresses'] = self::seed($userId);
        }
        return $_SESSION['addresses'];
    }

    public function forUser(int $userId): array
    {
        return array_values(array_filter($this->store($userId), fn($a) => $a['user_id'] === $userId));
    }

    public function find(int $userId, int $id): ?array
    {
        foreach ($this->forUser($userId) as $a) {
            if ($a['id'] === $id) {
                return $a;
            }
        }
        return null;
    }

    public function create(int $userId, array $data): array
    {
        $store = &$this->store($userId);
        $address = [
            'id'         => count($store) + 1,
            'user_id'    => $userId,
            'label'      => $data['label'] ?? 'Other',
            'line1'      => $data['line1'],
            'city'       => $data['city'] ?? '',
            'phone'      => $data['phone'] ?? '',
            'is_default' => false,
        ];
        $store[] = $address;
        return $address;
    }
}
