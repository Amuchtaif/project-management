<?php
// app/controllers/Team.php

class Team extends Controller {
    public function index() {
        $this->auth();

        // Hanya admin yang bisa mengakses halaman manajemen tim
        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses ke halaman ini', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Tim - Yayasan Assunnah';
        $data['judul_halaman'] = 'Manajemen Tim';
        $data['sub_judul'] = 'Kelola anggota tim dan hak akses mereka dalam sistem.';

        $data['team'] = $this->model('Team_model')->getAllTeam();

        // Fetch Deadline Notifications
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($_SESSION['user']['role'], $_SESSION['user']['id']);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('team/index', $data);
        $this->view('templates/footer', $data);
    }

    public function delete($id) {
        $this->auth();

        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses ke halaman ini', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        // Jangan izinkan admin menghapus dirinya sendiri
        if($id == $_SESSION['user']['id']) {
            Flasher::setFlash('gagal', 'Anda tidak bisa menghapus akun sendiri', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        $member = $this->model('Team_model')->getMemberById($id);
        if($this->model('Team_model')->deleteMember($id) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menghapus anggota tim',
                'target' => $member['name'],
                'status_from' => null,
                'status_to' => null,
                'info' => 'Anggota tim ' . $member['name'] . ' telah dihapus dari sistem.',
                'icon' => '👤',
                'icon_bg' => 'bg-red-50 text-red-600'
            ]);
            Flasher::setFlash('berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/team');
        exit;
    }

    public function add() {
        $this->auth();

        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses ke halaman ini', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        if ($this->model('Team_model')->addMember($_POST) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menambah anggota tim',
                'target' => $_POST['name'],
                'status_from' => null,
                'status_to' => $_POST['role'],
                'info' => 'Anggota tim baru ' . $_POST['name'] . ' telah didaftarkan sebagai ' . $_POST['role'] . '.',
                'icon' => '➕',
                'icon_bg' => 'bg-indigo-50 text-indigo-600'
            ]);
            Flasher::setFlash('berhasil', 'ditambahkan. Password default: [namadepan]123', 'success');
        } else {
            Flasher::setFlash('gagal', 'ditambahkan. Email mungkin sudah terdaftar.', 'danger');
        }
        header('Location: ' . BASEURL . '/team');
        exit;
    }

    public function edit() {
        $this->auth();

        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses ke halaman ini', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        if ($this->model('Team_model')->updateMember($_POST) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'mengubah detail anggota',
                'target' => $_POST['name'],
                'status_from' => null,
                'status_to' => $_POST['role'] ?? null,
                'info' => 'Informasi profil untuk ' . $_POST['name'] . ' telah diperbarui.',
                'icon' => '👤',
                'icon_bg' => 'bg-blue-50 text-blue-600'
            ]);
            Flasher::setFlash('berhasil', 'diperbarui', 'success');
        } else {
            Flasher::setFlash('gagal', 'diperbarui', 'danger');
        }
        header('Location: ' . BASEURL . '/team');
        exit;
    }

    public function getEdit($id) {
        echo json_encode($this->model('Team_model')->getMemberById($id));
    }
}
