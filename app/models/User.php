<?php

class User extends Model {

    // Fetch user by email address
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_OBJ); // Returns a single user object
    }

    // Register a new Customer (uses database transaction)
    public function registerCustomer($data) {
        try {
            $this->db->beginTransaction();

            // 1. Insert into core users table
            $userSql = "INSERT INTO users (full_name, email, phone, address, password_hash, role, status) 
                        VALUES (:full_name, :email, :phone, :address, :password_hash, 'Customer', 'Active')";
            
            $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

            $stmtUser = $this->db->prepare($userSql);
            $stmtUser->execute([
                'full_name'     => $data['full_name'],
                'email'         => $data['email'],
                'phone'         => $data['phone'],
                'address'       => !empty($data['address']) ? $data['address'] : null,
                'password_hash' => $passwordHash
            ]);

            $userId = $this->db->lastInsertId();

            // 2. Insert into customer_profiles table
            $profileSql = "INSERT INTO customer_profiles (user_id, date_of_birth, allergies, medical_conditions) 
                           VALUES (:user_id, :dob, :allergies, :medical_conditions)";
            
            $stmtProfile = $this->db->prepare($profileSql);
            $stmtProfile->execute([
                'user_id'            => $userId,
                'dob'                => !empty($data['date_of_birth']) ? $data['date_of_birth'] : null,
                'allergies'          => !empty($data['allergies']) ? $data['allergies'] : null,
                'medical_conditions' => !empty($data['medical_conditions']) ? $data['medical_conditions'] : null
            ]);

            $this->db->commit();
            return $userId;

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    // Update last login timestamp
    public function recordLogin($userId) {
        $sql = "UPDATE users SET last_login = NOW() WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
    }
}