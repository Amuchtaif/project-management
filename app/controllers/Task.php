<?php
// app/controllers/Task.php

class Task extends Controller {
    public function index() {
        if(!isset($_SESSION['user'])) {
            Flasher::setFlash('Sesi', 'anda telah berakhir, silakan login kembali', 'danger');
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Tugas - Yayasan Assunnah';
        $data['judul_halaman'] = 'Papan Tugas';
        $data['sub_judul'] = 'Pantau perkembangan tugas tim Anda secara real-time.';

        // Project and Team for Dropdowns - Fetch early for logic
        $data['projects'] = $this->model('Project_model')->getProjectsByRole($userRole, $userId);
        $data['team'] = $this->model('Team_model')->getAllTeam();
        $data['user_role'] = $userRole;

        // Filtering parameters
        $filters = [
            'project_id' => $_GET['project_id'] ?? null,
            'search'     => $_GET['search'] ?? null
        ];

        // Logic: Empty if multiple projects exist but none selected
        $shouldFetchTasks = true;
        if (empty($filters['project_id'])) {
            if (count($data['projects']) > 1) {
                $shouldFetchTasks = false;
            } elseif (count($data['projects']) == 1) {
                $filters['project_id'] = $data['projects'][0]['id'];
            }
        }

        $allTasks = $shouldFetchTasks ? $this->model('Task_model')->getFilteredTasks($userRole, $userId, $filters) : [];
        
        $data['is_empty_state'] = !$shouldFetchTasks;
        $data['active_project_id'] = $filters['project_id'];
        
        // Group tasks by status for Kanban
        $data['tasks'] = [
            'todo' => [],
            'in_progress' => [],
            'done' => []
        ];

        foreach($allTasks as $task) {
            if($task['status'] == 'todo') $data['tasks']['todo'][] = $task;
            elseif($task['status'] == 'in_progress') $data['tasks']['in_progress'][] = $task;
            elseif($task['status'] == 'done') $data['tasks']['done'][] = $task;
        }

        // Fetch Deadline Notifications
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('task/index', $data);
        $this->view('templates/footer', $data);
    }

    public function delete($id) {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $task = $this->model('Task_model')->getTaskById($id);
        if($this->model('Task_model')->deleteTask($id) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menghapus tugas',
                'target' => $task['title'],
                'status_from' => null,
                'status_to' => null,
                'info' => 'Tugas "' . $task['title'] . '" telah dihapus.',
                'icon' => '🗑️',
                'icon_bg' => 'bg-red-50 text-red-600'
            ]);
            Flasher::setFlash('berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
        }
        $redirectUrl = BASEURL . '/task';
        if (!empty($_GET['project_id'])) {
            $redirectUrl .= '?project_id=' . $_GET['project_id'];
        }
        
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function add() {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        // Automatic Status Transition based on Progress
        $progress = (int)$_POST['progress'];
        if ($progress == 100) {
            $_POST['status'] = 'done';
        } elseif ($progress > 0) {
            $_POST['status'] = 'in_progress';
        } else {
            $_POST['status'] = 'todo';
        }

        // Enforce assignee for members
        if ($_SESSION['user']['role'] !== 'admin') {
            $_POST['assignee_id'] = $_SESSION['user']['id'];
        }

        if ($this->model('Task_model')->addTask($_POST) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menambah tugas',
                'target' => $_POST['title'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Tugas baru "' . $_POST['title'] . '" berhasil dibuat.',
                'icon' => '📌',
                'icon_bg' => 'bg-blue-50 text-blue-600'
            ]);
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('gagal', 'ditambahkan', 'danger');
        }
        
        $redirectUrl = BASEURL . '/task';
        if (!empty($_POST['project_id'])) {
            $redirectUrl .= '?project_id=' . $_POST['project_id'];
        }
        
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function edit() {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        // Automatic Status Transition based on Progress
        $progress = (int)$_POST['progress'];
        if ($progress == 100) {
            $_POST['status'] = 'done';
        } elseif ($progress > 0) {
            $_POST['status'] = 'in_progress';
        } else {
            $_POST['status'] = 'todo';
        }

        // Enforce assignee for members
        if ($_SESSION['user']['role'] !== 'admin') {
            $_POST['assignee_id'] = $_SESSION['user']['id'];
        }

        if ($this->model('Task_model')->updateTask($_POST) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'memperbarui tugas',
                'target' => $_POST['title'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Status atau detail tugas "' . $_POST['title'] . '" telah diperbarui.',
                'icon' => '🔄',
                'icon_bg' => 'bg-green-50 text-green-600'
            ]);
            Flasher::setFlash('berhasil', 'diperbarui', 'success');
        } else {
            Flasher::setFlash('gagal', 'diperbarui', 'danger');
        }

        $redirectUrl = BASEURL . '/task';
        if (!empty($_POST['project_id'])) {
            $redirectUrl .= '?project_id=' . $_POST['project_id'];
        }

        header('Location: ' . $redirectUrl);
        exit;
    }

    public function getEdit($id) {
        echo json_encode($this->model('Task_model')->getTaskById($id));
    }
}
