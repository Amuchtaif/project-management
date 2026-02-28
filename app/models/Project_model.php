<?php

class Project_model {
    private $table = 'projects';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Ambil semua proyek (untuk admin)
     */
    public function getAllProjects() {
        $query = "SELECT p.*, u.name as leader_name 
                  FROM projects p
                  LEFT JOIN project_members pm ON p.id = pm.project_id AND pm.role_in_project = 'leader'
                  LEFT JOIN users u ON pm.user_id = u.id
                  ORDER BY p.created_at DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    /**
     * Ambil proyek berdasarkan ID
     */
    public function getProjectById($id) {
        $query = "SELECT p.*, pm.user_id as leader_id 
                  FROM projects p
                  LEFT JOIN project_members pm ON p.id = pm.project_id AND pm.role_in_project = 'leader'
                  WHERE p.id = :id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    /**
     * Ambil proyek yang ditugaskan ke member tertentu (via project_members)
     */
    public function getProjectsByMemberId($userId) {
        $query = "SELECT p.*, u.name as leader_name FROM projects p 
                  INNER JOIN project_members pm_all ON p.id = pm_all.project_id 
                  LEFT JOIN project_members pm ON p.id = pm.project_id AND pm.role_in_project = 'leader'
                  LEFT JOIN users u ON pm.user_id = u.id
                  WHERE pm_all.user_id = :user_id 
                  ORDER BY p.created_at DESC";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Hitung proyek berdasarkan member ID
     */
    public function countProjectsByMemberId($userId) {
        $query = "SELECT COUNT(*) as total FROM projects p 
                  INNER JOIN project_members pm ON p.id = pm.project_id 
                  WHERE pm.user_id = :user_id";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->single()['total'];
    }

    /**
     * Ambil proyek berdasarkan role user dengan filter
     * Admin = semua proyek, Member = hanya proyek yang ditugaskan
     */
    public function getProjectsByRole($role, $userId, $filters = []) {
        $query = "SELECT DISTINCT p.*, u.name as leader_name,
                  (SELECT COALESCE(AVG(progress), 0) FROM tasks WHERE project_id = p.id) as avg_progress 
                  FROM projects p
                  LEFT JOIN project_members pm ON p.id = pm.project_id AND pm.role_in_project = 'leader'
                  LEFT JOIN users u ON pm.user_id = u.id";
        
        if ($role !== 'admin') {
            $query .= " INNER JOIN project_members pm_all ON p.id = pm_all.project_id AND pm_all.user_id = :user_id";
        }

        $query .= " WHERE 1=1";

        if (!empty($filters['status'])) {
            $query .= " AND p.status = :status";
        }

        if (!empty($filters['search'])) {
            $query .= " AND (p.name LIKE :search OR p.client LIKE :search)";
        }

        $query .= " ORDER BY p.created_at DESC";

        $this->db->query($query);
        
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }

        if (!empty($filters['status'])) {
            $this->db->bind('status', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $this->db->bind('search', '%' . $filters['search'] . '%');
        }

        return $this->db->resultSet();
    }

    public function addProject($data) {
        $query = "INSERT INTO projects (name, client, due_date, status, icon, status_color) VALUES (:name, :client, :due_date, :status, :icon, :status_color)";
        $this->db->query($query);
        $this->db->bind('name', $data['name']);
        $this->db->bind('client', $data['client']);
        $this->db->bind('due_date', $data['due_date']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('icon', $data['icon']);
        $this->db->bind('status_color', $data['status_color']);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function updateProject($data) {
        $query = "UPDATE projects SET name=:name, client=:client, due_date=:due_date, status=:status, icon=:icon, status_color=:status_color WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('name', $data['name']);
        $this->db->bind('client', $data['client']);
        $this->db->bind('due_date', $data['due_date']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('icon', $data['icon']);
        $this->db->bind('status_color', $data['status_color']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteProject($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Ambil anggota yang ditugaskan ke proyek tertentu
     */
    public function getProjectMembers($projectId) {
        $query = "SELECT u.id, u.name, u.email, u.role_label, pm.role_in_project, pm.assigned_at 
                  FROM project_members pm 
                  INNER JOIN users u ON pm.user_id = u.id 
                  WHERE pm.project_id = :project_id 
                  ORDER BY pm.assigned_at DESC";
        $this->db->query($query);
        $this->db->bind('project_id', $projectId);
        return $this->db->resultSet();
    }

    /**
     * Assign member ke proyek
     */
    public function assignMember($projectId, $userId, $roleInProject = 'member') {
        $query = "INSERT INTO project_members (project_id, user_id, role_in_project) 
                  VALUES (:project_id, :user_id, :role_in_project)
                  ON DUPLICATE KEY UPDATE role_in_project = :role_in_project_update";
        $this->db->query($query);
        $this->db->bind('project_id', $projectId);
        $this->db->bind('user_id', $userId);
        $this->db->bind('role_in_project', $roleInProject);
        $this->db->bind('role_in_project_update', $roleInProject);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Hapus member dari proyek
     */
    public function removeMember($projectId, $userId) {
        $query = "DELETE FROM project_members WHERE project_id = :project_id AND user_id = :user_id";
        $this->db->query($query);
        $this->db->bind('project_id', $projectId);
        $this->db->bind('user_id', $userId);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function removeLeader($projectId) {
        $query = "DELETE FROM project_members WHERE project_id = :project_id AND role_in_project = 'leader'";
        $this->db->query($query);
        $this->db->bind('project_id', $projectId);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Ambil data progres proyek (rata-rata progres tugas)
     */
    public function getProjectsProgress($role, $userId) {
        $query = "SELECT p.id, p.name, p.status_color, p.created_at, p.status,
                  COALESCE(AVG(t.progress), 0) as avg_progress,
                  u.name as leader_name
                  FROM projects p
                  LEFT JOIN tasks t ON p.id = t.project_id
                  LEFT JOIN project_members pm_leader ON p.id = pm_leader.project_id AND pm_leader.role_in_project = 'leader'
                  LEFT JOIN users u ON pm_leader.user_id = u.id";
        
        if ($role !== 'admin') {
            $query .= " INNER JOIN project_members pm_all ON p.id = pm_all.project_id AND pm_all.user_id = :user_id";
        }

        $query .= " GROUP BY p.id ORDER BY p.created_at DESC";

        $this->db->query($query);
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }

        return $this->db->resultSet();
    }
}
