<?php
// app/controllers/Dashboard.php

class Dashboard extends Controller {
    public function index() {
        if(!isset($_SESSION['user'])) {
            Flasher::setFlash('Sesi', 'anda telah berakhir, silakan login kembali', 'danger');
            header('Location: ' . BASEURL . '/login');
            exit;
        }

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

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/footer', $data);
    }
}
