<?php
// app/controllers/Danger.php

class Danger extends Controller {
    public function __construct() {
        $this->auth();

        // Verifikasi ketat: HANYA Administrator Utama dengan ID User 1
        if (!isset($_SESSION['user']) || (int)$_SESSION['user']['id'] !== 1) {
            Flasher::setFlash('Akses Ditolak', 'Halaman Danger Area hanya dapat diakses oleh Administrator Utama (ID: 1).', 'danger');
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
    }

    public function index() {
        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Danger Area - Yayasan Assunnah';
        $data['judul_halaman'] = 'Danger Area (Zona Bahaya)';
        $data['sub_judul'] = 'Pusat kendali darurat dan reset data sistem.';
        
        $data['counts'] = $this->model('Danger_model')->getDataCounts();
        $data['backup_stats'] = $this->model('Danger_model')->getBackupStats();
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('danger/index', $data);
        $this->view('templates/footer', $data);
    }

    public function backup() {
        try {
            $sql = $this->model('Danger_model')->generateDatabaseBackup();
            
            // Catat log aktivitas unduh backup
            $this->model('Activity_model')->addActivity([
                'user_id' => 1,
                'action' => 'unduh backup database',
                'target' => 'Database Sistem',
                'status_from' => null,
                'status_to' => 'backup .sql',
                'info' => 'Administrator Utama mengunduh cadangan database sistem (.sql).',
                'icon' => '💾',
                'icon_bg' => 'bg-emerald-100 text-emerald-600'
            ]);

            $filename = 'backup_project_mgmt_' . date('Y-m-d_H-i-s') . '.sql';

            // Bersihkan output buffer sebelum kirim file
            if (ob_get_level()) {
                ob_end_clean();
            }

            header('Content-Type: application/sql; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . strlen($sql));
            header('Pragma: no-cache');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');

            echo $sql;
            exit;
        } catch (Exception $e) {
            Flasher::setFlash('Gagal', 'Mengunduh cadangan database: ' . $e->getMessage(), 'danger');
            header('Location: ' . BASEURL . '/danger');
            exit;
        }
    }

    public function reset() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/danger');
            exit;
        }

        $confirmText = trim($_POST['confirm_text'] ?? '');
        $selectedOptions = $_POST['reset_options'] ?? [];

        // Validasi teks konfirmasi keamanan
        if (strtoupper($confirmText) !== 'RESET DATA') {
            Flasher::setFlash('Gagal', 'Teks konfirmasi tidak sesuai. Harap ketik "RESET DATA" dengan benar.', 'danger');
            header('Location: ' . BASEURL . '/danger');
            exit;
        }

        // Validasi opsi yang dipilih
        if (empty($selectedOptions) || !is_array($selectedOptions)) {
            Flasher::setFlash('Gagal', 'Pilih minimal satu opsi data yang ingin direset.', 'danger');
            header('Location: ' . BASEURL . '/danger');
            exit;
        }

        // Eksekusi reset data
        $result = $this->model('Danger_model')->executeReset($selectedOptions);

        if ($result['success']) {
            // Catat log aktivitas reset oleh Admin Utama (id: 1)
            $logSummary = !empty($result['details']) ? implode('; ', $result['details']) : 'Reset data berhasil';
            $this->model('Activity_model')->addActivity([
                'user_id' => 1,
                'action' => 'reset data sistem',
                'target' => 'Database Utama',
                'status_from' => null,
                'status_to' => 'danger area',
                'info' => 'Admin Utama melakukan reset: ' . $logSummary,
                'icon' => '⚠️',
                'icon_bg' => 'bg-rose-100 text-rose-600'
            ]);

            Flasher::setFlash('Berhasil', 'Pembersihan data terpilih selesai dilakukan.', 'success');
        } else {
            Flasher::setFlash('Gagal', $result['message'], 'danger');
        }

        header('Location: ' . BASEURL . '/danger');
        exit;
    }

    public function restore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASEURL . '/danger');
            exit;
        }

        $confirmText = trim($_POST['confirm_restore'] ?? '');
        if (strtoupper($confirmText) !== 'RESTORE DATABASE') {
            Flasher::setFlash('Gagal', 'Teks konfirmasi restore tidak sesuai. Ketik "RESTORE DATABASE".', 'danger');
            header('Location: ' . BASEURL . '/danger');
            exit;
        }

        if (!isset($_FILES['sql_file']) || $_FILES['sql_file']['error'] !== UPLOAD_ERR_OK) {
            Flasher::setFlash('Gagal', 'Silakan pilih berkas backup .sql yang valid.', 'danger');
            header('Location: ' . BASEURL . '/danger');
            exit;
        }

        $fileTmp = $_FILES['sql_file']['tmp_name'];
        $fileName = $_FILES['sql_file']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($fileExt !== 'sql') {
            Flasher::setFlash('Gagal', 'Format berkas harus berekstensi .sql', 'danger');
            header('Location: ' . BASEURL . '/danger');
            exit;
        }

        $result = $this->model('Danger_model')->restoreDatabase($fileTmp);
        if ($result['success']) {
            $this->model('Activity_model')->addActivity([
                'user_id' => 1,
                'action' => 'restore database',
                'target' => 'Database Utama',
                'status_from' => null,
                'status_to' => 'pulih',
                'info' => 'Administrator Utama memulihkan database dari berkas "' . htmlspecialchars($fileName) . '".',
                'icon' => '📥',
                'icon_bg' => 'bg-emerald-100 text-emerald-600'
            ]);
            Flasher::setFlash('Berhasil', 'Database berhasil dipulihkan dari berkas ' . htmlspecialchars($fileName), 'success');
        } else {
            Flasher::setFlash('Gagal', $result['message'], 'danger');
        }

        header('Location: ' . BASEURL . '/danger');
        exit;
    }
}
