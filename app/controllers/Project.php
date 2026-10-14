<?php
// app/controllers/Project.php

class Project extends Controller {
    public function index() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Project - Yayasan Assunnah';
        $data['judul_halaman'] = 'Direktori Project';
        $data['sub_judul'] = 'Kelola dan lacak semua project organisasi secara efisien di satu tempat.';

        // Role-based project view with filtering
        $filters = [
            'status' => $_GET['status'] ?? null,
            'search' => $_GET['search'] ?? null
        ];
        
        $data['projects'] = $this->model('Project_model')->getProjectsByRole($userRole, $userId, $filters);
        $data['team'] = $this->model('Team_model')->getAllTeam();
        $data['user_role'] = $userRole;

        // Fetch Deadline Notifications & Lead Project alerts
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('project/index', $data);
        $this->view('templates/footer', $data);
    }

    public function delete($id) {
        $this->auth();
        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        $project = $this->model('Project_model')->getProjectById($id);
        $projectName = $project['name'] ?? 'Project';
        if($this->model('Project_model')->deleteProject($id) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menghapus project',
                'target' => $projectName,
                'status_from' => null,
                'status_to' => null,
                'info' => 'Project ' . $projectName . ' telah dihapus permanent.',
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
        $this->auth();
        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        $projectId = $this->model('Project_model')->addProject($_POST);
        if ($projectId > 0) {
            // Assign leader if selected
            if (!empty($_POST['leader_id'])) {
                $this->model('Project_model')->assignMember($projectId, $_POST['leader_id'], 'leader');

                // Notifikasi card ke user yg terpilih lead project
                $this->model('Notification_model')->addNotification(
                    $_POST['leader_id'],
                    'new_project',
                    'Project Baru: ' . $_POST['name'],
                    'Anda terpilih sebagai Project Leader untuk project ' . $_POST['name'] . '.',
                    BASEURL . '/task?project_id=' . $projectId,
                    $projectId
                );
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
                'action' => 'menambah project baru',
                'target' => $_POST['name'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Project baru ' . $_POST['name'] . ' berhasil didaftarkan.',
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
        $this->auth();
        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/project');
            exit;
        }

        $oldProject = $this->model('Project_model')->getProjectById($_POST['id']);
        $oldLeaderId = $oldProject['leader_id'] ?? null;

        $result = $this->model('Project_model')->updateProject($_POST);
        
        // Handle leader update
        if (isset($_POST['leader_id'])) {
            $projectId = $_POST['id'];
            $this->model('Project_model')->removeLeader($projectId);
            if (!empty($_POST['leader_id'])) {
                $this->model('Project_model')->assignMember($projectId, $_POST['leader_id'], 'leader');

                // Jika leader baru di-assign atau berubah, kirim notifikasi card
                if ((int)$_POST['leader_id'] !== (int)$oldLeaderId) {
                    $this->model('Notification_model')->addNotification(
                        $_POST['leader_id'],
                        'new_project',
                        'Project Baru: ' . $_POST['name'],
                        'Anda terpilih sebagai Project Leader untuk project ' . $_POST['name'] . '.',
                        BASEURL . '/task?project_id=' . $projectId,
                        $projectId
                    );
                }
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
                'action' => 'mengubah detail project',
                'target' => $_POST['name'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Informasi project ' . $_POST['name'] . ' telah diperbarui.',
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
        $this->auth();
        if($_SESSION['user']['role'] !== 'admin') {
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
        $this->auth();
        if($_SESSION['user']['role'] !== 'admin') {
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

    public function exportCsv() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $projects = $this->model('Project_model')->getProjectsByRole($userRole, $userId);

        $filename = 'laporan_proyek_' . date('Y-m-d_H-i') . '.csv';

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($output, ['ID', 'Nama Proyek', 'Klien', 'Tenggat Waktu', 'Status', 'Progres (%)', 'Project Leader', 'Total Tugas Selesai', 'Total Tugas']);

        foreach ($projects as $p) {
            fputcsv($output, [
                $p['id'],
                $p['name'],
                $p['client'],
                $p['due_date'],
                $p['status'],
                $p['progress'] . '%',
                $p['leader_name'] ?? 'Belum ditentukan',
                $p['completed_tasks'] ?? 0,
                $p['total_tasks'] ?? 0
            ]);
        }

        fclose($output);
        exit;
    }

    public function reportPrint() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Laporan Eksekutif Proyek - Yayasan Assunnah';
        $data['projects'] = $this->model('Project_model')->getProjectsByRole($userRole, $userId);
        $data['generated_by'] = $_SESSION['user']['name'];
        $data['generated_at'] = date('d M Y, H:i');

        $this->view('project/report_print', $data);
    }

    public function exportPdf() {
        $this->reportPrint();
    }
}
