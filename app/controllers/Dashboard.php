<?php
// app/controllers/Dashboard.php

class Dashboard extends Controller {
    public function index() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Dashboard';
        $data['judul_halaman'] = 'Ringkasan Dashboard';
        $data['sub_judul'] = 'Selamat datang kembali, ' . $_SESSION['user']['name'] . '. Berikut adalah perkembangan hari ini.';
        
        // Role-based project fetching
        $projectModel = $this->model('Project_model');
        $taskModel = $this->model('Task_model');

        if ($userRole === 'admin') {
            // Admin melihat semua proyek dan semua tugas
            $projects = $projectModel->getAllProjects();
            $tasks = $taskModel->getAllTasks();
            $memberCount = count($this->model('Team_model')->getAllTeam());
        } else {
            // Member hanya melihat proyek yang ditugaskan kepadanya
            $projects = $projectModel->getProjectsByMemberId($userId);
            $tasks = $taskModel->getTasksByMemberId($userId);
            $memberCount = null; // Member tidak perlu lihat total anggota
        }

        $data['stats'] = [
            'projects' => count($projects),
            'tasks'    => count($tasks),
            'members'  => $memberCount
        ];

        // Fetch Detailed Data for Charts/Progress
        $data['projects_progress'] = $projectModel->getProjectsProgress($userRole, $userId);
        $data['task_distribution'] = $taskModel->getTaskDistribution($userRole, $userId);

        // Fetch Recent Activities
        if ($userRole === 'admin') {
            $data['activities'] = $this->model('Activity_model')->getFilteredActivities();
        } else {
            $data['activities'] = $this->model('Activity_model')->getFilteredActivities(['user_id' => $userId]);
        }

        // Pass role info ke view
        $data['user_role'] = $userRole;

        // Fetch Deadline Notifications
        $data['notifications'] = $taskModel->getDeadlineNotifications($userRole, $userId);
        
        // Fetch Near Deadline Tasks for Dashboard Section
        $data['near_deadline_tasks'] = $taskModel->getNearDeadlineTasks($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/footer', $data);
    }

    public function globalSearch() {
        $this->auth();
        header('Content-Type: application/json');

        $query = trim($_GET['q'] ?? '');
        if (strlen($query) < 1) {
            echo json_encode(['results' => []]);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $results = [];
        $db = new Database();

        // 1. Projects
        if ($userRole === 'admin') {
            $db->query("SELECT id, name, client, status FROM projects WHERE name LIKE :q OR client LIKE :q LIMIT 5");
        } else {
            $db->query("SELECT p.id, p.name, p.client, p.status FROM projects p 
                        JOIN project_members pm ON p.id = pm.project_id 
                        WHERE pm.user_id = :uid AND (p.name LIKE :q OR p.client LIKE :q) LIMIT 5");
            $db->bind('uid', $userId);
        }
        $db->bind('q', "%$query%");
        $projects = $db->resultSet();
        foreach ($projects as $p) {
            $results[] = [
                'category' => 'Proyek',
                'title' => $p['name'],
                'subtitle' => 'Klien: ' . $p['client'] . ' • Status: ' . $p['status'],
                'url' => BASEURL . '/task?project_id=' . $p['id'],
                'icon' => 'fa-solid fa-folder-open text-amber-500'
            ];
        }

        // 2. Tasks
        if ($userRole === 'admin') {
            $db->query("SELECT t.id, t.title, t.priority, t.status, t.project_id, p.name as project_name 
                        FROM tasks t JOIN projects p ON t.project_id = p.id 
                        WHERE t.title LIKE :q OR t.tag LIKE :q LIMIT 6");
        } else {
            $db->query("SELECT t.id, t.title, t.priority, t.status, t.project_id, p.name as project_name 
                        FROM tasks t JOIN projects p ON t.project_id = p.id 
                        WHERE t.assignee_id = :uid AND (t.title LIKE :q OR t.tag LIKE :q) LIMIT 6");
            $db->bind('uid', $userId);
        }
        $db->bind('q', "%$query%");
        $tasks = $db->resultSet();
        foreach ($tasks as $t) {
            $results[] = [
                'category' => 'Tugas',
                'title' => $t['title'],
                'subtitle' => $t['project_name'] . ' • ' . ucfirst($t['status']) . ' (' . ucfirst($t['priority']) . ')',
                'url' => BASEURL . '/task?project_id=' . $t['project_id'],
                'icon' => 'fa-solid fa-list-check text-indigo-500'
            ];
        }

        // 3. Team Members
        if ($userRole === 'admin') {
            $db->query("SELECT id, name, role_label, email FROM users WHERE name LIKE :q OR email LIKE :q LIMIT 4");
            $db->bind('q', "%$query%");
            $users = $db->resultSet();
            foreach ($users as $u) {
                $results[] = [
                    'category' => 'Anggota Tim',
                    'title' => $u['name'],
                    'subtitle' => ($u['role_label'] ?? 'Anggota') . ' • ' . $u['email'],
                    'url' => BASEURL . '/team',
                    'icon' => 'fa-solid fa-user text-emerald-500'
                ];
            }
        }

        // 4. Quick Actions / Navigation items
        $navigationItems = [
            ['title' => 'Dashboard Utama', 'subtitle' => 'Ringkasan & Metrik Sistem', 'url' => BASEURL . '/dashboard', 'icon' => 'fa-solid fa-house text-blue-500', 'keyword' => 'dashboard beranda home'],
            ['title' => 'Daftar Proyek', 'subtitle' => 'Kelola seluruh proyek yayasan', 'url' => BASEURL . '/project', 'icon' => 'fa-solid fa-folder text-amber-500', 'keyword' => 'proyek project'],
            ['title' => 'Papan Tugas (Kanban)', 'subtitle' => 'Lihat papan kartu tugas Todo, In Progress, Done', 'url' => BASEURL . '/task', 'icon' => 'fa-solid fa-table-columns text-indigo-500', 'keyword' => 'tugas task kanban papan'],
            ['title' => 'Kalender Tugas', 'subtitle' => 'Jadwal dan batas waktu tugas', 'url' => BASEURL . '/task/calendar', 'icon' => 'fa-solid fa-calendar-days text-purple-500', 'keyword' => 'kalender calendar jadwal'],
            ['title' => 'Roadmap & Timeline (Gantt)', 'subtitle' => 'Tampilan garis waktu proyek', 'url' => BASEURL . '/task/timeline', 'icon' => 'fa-solid fa-chart-gantt text-cyan-500', 'keyword' => 'timeline gantt roadmap'],
            ['title' => 'Log Aktivitas', 'subtitle' => 'Jejak audit dan rekaman aktivitas pengguna', 'url' => BASEURL . '/activity', 'icon' => 'fa-solid fa-clock-rotate-left text-gray-500', 'keyword' => 'log aktivitas activity'],
        ];

        if ($userRole === 'admin') {
            $navigationItems[] = ['title' => 'Manajemen Tim', 'subtitle' => 'Kelola anggota tim dan hak akses', 'url' => BASEURL . '/team', 'icon' => 'fa-solid fa-users text-teal-500', 'keyword' => 'tim team anggota user'];
        }

        if ((int)$_SESSION['user']['id'] === 1) {
            $navigationItems[] = ['title' => 'Danger Area (Reset & Backup)', 'subtitle' => 'Zona bahaya, cadangan database, dan pemulihan', 'url' => BASEURL . '/danger', 'icon' => 'fa-solid fa-triangle-exclamation text-rose-500', 'keyword' => 'danger reset backup restore bahaya'];
        }

        $qLower = strtolower($query);
        foreach ($navigationItems as $nav) {
            if (strpos(strtolower($nav['title']), $qLower) !== false || strpos($nav['keyword'], $qLower) !== false) {
                $results[] = [
                    'category' => 'Navigasi Pintas',
                    'title' => $nav['title'],
                    'subtitle' => $nav['subtitle'],
                    'url' => $nav['url'],
                    'icon' => $nav['icon']
                ];
            }
        }

        echo json_encode(['results' => $results]);
        exit;
    }
}
