<?php
// app/controllers/Settings.php

class Settings extends Controller {
    public function index() {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Pengaturan Profil';
        $data['judul_halaman'] = 'Pengaturan Profil';
        $data['sub_judul'] = 'Kelola informasi pribadi dan keamanan akun Anda.';
        
        $data['user'] = $this->model('Team_model')->getMemberById($userId);
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('settings/index', $data);
        $this->view('templates/footer', $data);
    }

    public function update() {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        if ($this->model('Team_model')->updateProfile($_POST) >= 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'memperbarui profil',
                'target' => 'Akun Pribadi',
                'status_from' => null,
                'status_to' => null,
                'info' => 'Pengguna telah memperbarui informasi profil atau kata sandi.',
                'icon' => '⚙️',
                'icon_bg' => 'bg-gray-100 text-gray-600'
            ]);
            Flasher::setFlash('berhasil', 'profil diperbarui', 'success');
        } else {
            Flasher::setFlash('gagal', 'profil diperbarui', 'danger');
        }
        header('Location: ' . BASEURL . '/settings');
        exit;
    }
}
