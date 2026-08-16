<?php

// Customer accounts.
//
// Accounts, passwords and sign-in belong to the shared PharmaSync login, not
// to this module — so there is no password handling here. This model only
// supplies the demo customer that stands in for a signed-in user until the
// shared login is merged (see AUTO_SIGN_IN_DEMO_CUSTOMER in config.php).
//
// Once the DB is connected, replace demoUser() with a real
// `SELECT ... FROM customers WHERE id = ?` lookup.
class Customer extends Model
{
    public function demoUser(): array
    {
        return [
            'id'               => 1,
            'name'             => 'Nadeesha Perera',
            'email'            => 'customer@example.com',
            'phone'            => '+94 77 123 4567',
            'address'          => '12 Galle Road, Colombo 03',
            'dob'              => '1990-05-14',
            'health_points'    => 2450,
            'allergies'        => ['Penicillin', 'Peanuts'],
            'conditions'       => ['Hypertension'],
            'profile_complete' => 85,
        ];
    }

    public function find(int $id): ?array
    {
        $user = $this->demoUser();
        return $user['id'] === $id ? $user : null;
    }
}
