<?php
// app/controllers/Activity.php

class Activity extends Controller {
    public function index() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Log Aktivitas - Yayasan Assunnah';
        $data['judul_halaman'] = 'Log Aktivitas';
        $data['sub_judul'] = 'Lihat semua riwayat perubahan dan interaksi tim dalam sistem.';

        // Filtering parameters
        $filters = [
            'user_id' => ($userRole === 'admin') ? ($_GET['user_id'] ?? null) : $userId,
            'date'    => $_GET['date'] ?? null,
            'type'    => $_GET['type'] ?? null
        ];

        $allActivities = $this->model('Activity_model')->getFilteredActivities($filters);
        
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
                'time' => date('H:i A', strtotime($activity['created_at'])),
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
}
