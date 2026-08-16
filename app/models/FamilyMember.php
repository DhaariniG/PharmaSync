<?php

// People an account holder can order medicine for: themselves and any family
// members they add. A prescription is uploaded for one of these, so the
// patient name shown on screen comes from a real record rather than a guess.
class FamilyMember extends Model
{
    public const RELATIONSHIPS = ['Self', 'Father', 'Mother', 'Spouse', 'Child', 'Sibling', 'Other'];

    private static function seed(int $userId): array
    {
        return [
            ['id' => 1, 'user_id' => $userId, 'name' => 'Nadeesha Perera',
             'relationship' => 'Self', 'date_of_birth' => '1990-05-14', 'notes' => ''],
            ['id' => 2, 'user_id' => $userId, 'name' => 'Kamal Wijesinghe',
             'relationship' => 'Father', 'date_of_birth' => '1958-02-20',
             'notes' => 'Takes Metformin for diabetes.'],
        ];
    }

    private function &store(int $userId): array
    {
        if (!isset($_SESSION['family_members']) || !is_array($_SESSION['family_members'])) {
            $_SESSION['family_members'] = self::seed($userId);
        }
        return $_SESSION['family_members'];
    }

    public function forUser(int $userId): array
    {
        return array_values(array_filter($this->store($userId), fn($m) => $m['user_id'] === $userId));
    }

    // Look one up, but only within this account. Used to check that a patient
    // really belongs to the person making the request.
    public function find(int $userId, int $id): ?array
    {
        foreach ($this->forUser($userId) as $m) {
            if ($m['id'] === $id) {
                return $m;
            }
        }
        return null;
    }

    // "Father — Kamal Wijesinghe", or just the name when it is the account holder.
    public function label(int $userId, ?int $id): string
    {
        if (!$id) {
            return 'Not specified';
        }
        $m = $this->find($userId, $id);
        if (!$m) {
            return 'Unknown';
        }
        return $m['relationship'] === 'Self'
            ? $m['name']
            : $m['relationship'] . ' — ' . $m['name'];
    }

    public function create(int $userId, array $data): array
    {
        $store = &$this->store($userId);

        $relationship = in_array($data['relationship'] ?? '', self::RELATIONSHIPS, true)
            ? $data['relationship']
            : 'Other';

        $ids = array_column($store, 'id');
        $member = [
            'id'            => ($ids ? max($ids) : 0) + 1,
            'user_id'       => $userId,
            'name'          => $data['name'],
            'relationship'  => $relationship,
            'date_of_birth' => $data['date_of_birth'] ?? '',
            'notes'         => $data['notes'] ?? '',
        ];

        $store[] = $member;
        return $member;
    }
}
