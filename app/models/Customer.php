<?php

// Customer accounts.
//
// Accounts, passwords and sign-in belong to the shared login (see
// app/models/User.php and AuthenticationController), so there is no
// password handling here. This model only reads a customer account in the
// same array shape Session::user() holds.
class Customer extends Model
{
    public function find(int $id): ?array
    {
        $row = (new User())->findById($id);

        if (!$row || $row['role'] !== 'Customer') {
            return null;
        }

        return User::toSessionUser($row);
    }
}
