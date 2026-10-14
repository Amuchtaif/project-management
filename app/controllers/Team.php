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

        // Jangan izinkan menghapus akun administrator utama
        if($id == 1) {
            Flasher::setFlash('gagal', 'Akun Administrator utama tidak dapat dihapus', 'danger');
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
            Flasher::setFlash('berhasil', 'ditambahkan. Password default: password123', 'success');
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

    public function resetPassword() {
        $this->auth();

        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses ke fitur ini', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $newPassword = trim($_POST['password'] ?? '');

        if (!$id || empty($newPassword)) {
            Flasher::setFlash('gagal', 'Kata sandi baru tidak boleh kosong', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        $member = $this->model('Team_model')->getMemberById($id);
        if (!$member) {
            Flasher::setFlash('gagal', 'Anggota tidak ditemukan', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        if ($this->model('Team_model')->resetPassword($id, $newPassword) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'mereset kata sandi',
                'target' => $member['name'],
                'status_from' => null,
                'status_to' => null,
                'info' => 'Kata sandi untuk ' . $member['name'] . ' telah berhasil direset oleh Administrator.',
                'icon' => '🔑',
                'icon_bg' => 'bg-amber-50 text-amber-600'
            ]);
            Flasher::setFlash('berhasil', 'Kata sandi untuk ' . $member['name'] . ' berhasil direset', 'success');
        } else {
            Flasher::setFlash('gagal', 'Kata sandi tidak berubah atau gagal direset', 'warning');
        }

        header('Location: ' . BASEURL . '/team');
        exit;
    }

    public function changeAccess() {
        $this->auth();

        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        $id = $_POST['id'] ?? null;
        $systemRole = $_POST['system_role'] ?? null;

        if (!$id || !in_array($systemRole, ['admin', 'member'])) {
            Flasher::setFlash('gagal', 'Data hak akses tidak valid', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        if ($id == $_SESSION['user']['id']) {
            Flasher::setFlash('gagal', 'Anda tidak dapat mengubah hak akses akun Anda sendiri', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        $member = $this->model('Team_model')->getMemberById($id);
        if (!$member) {
            Flasher::setFlash('gagal', 'Anggota tidak ditemukan', 'danger');
            header('Location: ' . BASEURL . '/team');
            exit;
        }

        if ($this->model('Team_model')->updateAccessRole($id, $systemRole) >= 0) {
            $roleLabel = $systemRole === 'admin' ? 'Akses Penuh (Admin)' : 'Akses Terbatas (Member)';
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'mengubah hak akses',
                'target' => $member['name'],
                'status_from' => $member['role'],
                'status_to' => $systemRole,
                'info' => 'Hak akses untuk ' . $member['name'] . ' diubah menjadi ' . $roleLabel . '.',
                'icon' => '🛡️',
                'icon_bg' => 'bg-indigo-50 text-indigo-600'
            ]);
            Flasher::setFlash('berhasil', 'Hak akses ' . $member['name'] . ' berhasil diubah menjadi ' . $roleLabel, 'success');
        } else {
            Flasher::setFlash('gagal', 'Hak akses gagal diperbarui', 'danger');
        }

        header('Location: ' . BASEURL . '/team');
        exit;
    }

    public function exportPdf() {
        $this->auth();

        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses ke halaman ini', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Laporan Data Anggota Tim & Hak Akses - Yayasan Assunnah';
        $data['team'] = $this->model('Team_model')->getAllTeam();
        $data['generated_by'] = $_SESSION['user']['name'];
        $data['generated_at'] = date('d M Y, H:i');

        $this->view('team/report_print', $data);
    }

    public function exportCsv() {
        $this->auth();

        if($_SESSION['user']['role'] !== 'admin') {
            Flasher::setFlash('gagal', 'Anda tidak memiliki akses', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $team = $this->model('Team_model')->getAllTeam();
        $filename = 'laporan_tim_' . date('Y-m-d_H-i') . '.csv';

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

        fputcsv($output, ['No', 'Nama Profil', 'Email', 'Jabatan / Profesi', 'Hak Akses Sistem', 'Departemen', 'Status']);

        $no = 1;
        foreach ($team as $m) {
            $hakAkses = ($m['role'] === 'admin') ? 'Akses Penuh (Admin)' : 'Akses Terbatas (Member)';
            fputcsv($output, [
                $no++,
                $m['name'],
                $m['email'],
                $m['role_label'] ?? $m['role'],
                $hakAkses,
                $m['department'] ?? '-',
                $m['status'] ?? 'AKTIF'
            ]);
        }

        fclose($output);
        exit;
    }
}
