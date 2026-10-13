<?php

class Team_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Ambil semua anggota tim (exclude user administrator utama sistem)
     * User yang memiliki hak akses admin tetap ditampilkan kecuali akun Administrator utama
     */
    public function getAllTeam() {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id != 1 AND email != 'admin@gmail.com' AND LOWER(name) != 'administrator' ORDER BY created_at DESC");
        return $this->db->resultSet();
    }

    /**
     * Ambil anggota berdasarkan ID
     */
    public function getMemberById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    /**
     * Tambah anggota baru — otomatis buat akun user dengan password ter-hash & hak akses sistem
     */
    public function addMember($data) {
        // Cek apakah email sudah terdaftar
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind('email', $data['email']);
        $existing = $this->db->single();
        
        if ($existing) {
            return 0; // Email sudah terdaftar
        }

        // Hash password — gunakan password dari form, atau generate default password123
        $password = !empty($data['password']) 
            ? $data['password'] 
            : 'password123';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $systemRole = (!empty($data['system_role']) && in_array($data['system_role'], ['admin', 'member'])) 
            ? $data['system_role'] 
            : 'member';

        $query = "INSERT INTO users (name, email, password, role, role_label, department, status, role_color, status_color, last_active) 
                  VALUES (:name, :email, :password, :role, :role_label, :department, :status, :role_color, :status_color, NOW())";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $hashedPassword);
        $this->db->bind('role', $systemRole);
        $this->db->bind('role_label', $data['role']);
        $this->db->bind('department', $data['department']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('role_color', $data['role_color']);
        $this->db->bind('status_color', $data['status_color']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Update data anggota (termasuk hak akses dan password jika diisi)
     */
    public function updateMember($data) {
        $systemRole = (!empty($data['system_role']) && in_array($data['system_role'], ['admin', 'member'])) 
            ? $data['system_role'] 
            : 'member';

        // Cegah admin mendemote akunnya sendiri
        if (isset($_SESSION['user']['id']) && $data['id'] == $_SESSION['user']['id']) {
            $systemRole = $_SESSION['user']['role'];
        }

        // Jika password baru diisi, update juga password-nya
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = "UPDATE users SET name=:name, email=:email, password=:password, role=:role, role_label=:role_label, department=:department, status=:status, role_color=:role_color, status_color=:status_color WHERE id=:id";
            $this->db->query($query);
            $this->db->bind('password', $hashedPassword);
        } else {
            $query = "UPDATE users SET name=:name, email=:email, role=:role, role_label=:role_label, department=:department, status=:status, role_color=:role_color, status_color=:status_color WHERE id=:id";
            $this->db->query($query);
        }
        
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('role', $systemRole);
        $this->db->bind('role_label', $data['role']);
        $this->db->bind('department', $data['department']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('role_color', $data['role_color']);
        $this->db->bind('status_color', $data['status_color']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Reset password anggota
     */
    public function resetPassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_BCRYPT);
        $this->db->query("UPDATE " . $this->table . " SET password = :password WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->bind('password', $hashed);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Update hak akses sistem (admin vs member)
     */
    public function updateAccessRole($id, $systemRole) {
        if (!in_array($systemRole, ['admin', 'member'])) {
            return 0;
        }
        $this->db->query("UPDATE " . $this->table . " SET role = :role WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->bind('role', $systemRole);
        $this->db->execute();
        return $this->db->rowCount();
    }


    /**
     * Hapus anggota
     */
    public function deleteMember($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Ambil semua member (role = member) untuk dropdown assignment
     */
    public function getAllMembers() {
        $this->db->query("SELECT id, name, email, role_label, department FROM users WHERE role = 'member' AND status = 'AKTIF' ORDER BY name ASC");
        return $this->db->resultSet();
    }
    /**
     * Update profil sendiri (Nama, dan Password jika diisi)
     */
    public function updateProfile($data) {
        $id = $_SESSION['user']['id'];
        
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = "UPDATE users SET name=:name, password=:password WHERE id=:id";
            $this->db->query($query);
            $this->db->bind('password', $hashedPassword);
        } else {
            $query = "UPDATE users SET name=:name WHERE id=:id";
            $this->db->query($query);
        }
        
        $this->db->bind('id', $id);
        $this->db->bind('name', $data['name']);
        $this->db->execute();
        
        // Update Session name
        if ($this->db->rowCount() > 0 || empty($data['password'])) {
             $_SESSION['user']['name'] = $data['name'];
        }
        
        return $this->db->rowCount();
    }
}
