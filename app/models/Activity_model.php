<?php

class Activity_model {
    private $table = 'activities';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getFilteredActivities($filters = []) {
        $query = "SELECT activities.*, users.name as user_name 
                  FROM activities 
                  JOIN users ON activities.user_id = users.id 
                  WHERE 1=1";
        
        if (!empty($filters['user_id'])) {
            $query .= " AND activities.user_id = :user_id";
        }
        
        if (!empty($filters['date'])) {
            $query .= " AND DATE(activities.created_at) = :date";
        }
        
        if (!empty($filters['type'])) {
            $query .= " AND activities.action LIKE :type";
        }
        
        $query .= " ORDER BY activities.created_at DESC";
        
        $this->db->query($query);
        
        if (!empty($filters['user_id'])) {
            $this->db->bind('user_id', $filters['user_id']);
        }
        if (!empty($filters['date'])) {
            $this->db->bind('date', $filters['date']);
        }
        if (!empty($filters['type'])) {
            $this->db->bind('type', '%' . $filters['type'] . '%');
        }
        
        return $this->db->resultSet();
    }

    public function getActivityByUserId($userId) {
        $query = "SELECT activities.*, users.name as user_name 
                  FROM activities 
                  JOIN users ON activities.user_id = users.id 
                  WHERE activities.user_id = :user_id
                  ORDER BY activities.created_at DESC";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->resultSet();
    }

    public function addActivity($data) {
        $query = "INSERT INTO activities (user_id, action, target, status_from, status_to, info, icon, icon_bg) 
                  VALUES (:user_id, :action, :target, :status_from, :status_to, :info, :icon, :icon_bg)";
        $this->db->query($query);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('action', $data['action']);
        $this->db->bind('target', $data['target']);
        $this->db->bind('status_from', $data['status_from']);
        $this->db->bind('status_to', $data['status_to']);
        $this->db->bind('info', $data['info']);
        $this->db->bind('icon', $data['icon']);
        $this->db->bind('icon_bg', $data['icon_bg']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
