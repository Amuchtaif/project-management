<?php
// app/controllers/Activity.php

class Activity extends Controller {
    public function index() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Log Aktivitas - Yayasan Assunnah';
        $data['judul_halaman'] = 'Log Aktivitas';
        $data['sub_judul'] = 'Pantau jejak audit, riwayat perubahan, dan aktivitas tim dalam sistem.';

        // Filtering parameters
        $filters = [
            'user_id' => ($userRole === 'admin') ? ($_GET['user_id'] ?? null) : $userId,
            'date'    => $_GET['date'] ?? null,
            'type'    => $_GET['type'] ?? null
        ];

        $allActivities = $this->model('Activity_model')->getFilteredActivities($filters);
        
        // Calculate statistics for Metric Strip
        $todayDate = date('Y-m-d');
        $totalCount = count($allActivities);
        $todayCount = 0;
        $createCount = 0;
        $updateCount = 0;

        foreach ($allActivities as $act) {
            $actDate = substr($act['created_at'], 0, 10);
            if ($actDate === $todayDate) $todayCount++;
            
            $actAction = strtolower($act['action']);
            if (strpos($actAction, 'menambah') !== false || strpos($actAction, 'membuat') !== false) {
                $createCount++;
            } elseif (strpos($actAction, 'ubah') !== false || strpos($actAction, 'perbarui') !== false || strpos($actAction, 'edit') !== false) {
                $updateCount++;
            }
        }

        $data['stats'] = [
            'total'   => $totalCount,
            'today'   => $todayCount,
            'create'  => $createCount,
            'update'  => $updateCount
        ];

        // Group activities by date
        $data['activities'] = [];
        foreach($allActivities as $activity) {
            $date = date('d F Y', strtotime($activity['created_at']));
            $today = date('d F Y');
            $yesterday = date('d F Y', strtotime("-1 day"));

            if($date == $today) $label = 'Hari Ini';
            elseif($date == $yesterday) $label = 'Kemarin';
            else $label = $date;

            $data['activities'][$label][] = [
                'user' => $activity['user_name'],
                'avatar' => $activity['user_name'],
                'action' => $activity['action'],
                'target' => $activity['target'],
                'from' => $activity['status_from'],
                'to' => $activity['status_to'],
                'info' => $activity['info'],
                'time' => date('H:i', strtotime($activity['created_at'])),
                'created_at' => $activity['created_at'],
                'icon' => $activity['icon'],
                'icon_bg' => $activity['icon_bg']
            ];
        }

        // For filters
        $data['user_role'] = $userRole;
        if ($userRole === 'admin') {
            $data['team'] = $this->model('Team_model')->getAllTeam();
        }

        // Fetch Deadline Notifications
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('activity/index', $data);
        $this->view('templates/footer', $data);
    }

    public function exportCsv() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $filters = [
            'user_id' => ($userRole === 'admin') ? ($_GET['user_id'] ?? null) : $userId,
            'date'    => $_GET['date'] ?? null,
            'type'    => $_GET['type'] ?? null
        ];

        $activities = $this->model('Activity_model')->getFilteredActivities($filters);
        $filename = 'log_aktivitas_' . date('Y-m-d_H-i') . '.csv';

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

        fputcsv($output, ['No', 'Waktu', 'Pengguna', 'Aktivitas', 'Target', 'Info Tambahan']);

        $no = 1;
        foreach ($activities as $a) {
            fputcsv($output, [
                $no++,
                $a['created_at'],
                $a['user_name'],
                $a['action'],
                $a['target'],
                $a['info'] ?? '-'
            ]);
        }

        fclose($output);
        exit;
    }
}
