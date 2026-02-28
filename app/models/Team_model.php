<?php

class Team_model {
    private $table = 'users';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Ambil semua anggota tim (semua user kecuali bisa difilter)
     */
    public function getAllTeam() {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE role = 'member' ORDER BY created_at DESC");
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
     * Tambah anggota baru — otomatis buat akun user dengan password ter-hash
     */
    public function addMember($data) {
        // Cek apakah email sudah terdaftar
        $this->db->query("SELECT id FROM users WHERE email = :email");
        $this->db->bind('email', $data['email']);
        $existing = $this->db->single();
        
        if ($existing) {
            return 0; // Email sudah terdaftar
        }

        // Hash password — gunakan password dari form, atau generate default
        $password = !empty($data['password']) 
            ? $data['password'] 
            : strtolower(str_replace(' ', '', $data['name'])) . '123';
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (name, email, password, role, role_label, department, status, role_color, status_color, last_active) 
                  VALUES (:name, :email, :password, 'member', :role_label, :department, :status, :role_color, :status_color, NOW())";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('password', $hashedPassword);
        $this->db->bind('role_label', $data['role']);
        $this->db->bind('department', $data['department']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('role_color', $data['role_color']);
        $this->db->bind('status_color', $data['status_color']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Update data anggota (tanpa mengubah password kecuali diminta)
     */
    public function updateMember($data) {
        // Jika password baru diisi, update juga password-nya
        if (!empty($data['password'])) {
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $query = "UPDATE users SET name=:name, email=:email, password=:password, role_label=:role_label, department=:department, status=:status, role_color=:role_color, status_color=:status_color WHERE id=:id";
            $this->db->query($query);
            $this->db->bind('password', $hashedPassword);
        } else {
            $query = "UPDATE users SET name=:name, email=:email, role_label=:role_label, department=:department, status=:status, role_color=:role_color, status_color=:status_color WHERE id=:id";
            $this->db->query($query);
        }
        
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('email', $data['email']);
        $this->db->bind('role_label', $data['role']);
        $this->db->bind('department', $data['department']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('role_color', $data['role_color']);
        $this->db->bind('status_color', $data['status_color']);
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
