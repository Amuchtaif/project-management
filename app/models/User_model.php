<?php

class User_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Ambil user berdasarkan email (untuk login)
     */
    public function getUserByEmail($email) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE email=:email");
        $this->db->bind('email', $email);
        return $this->db->single();
    }

    /**
     * Ambil user berdasarkan ID
     */
    public function getUserById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    /**
     * Update last_active timestamp
     */
    public function updateLastActive($userId) {
        $this->db->query("UPDATE users SET last_active = NOW() WHERE id = :id");
        $this->db->bind('id', $userId);
        $this->db->execute();
    }

    /**
     * Hitung total user aktif
     */
    public function countActiveMembers() {
        $this->db->query("SELECT COUNT(*) as total FROM users WHERE status = 'AKTIF'");
        return $this->db->single()['total'];
    }
}
