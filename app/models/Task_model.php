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
                  ORDER BY tasks.due_date ASC, (CASE WHEN tasks.priority = 'tinggi' THEN 1 WHEN tasks.priority = 'sedang' THEN 2 ELSE 3 END) ASC, tasks.created_at DESC";
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

        if (!empty($filters['filter_type'])) {
            if ($filters['filter_type'] === 'overdue') {
                $query .= " AND tasks.due_date < CURDATE() AND tasks.status != 'done'";
            } elseif ($filters['filter_type'] === 'urgent') {
                $query .= " AND tasks.priority = 'tinggi'";
            } elseif ($filters['filter_type'] === 'my_tasks') {
                $query .= " AND tasks.assignee_id = :my_user_id";
            }
        }

        $query .= " ORDER BY tasks.due_date ASC, (CASE WHEN tasks.priority = 'tinggi' THEN 1 WHEN tasks.priority = 'sedang' THEN 2 ELSE 3 END) ASC";

        $this->db->query($query);
        
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }

        if (!empty($filters['filter_type']) && $filters['filter_type'] === 'my_tasks') {
            $this->db->bind('my_user_id', $userId);
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
                  ORDER BY tasks.due_date ASC, tasks.created_at DESC";
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
                  ORDER BY tasks.due_date ASC, tasks.created_at DESC";
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

    public function updateTaskStatus($id, $status, $progress) {
        $query = "UPDATE " . $this->table . " SET status=:status, progress=:progress WHERE id=:id";
        $this->db->query($query);
        $this->db->bind('id', $id);
        $this->db->bind('status', $status);
        $this->db->bind('progress', $progress);
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
        $this->syncLeaderNotifications($userId);
        $this->syncDeadlineNotifications($role, $userId);

        $results = [];

        // Ambil notifikasi yang belum dibaca (is_read = 0) untuk user ini
        $this->db->query("SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC LIMIT 20");
        $this->db->bind('user_id', $userId);
        $userNotifs = $this->db->resultSet();
        foreach ($userNotifs as $un) {
            $results[] = [
                'id' => $un['id'],
                'type' => $un['type'],
                'title' => $un['title'],
                'message' => $un['message'],
                'link' => $un['link'],
                'project_id' => $un['project_id'],
                'task_id' => $un['task_id'] ?? null,
                'is_read' => (int)$un['is_read'],
                'created_at' => $un['created_at'],
                'time_ago' => $this->formatTimeAgo($un['created_at'])
            ];
        }

        return $results;
    }

    private function syncDeadlineNotifications($role, $userId) {
        // Ambil notifikasi deadline tugas yang mendekat (0 - 3 hari)
        $query = "SELECT t.*, p.name as project_name, 
                  DATEDIFF(t.due_date, CURDATE()) as days_left
                  FROM tasks t
                  JOIN projects p ON t.project_id = p.id
                  WHERE t.status != 'done' 
                  AND DATEDIFF(t.due_date, CURDATE()) BETWEEN 0 AND 3";
        
        if ($role !== 'admin') {
            $query .= " AND t.assignee_id = :user_id";
        }
        
        $query .= " ORDER BY days_left ASC LIMIT 10";
        
        $this->db->query($query);
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }
        
        $deadlines = $this->db->resultSet();
        foreach ($deadlines as $d) {
            $daysLeft = (int)$d['days_left'];
            $targetUserId = ($role === 'admin') ? $userId : $userId;

            // Cek apakah sudah pernah dibuat notifikasi deadline untuk tugas ini hari ini
            $this->db->query("SELECT id FROM notifications 
                              WHERE user_id = :user_id AND type = 'deadline' AND task_id = :task_id AND DATE(created_at) = CURDATE()");
            $this->db->bind('user_id', $targetUserId);
            $this->db->bind('task_id', $d['id']);
            $exists = $this->db->single();

            if (!$exists) {
                $daysText = ($daysLeft === 0) ? 'hari ini' : $daysLeft . ' hari lagi';
                $msg = 'Deadline ' . $daysText . ' (' . ($d['project_name'] ?? 'Project') . ')';
                $link = BASEURL . '/task?project_id=' . $d['project_id'] . '&task_id=' . $d['id'];

                $this->db->query("INSERT INTO notifications (user_id, type, title, message, link, project_id, task_id, is_read, created_at)
                                  VALUES (:user_id, 'deadline', :title, :message, :link, :project_id, :task_id, 0, NOW())");
                $this->db->bind('user_id', $targetUserId);
                $this->db->bind('title', 'Deadline: ' . $d['title']);
                $this->db->bind('message', $msg);
                $this->db->bind('link', $link);
                $this->db->bind('project_id', $d['project_id']);
                $this->db->bind('task_id', $d['id']);
                $this->db->execute();
            }
        }
    }

    private function syncLeaderNotifications($userId) {
        $this->db->query("SELECT p.id, p.name, p.created_at 
                          FROM projects p 
                          JOIN project_members pm ON p.id = pm.project_id 
                          WHERE pm.user_id = :user_id AND pm.role_in_project = 'leader'");
        $this->db->bind('user_id', $userId);
        $ledProjects = $this->db->resultSet();

        foreach ($ledProjects as $lp) {
            // Notifikasi project baru untuk lead
            $this->db->query("SELECT id FROM notifications 
                              WHERE user_id = :user_id AND type = 'new_project' AND project_id = :project_id");
            $this->db->bind('user_id', $userId);
            $this->db->bind('project_id', $lp['id']);
            $exists = $this->db->single();
            if (!$exists) {
                $this->db->query("INSERT INTO notifications (user_id, type, title, message, link, project_id, is_read, created_at)
                                  VALUES (:user_id, 'new_project', :title, :message, :link, :project_id, 0, :created_at)");
                $this->db->bind('user_id', $userId);
                $this->db->bind('title', 'Project Baru: ' . $lp['name']);
                $this->db->bind('message', 'Anda terpilih sebagai Project Leader untuk project ' . $lp['name'] . '.');
                $this->db->bind('link', BASEURL . '/task?project_id=' . $lp['id']);
                $this->db->bind('project_id', $lp['id']);
                $this->db->bind('created_at', $lp['created_at'] ?? date('Y-m-d H:i:s'));
                $this->db->execute();
            }

            // Notifikasi task baru pada project ini untuk lead
            $this->db->query("SELECT id, title, created_at FROM tasks 
                              WHERE project_id = :project_id ORDER BY id DESC LIMIT 5");
            $this->db->bind('project_id', $lp['id']);
            $tasks = $this->db->resultSet();
            foreach ($tasks as $t) {
                $this->db->query("SELECT id FROM notifications 
                                  WHERE user_id = :user_id AND type = 'new_task' AND task_id = :task_id");
                $this->db->bind('user_id', $userId);
                $this->db->bind('task_id', $t['id']);
                $taskExists = $this->db->single();
                if (!$taskExists) {
                    $this->db->query("INSERT INTO notifications (user_id, type, title, message, link, project_id, task_id, is_read, created_at)
                                      VALUES (:user_id, 'new_task', :title, :message, :link, :project_id, :task_id, 0, :created_at)");
                    $this->db->bind('user_id', $userId);
                    $this->db->bind('title', 'Task Baru: ' . $t['title']);
                    $this->db->bind('message', 'Task baru "' . $t['title'] . '" ditambahkan pada project ' . $lp['name'] . '.');
                    $this->db->bind('link', BASEURL . '/task?project_id=' . $lp['id']);
                    $this->db->bind('project_id', $lp['id']);
                    $this->db->bind('task_id', $t['id']);
                    $this->db->bind('created_at', $t['created_at'] ?? date('Y-m-d H:i:s'));
                    $this->db->execute();
                }
            }
        }
    }

    private function formatTimeAgo($datetime) {
        if (empty($datetime)) return '';
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 0) return 'Mendatang';
        if ($diff < 60) return 'Baru saja';
        if ($diff < 3600) return floor($diff / 60) . ' mnt lalu';
        if ($diff < 86400) return floor($diff / 3600) . ' jam lalu';
        if ($diff < 604800) return floor($diff / 86400) . ' hari lalu';
        return date('d M Y', $time);
    }


    public function getNearDeadlineTasks($role, $userId, $limit = 5) {
        $query = "SELECT t.*, p.name as project_name, u.name as assignee_name,
                  DATEDIFF(t.due_date, CURDATE()) as days_left
                  FROM tasks t
                  JOIN projects p ON t.project_id = p.id
                  LEFT JOIN users u ON t.assignee_id = u.id
                  WHERE t.status != 'done' 
                  AND DATEDIFF(t.due_date, CURDATE()) <= 7";
        
        if ($role !== 'admin') {
            $query .= " AND t.assignee_id = :user_id";
        }
        
        $query .= " ORDER BY days_left ASC, (CASE WHEN t.priority = 'tinggi' THEN 1 WHEN t.priority = 'sedang' THEN 2 ELSE 3 END) ASC
                   LIMIT :limit";
        
        $this->db->query($query);
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }
        $this->db->bind('limit', $limit);
        
        return $this->db->resultSet();
    }

    public function getOverdueCount($role, $userId, $projectId = null) {
        $query = "SELECT COUNT(*) as total FROM tasks WHERE status != 'done' AND due_date < CURDATE()";
        if ($role !== 'admin') {
            $query .= " AND assignee_id = :user_id";
        }
        if (!empty($projectId)) {
            $query .= " AND project_id = :project_id";
        }
        $this->db->query($query);
        if ($role !== 'admin') {
            $this->db->bind('user_id', $userId);
        }
        if (!empty($projectId)) {
            $this->db->bind('project_id', $projectId);
        }
        return (int)($this->db->single()['total'] ?? 0);
    }

    // ================= SUBTASKS METHODS =================
    public function getSubtasks($taskId) {
        $this->db->query("SELECT * FROM task_subtasks WHERE task_id = :task_id ORDER BY id ASC");
        $this->db->bind('task_id', $taskId);
        return $this->db->resultSet();
    }

    public function addSubtask($taskId, $title) {
        $this->db->query("INSERT INTO task_subtasks (task_id, title, is_completed) VALUES (:task_id, :title, 0)");
        $this->db->bind('task_id', $taskId);
        $this->db->bind('title', trim($title));
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function toggleSubtask($id) {
        $this->db->query("UPDATE task_subtasks SET is_completed = IF(is_completed = 1, 0, 1) WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deleteSubtask($id) {
        $this->db->query("DELETE FROM task_subtasks WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ================= COMMENTS METHODS =================
    public function getComments($taskId) {
        $this->db->query("SELECT tc.*, u.name as user_name, u.role, u.role_label 
                          FROM task_comments tc 
                          JOIN users u ON tc.user_id = u.id 
                          WHERE tc.task_id = :task_id 
                          ORDER BY tc.created_at ASC");
        $this->db->bind('task_id', $taskId);
        return $this->db->resultSet();
    }

    public function addComment($taskId, $userId, $comment) {
        $this->db->query("INSERT INTO task_comments (task_id, user_id, comment) VALUES (:task_id, :user_id, :comment)");
        $this->db->bind('task_id', $taskId);
        $this->db->bind('user_id', $userId);
        $this->db->bind('comment', trim($comment));
        $this->db->execute();
        $rowCount = $this->db->rowCount();
        if ($rowCount > 0) {
            $this->clearTyping($taskId, $userId);
        }
        return $rowCount;
    }

    public function deleteComment($id, $userId, $userRole) {
        if ($userRole === 'admin') {
            $this->db->query("DELETE FROM task_comments WHERE id = :id");
            $this->db->bind('id', $id);
        } else {
            $this->db->query("DELETE FROM task_comments WHERE id = :id AND user_id = :user_id");
            $this->db->bind('id', $id);
            $this->db->bind('user_id', $userId);
        }
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ================= REALTIME TYPING STATUS METHODS =================
    public function getTypingUsers($taskId, $currentUserId) {
        $this->db->query("SELECT user_name FROM task_typing 
                          WHERE task_id = :task_id 
                          AND user_id != :current_user_id 
                          AND updated_at >= NOW() - INTERVAL 4 SECOND");
        $this->db->bind('task_id', $taskId);
        $this->db->bind('current_user_id', $currentUserId);
        return $this->db->resultSet();
    }

    public function updateTyping($taskId, $userId, $userName) {
        $this->db->query("INSERT INTO task_typing (task_id, user_id, user_name, updated_at) 
                          VALUES (:task_id, :user_id, :user_name, NOW()) 
                          ON DUPLICATE KEY UPDATE updated_at = NOW()");
        $this->db->bind('task_id', $taskId);
        $this->db->bind('user_id', $userId);
        $this->db->bind('user_name', $userName);
        $this->db->execute();
        return true;
    }

    public function clearTyping($taskId, $userId) {
        $this->db->query("DELETE FROM task_typing WHERE task_id = :task_id AND user_id = :user_id");
        $this->db->bind('task_id', $taskId);
        $this->db->bind('user_id', $userId);
        $this->db->execute();
        return true;
    }
}
