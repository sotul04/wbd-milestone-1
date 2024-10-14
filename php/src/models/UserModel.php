<?php

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserById($userId) {
        $this->db->query("SELECT * FROM users WHERE user_id = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->single();
    }

    public function getUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function getAllUsers() {
        $this->db->query("SELECT * FROM users");
        return $this->db->resultSet();
    }

    public function createUser($email, $password, $role, $nama) {
        $this->db->query("INSERT INTO users (email, password, role, nama) VALUES (:email, :password, :role, :nama)");
        $this->db->bind(':email', $email);
        $this->db->bind(':password', password_hash($password, PASSWORD_BCRYPT));
        $this->db->bind(':role', $role);
        $this->db->bind(':nama', $nama);
        return $this->db->execute();
    }

    // Delete user by ID
    public function deleteUser($userId) {
        $this->db->query("DELETE FROM users WHERE user_id = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    // Edit user by ID
    public function editUser($userId, $email, $password = null, $role = null, $nama = null) {
        // Prepare base SQL
        $query = "UPDATE users SET email = :email";

        // Conditionally add password, role, and nama updates if provided
        if (!empty($password)) {
            $query .= ", password = :password";
        }
        if (!empty($role)) {
            $query .= ", role = :role";
        }
        if (!empty($nama)) {
            $query .= ", nama = :nama";
        }

        $query .= " WHERE user_id = :userId";

        // Prepare query
        $this->db->query($query);

        // Bind basic values
        $this->db->bind(':email', $email);
        $this->db->bind(':userId', $userId);

        // Conditionally bind additional fields if they are provided
        if (!empty($password)) {
            $this->db->bind(':password', password_hash($password, PASSWORD_BCRYPT));
        }
        if (!empty($role)) {
            $this->db->bind(':role', $role);
        }
        if (!empty($nama)) {
            $this->db->bind(':nama', $nama);
        }

        // Execute query
        return $this->db->execute();
    }
}
