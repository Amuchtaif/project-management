<?php

class Task_model {
    private $table = 'tasks';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllTasks() {
        $query = "SELECT tasks.*, projects.name as project_name, u.name as assignee_name 
                  FROM tasks 
                  LEFT JOIN projects ON tasks.project_id = projects.id 
                  LEFT JOIN users u ON tasks.assignee_id = u.id 
                  ORDER BY (CASE WHEN tasks.priority = 'tinggi' THEN 1 WHEN tasks.priority = 'sedang' THEN 2 ELSE 3 END), tasks.created_at DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getFilteredTasks($role, $userId, $filters = []) {
        $query = "SELECT tasks.*, projects.name as project_name, u.name as assignee_name 
                  FROM tasks 
                  LEFT JOIN projects ON tasks.project_id = projects.id 
                  LEFT JOIN users u ON tasks.assignee_id = u.id 
                  WHERE 1=1";
        
        if ($role !== 'admin') {
            $query .= " AND tasks.assignee_id = :user_id";
        }

        if (!empty($filters['project_id'])) {
            $query .= " AND tasks.project_id = :project_id";
        }

        if (!empty($filters['search'])) {
            $query .= " AND (tasks.title LIKE :search OR projects.name LIKE :search)";
        }

        $query .= " ORDER BY (CASE WHEN tasks.priority = 'tinggi' THEN 1 WHEN tasks.priority = 'sedang' THEN 2 ELSE 3 END), tasks.created_at DESC";

        $this->db->query($query);
        
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }

        if (!empty($filters['project_id'])) {
            $this->db->bind('project_id', $filters['project_id']);
        }

        if (!empty($filters['search'])) {
            $this->db->bind('search', '%' . $filters['search'] . '%');
        }

        return $this->db->resultSet();
    }

    /**
     * Ambil tugas berdasarkan member ID (tugas yang ditugaskan ke member tsb)
     */
    public function getTasksByMemberId($userId) {
        $query = "SELECT tasks.*, projects.name as project_name, u.name as assignee_name 
                  FROM tasks 
                  LEFT JOIN projects ON tasks.project_id = projects.id 
                  LEFT JOIN users u ON tasks.assignee_id = u.id 
                  WHERE tasks.assignee_id = :user_id 
                  ORDER BY tasks.created_at DESC";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    public function getTasksByStatus($status) {
        $query = "SELECT tasks.*, projects.name as project_name, u.name as assignee_name 
                  FROM tasks 
                  LEFT JOIN projects ON tasks.project_id = projects.id 
                  LEFT JOIN users u ON tasks.assignee_id = u.id 
                  WHERE tasks.status = :status 
                  ORDER BY tasks.created_at DESC";
        $this->db->query($query);
        $this->db->bind('status', $status);
        return $this->db->resultSet();
    }

    public function addTask($data) {
        $query = "INSERT INTO tasks (title, project_id, assignee_id, tag, tag_color, priority, due_date, status, progress) 
                  VALUES (:title, :project_id, :assignee_id, :tag, :tag_color, :priority, :due_date, :status, :progress)";
        $this->db->query($query);
        $this->db->bind('title', $data['title']);
        $this->db->bind('project_id', $data['project_id']);
        $this->db->bind('assignee_id', $data['assignee_id']);
        $this->db->bind('tag', $data['tag']);
        $this->db->bind('tag_color', $data['tag_color']);
        $this->db->bind('priority', $data['priority']);
        $this->db->bind('due_date', $data['due_date']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('progress', $data['progress']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getTaskById($id) {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function updateTask($data) {
        $query = "UPDATE tasks SET title=:title, project_id=:project_id, assignee_id=:assignee_id, tag=:tag, tag_color=:tag_color, priority=:priority, due_date=:due_date, status=:status, progress=:progress WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $data['id']);
        $this->db->bind('title', $data['title']);
        $this->db->bind('project_id', $data['project_id']);
        $this->db->bind('assignee_id', $data['assignee_id']);
        $this->db->bind('tag', $data['tag']);
        $this->db->bind('tag_color', $data['tag_color']);
        $this->db->bind('priority', $data['priority']);
        $this->db->bind('due_date', $data['due_date']);
        $this->db->bind('status', $data['status']);
        $this->db->bind('progress', $data['progress']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteTask($id) {
        $this->db->query("DELETE FROM " . $this->table . " WHERE id=:id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    /**
     * Ambil distribusi tugas berdasarkan status
     */
    public function getTaskDistribution($role, $userId) {
        $query = "SELECT status, COUNT(*) as count FROM tasks";
        
        if ($role !== 'admin') {
            $query .= " WHERE assignee_id = :user_id";
        }

        $query .= " GROUP BY status";

        $this->db->query($query);
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }

        return $this->db->resultSet();
    }

    public function getDeadlineNotifications($role, $userId) {
        $query = "SELECT t.*, p.name as project_name, 
                  DATEDIFF(t.due_date, CURDATE()) as days_left
                  FROM tasks t
                  JOIN projects p ON t.project_id = p.id
                  WHERE t.status != 'done' 
                  AND DATEDIFF(t.due_date, CURDATE()) IN (1, 3)";
        
        if ($role !== 'admin') {
            $query .= " AND t.assignee_id = :user_id";
        }
        
        $query .= " ORDER BY days_left ASC";
        
        $this->db->query($query);
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }
        
        return $this->db->resultSet();
    }
}
