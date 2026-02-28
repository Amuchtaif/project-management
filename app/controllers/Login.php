<?php
// app/controllers/Login.php

class Login extends Controller {
    public function index() {
        if(isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
        $data['judul'] = 'Login - Yayasan Assunnah';
        $this->view('login/index', $data);
    }

    public function process() {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $db = new Database();
        $db->query('SELECT * FROM users WHERE email = :email');
        $db->bind(':email', $email);
        $user = $db->single();

        if ($user) {
            // Cek apakah akun aktif
            if ($user['status'] === 'NONAKTIF') {
                Flasher::setFlash('Gagal', 'login, akun Anda dinonaktifkan. Hubungi administrator.', 'danger');
                header('Location: ' . BASEURL . '/login');
                exit;
            }

            if (password_verify($password, $user['password'])) {
                $_SESSION['user'] = [
                    'id'    => $user['id'],
                    'name'  => $user['name'],
                    'role'  => $user['role'],
                    'email' => $user['email']
                ];

                // Update last_active timestamp
                $db->query('UPDATE users SET last_active = NOW() WHERE id = :id');
                $db->bind(':id', $user['id']);
                $db->execute();

                // Log Login Activity
                $this->model('Activity_model')->addActivity([
                    'user_id' => $user['id'],
                    'action' => 'login',
                    'target' => 'Sistem',
                    'status_from' => null,
                    'status_to' => 'aktif',
                    'info' => 'Pengguna "' . $user['name'] . '" telah masuk ke sistem.',
                    'icon' => '🔑',
                    'icon_bg' => 'bg-emerald-50 text-emerald-600'
                ]);

                Flasher::setFlash('Berhasil', 'masuk', 'success');
                header('Location: ' . BASEURL . '/dashboard');
                exit;
            }
        }
        
        Flasher::setFlash('Gagal', 'login, cek email atau password kembali', 'danger');
        header('Location: ' . BASEURL . '/login');
        exit;
    }

    public function logout() {
        if (isset($_SESSION['user'])) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'logout',
                'target' => 'Sistem',
                'status_from' => 'aktif',
                'status_to' => 'keluar',
                'info' => 'Pengguna "' . $_SESSION['user']['name'] . '" telah keluar dari sistem.',
                'icon' => '🚪',
                'icon_bg' => 'bg-rose-50 text-rose-600'
            ]);
        }
        Flasher::setFlash('Berhasil', 'keluar dari sistem', 'success');
        unset($_SESSION['user']);
        header('Location: ' . BASEURL . '/login');
        exit;
    }
}
