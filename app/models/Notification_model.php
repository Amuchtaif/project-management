<?php

class Notification_model {
    private $table = 'notifications';
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addNotification($userId, $type, $title, $message, $link, $projectId = null, $taskId = null) {
        $query = "INSERT INTO " . $this->table . " (user_id, type, title, message, link, project_id, task_id, is_read, created_at)
                  VALUES (:user_id, :type, :title, :message, :link, :project_id, :task_id, 0, NOW())";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        $this->db->bind('type', $type);
        $this->db->bind('title', $title);
        $this->db->bind('message', $message);
        $this->db->bind('link', $link);
        $this->db->bind('project_id', $projectId);
        $this->db->bind('task_id', $taskId);
        $this->db->execute();
        return $this->db->lastInsertId();
    }

    public function getUserNotifications($userId, $limit = 20) {
        $query = "SELECT * FROM " . $this->table . " 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        $this->db->bind('limit', $limit);
        return $this->db->resultSet();
    }

    public function getUnreadCount($userId) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                  WHERE user_id = :user_id AND is_read = 0";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return (int)($this->db->single()['total'] ?? 0);
    }

    public function markAsRead($id, $userId) {
        $query = "UPDATE " . $this->table . " SET is_read = 1 WHERE id = :id AND user_id = :user_id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('user_id', $userId);
        return $this->db->execute();
    }

    public function markAllAsRead($userId) {
        $query = "UPDATE " . $this->table . " SET is_read = 1 WHERE user_id = :user_id";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->execute();
    }

    public function clearAll($userId) {
        $query = "UPDATE " . $this->table . " SET is_read = 1 WHERE user_id = :user_id";
        $this->db->query($query);
        $this->db->bind('user_id', $userId);
        return $this->db->execute();
    }
}
