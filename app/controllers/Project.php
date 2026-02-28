<?php
// app/controllers/Project.php

class Project extends Controller {
    public function index() {
        if(!isset($_SESSION['user'])) {
            Flasher::setFlash('Sesi', 'anda telah berakhir, silakan login kembali', 'danger');
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Proyek - Yayasan Assunnah';
        $data['judul_halaman'] = 'Direktori Proyek';
        $data['sub_judul'] = 'Kelola dan lacak semua proyek organisasi secara efisien di satu tempat.';

        // Role-based project view with filtering
        $filters = [
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        $data['projects'] = $this->model('Project_model')->getProjectsByRole($userRole, $userId, $filters);
        $data['team'] = $this->model('Team_model')->getAllTeam();
        $data['user_role'] = $userRole;

        // Fetch Deadline Notifications
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('project/index', $data);
        $this->view('templates/footer', $data);
    }

    public function delete($id) {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        $project = $this->model('Project_model')->getProjectById($id);
        if($this->model('Project_model')->deleteProject($id) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menghapus proyek',
                'target' => $project['name'],
                'status_from' => null,
                'status_to' => null,
                'info' => 'Proyek ' . $project['name'] . ' telah dihapus permanent.',
                'icon' => '🗑️',
                'icon_bg' => 'bg-red-50 text-red-600'
            ]);
            Flasher::setFlash('berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/project');
        exit;
    }

    public function add() {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        $projectId = $this->model('Project_model')->addProject($_POST);
        if ($projectId > 0) {
            // Assign leader if selected
            if (!empty($_POST['leader_id'])) {
                $this->model('Project_model')->assignMember($projectId, $_POST['leader_id'], 'leader');
            }

            // Sync members if selected
            if (!empty($_POST['member_ids'])) {
                $memberIds = json_decode($_POST['member_ids'], true);
                if (is_array($memberIds)) {
                    $this->model('Project_model')->syncMembers($projectId, $memberIds);
                }
            }

            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menambah proyek baru',
                'target' => $_POST['name'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Proyek baru ' . $_POST['name'] . ' berhasil didaftarkan.',
                'icon' => '🚀',
                'icon_bg' => 'bg-indigo-50 text-indigo-600'
            ]);

            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('gagal', 'ditambahkan', 'danger');
        }
        header('Location: ' . BASEURL . '/project');
        exit;
    }

    public function edit() {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        $result = $this->model('Project_model')->updateProject($_POST);
        
        // Handle leader update
        if (isset($_POST['leader_id'])) {
            $projectId = $_POST['id'];
            $this->model('Project_model')->removeLeader($projectId);
            if (!empty($_POST['leader_id'])) {
                $this->model('Project_model')->assignMember($projectId, $_POST['leader_id'], 'leader');
            }
            $result = 1; 
        }

        // Handle members update
        if (isset($_POST['member_ids'])) {
            $projectId = $_POST['id'];
            $memberIds = json_decode($_POST['member_ids'], true);
            $this->model('Project_model')->syncMembers($projectId, $memberIds);
            $result = 1; 
        }

        if ($result > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'mengubah detail proyek',
                'target' => $_POST['name'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Informasi proyek ' . $_POST['name'] . ' telah diperbarui.',
                'icon' => '📝',
                'icon_bg' => 'bg-blue-50 text-blue-600'
            ]);
            Flasher::setFlash('berhasil', 'diperbarui', 'success');
        } else {
            Flasher::setFlash('gagal', 'diperbarui', 'danger');
        }
        header('Location: ' . BASEURL . '/project');
        exit;
    }

    public function getEdit($id) {
        $project = $this->model('Project_model')->getProjectById($id);
        if ($project) {
            $project['member_ids'] = $this->model('Project_model')->getProjectMemberIds($id);
        }
        echo json_encode($project);
    }

    /**
     * Assign member ke proyek (POST: project_id, user_id)
     */
    public function assignMember() {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        $projectId = $_POST['project_id'];
        $userId = $_POST['user_id'];
        
        if ($this->model('Project_model')->assignMember($projectId, $userId) > 0) {
            Flasher::setFlash('berhasil', 'anggota ditugaskan ke proyek', 'success');
        } else {
            Flasher::setFlash('gagal', 'anggota sudah terdaftar di proyek ini', 'danger');
        }
        header('Location: ' . BASEURL . '/project');
        exit;
    }

    /**
     * Remove member dari proyek
     */
    public function removeMember($projectId, $userId) {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        if ($this->model('Project_model')->removeMember($projectId, $userId) > 0) {
            Flasher::setFlash('berhasil', 'anggota dihapus dari proyek', 'success');
        } else {
            Flasher::setFlash('gagal', 'dihapus dari proyek', 'danger');
        }
        header('Location: ' . BASEURL . '/project');
        exit;
    }
}
