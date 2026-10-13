<?php
// app/controllers/Task.php
class Task extends Controller {
    public function index() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Tugas - Yayasan Assunnah';
        $data['judul_halaman'] = 'Papan Tugas';
        $data['sub_judul'] = 'Pantau perkembangan tim.';

        // Project and Team for Dropdowns - Fetch early for logic
        $data['projects'] = $this->model('Project_model')->getProjectsByRole($userRole, $userId);
        $data['team'] = $this->model('Team_model')->getAllTeam();
        $data['user_role'] = $userRole;

        // Filtering parameters
        $filters = [
            'project_id'  => $_GET['project_id'] ?? null,
            'search'      => $_GET['search'] ?? null,
            'filter_type' => $_GET['filter_type'] ?? null
        ];

        // Logic: Empty if multiple projects exist but none selected
        $shouldFetchTasks = true;
        if (empty($filters['project_id'])) {
            if (count($data['projects']) > 1) {
                $shouldFetchTasks = false;
            } elseif (count($data['projects']) == 1) {
                $filters['project_id'] = $data['projects'][0]['id'];
            }
        }

        $allTasks = $shouldFetchTasks ? $this->model('Task_model')->getFilteredTasks($userRole, $userId, $filters) : [];
        
        $data['is_empty_state'] = !$shouldFetchTasks;
        $data['active_project_id'] = $filters['project_id'];
        $data['active_filter_type'] = $filters['filter_type'];
        $data['overdue_count'] = $this->model('Task_model')->getOverdueCount($userRole, $userId, $filters['project_id']);
        
        // Group tasks by status for Kanban
        $data['tasks'] = [
            'todo' => [],
            'in_progress' => [],
            'done' => []
        ];

        foreach($allTasks as $task) {
            if($task['status'] == 'todo') $data['tasks']['todo'][] = $task;
            elseif($task['status'] == 'in_progress') $data['tasks']['in_progress'][] = $task;
            elseif($task['status'] == 'done') $data['tasks']['done'][] = $task;
        }

        // Fetch Deadline Notifications
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('task/index', $data);
        $this->view('templates/footer', $data);
    }

    public function calendar() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Kalender Tugas - Yayasan Assunnah';
        $data['judul_halaman'] = 'Kalender Tugas';
        $data['sub_judul'] = 'Visualisasikan jadwal, batas waktu, dan progres tugas tim dalam kalender.';

        // Project and Team for Dropdowns
        $data['projects'] = $this->model('Project_model')->getProjectsByRole($userRole, $userId);
        $data['team'] = $this->model('Team_model')->getAllTeam();
        $data['user_role'] = $userRole;

        // Month & Year (defaults to current date)
        $month = isset($_GET['month']) ? (int)$_GET['month'] : (int)date('n');
        $year = isset($_GET['year']) ? (int)$_GET['year'] : (int)date('Y');

        if ($month < 1 || $month > 12) {
            $month = (int)date('n');
        }
        if ($year < 2000 || $year > 2099) {
            $year = (int)date('Y');
        }

        $data['calendar_month'] = $month;
        $data['calendar_year'] = $year;

        // Filtering parameters
        $filters = [
            'project_id' => $_GET['project_id'] ?? null,
            'search'     => $_GET['search'] ?? null
        ];

        $data['active_project_id'] = $filters['project_id'];
        $data['search'] = $filters['search'];
        $data['selected_status'] = $_GET['status'] ?? 'all';

        // In calendar view, fetch tasks. If no project selected, show all accessible tasks
        $allTasks = $this->model('Task_model')->getFilteredTasks($userRole, $userId, $filters);

        // Filter by status if specified
        if (!empty($data['selected_status']) && $data['selected_status'] !== 'all') {
            $allTasks = array_values(array_filter($allTasks, function($t) use ($data) {
                return $t['status'] === $data['selected_status'];
            }));
        }

        $data['tasks'] = $allTasks;

        // Group tasks by due_date (Y-m-d)
        $tasksByDate = [];
        $monthStats = [
            'total' => 0,
            'todo' => 0,
            'in_progress' => 0,
            'done' => 0
        ];

        $currentMonthPrefix = sprintf('%04d-%02d', $year, $month);

        foreach ($allTasks as $task) {
            if (!empty($task['due_date'])) {
                $dueDate = date('Y-m-d', strtotime($task['due_date']));
                $tasksByDate[$dueDate][] = $task;

                if (strpos($dueDate, $currentMonthPrefix) === 0) {
                    $monthStats['total']++;
                    if ($task['status'] === 'todo') $monthStats['todo']++;
                    elseif ($task['status'] === 'in_progress') $monthStats['in_progress']++;
                    elseif ($task['status'] === 'done') $monthStats['done']++;
                }
            }
        }

        $data['tasks_by_date'] = $tasksByDate;
        $data['month_stats'] = $monthStats;

        // Upcoming Tasks (tasks that are pending/in_progress, sorted by due_date ASC)
        $todayStr = date('Y-m-d');
        $upcomingTasks = [];
        foreach ($allTasks as $task) {
            if ($task['status'] !== 'done' && !empty($task['due_date'])) {
                $dueDate = date('Y-m-d', strtotime($task['due_date']));
                $daysLeft = (int)round((strtotime($dueDate) - strtotime($todayStr)) / 86400);
                $task['days_left'] = $daysLeft;
                $upcomingTasks[] = $task;
            }
        }
        usort($upcomingTasks, function($a, $b) {
            if ($a['due_date'] == $b['due_date']) {
                $prioRank = ['tinggi' => 1, 'sedang' => 2, 'biasa' => 3];
                $pa = $prioRank[$a['priority'] ?? 'biasa'] ?? 3;
                $pb = $prioRank[$b['priority'] ?? 'biasa'] ?? 3;
                return $pa - $pb;
            }
            return strtotime($a['due_date']) - strtotime($b['due_date']);
        });
        $data['upcoming_tasks'] = $upcomingTasks;

        // Fetch Deadline Notifications
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('task/calendar', $data);
        $this->view('templates/footer', $data);
    }

    public function delete($id) {
        $this->auth();

        $task = $this->model('Task_model')->getTaskById($id);
        if($this->model('Task_model')->deleteTask($id) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menghapus tugas',
                'target' => $task['title'],
                'status_from' => null,
                'status_to' => null,
                'info' => 'Tugas "' . $task['title'] . '" telah dihapus.',
                'icon' => '🗑️',
                'icon_bg' => 'bg-red-50 text-red-600'
            ]);
            Flasher::setFlash('berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
        }

        $redirectUrl = !empty($_GET['return_to']) ? $_GET['return_to'] : BASEURL . '/task';
        if (empty($_GET['return_to']) && !empty($_GET['project_id'])) {
            $redirectUrl .= '?project_id=' . $_GET['project_id'];
        }
        
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function add() {
        $this->auth();

        // Automatic Status Transition based on Progress
        $progress = (int)$_POST['progress'];
        if ($progress == 100) {
            $_POST['status'] = 'done';
        } elseif ($progress > 0) {
            $_POST['status'] = 'in_progress';
        } else {
            $_POST['status'] = 'todo';
        }

        // Enforce assignee for members
        if ($_SESSION['user']['role'] !== 'admin') {
            $_POST['assignee_id'] = $_SESSION['user']['id'];
        }

        if ($this->model('Task_model')->addTask($_POST) > 0) {
            $projectId = $_POST['project_id'] ?? null;
            if ($projectId) {
                $project = $this->model('Project_model')->getProjectById($projectId);
                if ($project && !empty($project['leader_id']) && (int)$project['leader_id'] !== (int)$_SESSION['user']['id']) {
                    $this->model('Notification_model')->addNotification(
                        $project['leader_id'],
                        'new_task',
                        'Task Baru: ' . $_POST['title'],
                        'Task baru "' . $_POST['title'] . '" ditambahkan pada project ' . ($project['name'] ?? 'Project') . '.',
                        BASEURL . '/task?project_id=' . $projectId,
                        $projectId
                    );
                }

                $assigneeId = (int)($_POST['assignee_id'] ?? 0);
                if ($assigneeId && $assigneeId !== (int)$_SESSION['user']['id'] && $assigneeId !== (int)($project['leader_id'] ?? 0)) {
                    $this->model('Notification_model')->addNotification(
                        $assigneeId,
                        'new_task',
                        'Task Baru: ' . $_POST['title'],
                        'Anda ditugaskan pada task "' . $_POST['title'] . '" di project ' . ($project['name'] ?? 'Project') . '.',
                        BASEURL . '/task?project_id=' . $projectId,
                        $projectId
                    );
                }
            }

            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'menambah tugas',
                'target' => $_POST['title'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Tugas baru "' . $_POST['title'] . '" berhasil dibuat.',
                'icon' => '📌',
                'icon_bg' => 'bg-blue-50 text-blue-600'
            ]);
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('gagal', 'ditambahkan', 'danger');
        }
        
        $redirectUrl = !empty($_POST['return_to']) ? $_POST['return_to'] : BASEURL . '/task';
        if (empty($_POST['return_to']) && !empty($_POST['project_id'])) {
            $redirectUrl .= '?project_id=' . $_POST['project_id'];
        }
        
        header('Location: ' . $redirectUrl);
        exit;
    }

    public function edit() {
        $this->auth();

        // Automatic Status Transition based on Progress
        $progress = (int)$_POST['progress'];
        if ($progress == 100) {
            $_POST['status'] = 'done';
        } elseif ($progress > 0) {
            $_POST['status'] = 'in_progress';
        } else {
            $_POST['status'] = 'todo';
        }

        // Enforce assignee for members
        if ($_SESSION['user']['role'] !== 'admin') {
            $_POST['assignee_id'] = $_SESSION['user']['id'];
        }

        if ($this->model('Task_model')->updateTask($_POST) > 0) {
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'memperbarui tugas',
                'target' => $_POST['title'],
                'status_from' => null,
                'status_to' => $_POST['status'],
                'info' => 'Status atau detail tugas "' . $_POST['title'] . '" telah diperbarui.',
                'icon' => '🔄',
                'icon_bg' => 'bg-green-50 text-green-600'
            ]);
            Flasher::setFlash('berhasil', 'diperbarui', 'success');
        } else {
            Flasher::setFlash('gagal', 'diperbarui', 'danger');
        }

        $redirectUrl = !empty($_POST['return_to']) ? $_POST['return_to'] : BASEURL . '/task';
        if (empty($_POST['return_to']) && !empty($_POST['project_id'])) {
            $redirectUrl .= '?project_id=' . $_POST['project_id'];
        }

        header('Location: ' . $redirectUrl);
        exit;
    }

    public function getEdit($id) {
        echo json_encode($this->model('Task_model')->getTaskById($id));
    }

    public function updateStatus() {
        $this->auth();

        header('Content-Type: application/json');

        // Support both JSON body and POST form
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $id = $input['id'] ?? null;
        $newStatus = $input['status'] ?? null;

        if (!$id || !in_array($newStatus, ['todo', 'in_progress', 'done'])) {
            echo json_encode(['success' => false, 'message' => 'Data tidak valid']);
            exit;
        }

        $task = $this->model('Task_model')->getTaskById($id);
        if (!$task) {
            echo json_encode(['success' => false, 'message' => 'Tugas tidak ditemukan']);
            exit;
        }

        // Permission check: if member, can only move tasks assigned to them
        if ($_SESSION['user']['role'] !== 'admin' && $task['assignee_id'] != $_SESSION['user']['id']) {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki hak akses untuk memindahkan tugas ini']);
            exit;
        }

        $oldStatus = $task['status'];
        if ($oldStatus === $newStatus) {
            echo json_encode(['success' => true, 'message' => 'Status tidak berubah', 'progress' => $task['progress']]);
            exit;
        }

        // Auto progress adjustment
        $progress = (int)$task['progress'];
        if ($newStatus === 'done') {
            $progress = 100;
        } elseif ($newStatus === 'todo') {
            $progress = 0;
        } elseif ($newStatus === 'in_progress') {
            if ($progress <= 0 || $progress >= 100) {
                $progress = 50;
            }
        }

        $updated = $this->model('Task_model')->updateTaskStatus($id, $newStatus, $progress);
        if ($updated >= 0) {
            $statusLabels = [
                'todo' => 'To Do',
                'in_progress' => 'In Progress',
                'done' => 'Done'
            ];
            $this->model('Activity_model')->addActivity([
                'user_id' => $_SESSION['user']['id'],
                'action' => 'memindahkan tugas',
                'target' => $task['title'],
                'status_from' => $oldStatus,
                'status_to' => $newStatus,
                'info' => 'Tugas "' . $task['title'] . '" dipindahkan dari ' . ($statusLabels[$oldStatus] ?? $oldStatus) . ' ke ' . ($statusLabels[$newStatus] ?? $newStatus) . '.',
                'icon' => '🔀',
                'icon_bg' => 'bg-indigo-50 text-indigo-600'
            ]);

            $task['status'] = $newStatus;
            $task['progress'] = $progress;

            echo json_encode([
                'success' => true,
                'message' => 'Tugas berhasil dipindahkan ke ' . ($statusLabels[$newStatus] ?? $newStatus),
                'task' => $task,
                'new_status' => $newStatus,
                'new_progress' => $progress
            ]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal memperbarui status tugas']);
            exit;
        }
    }

    // ================= DETAIL, SUBTASKS & COMMENTS =================
    public function getDetail($id) {
        $this->auth();
        header('Content-Type: application/json');

        $task = $this->model('Task_model')->getTaskById($id);
        if (!$task) {
            echo json_encode(['success' => false, 'message' => 'Tugas tidak ditemukan']);
            exit;
        }

        $subtasks = $this->model('Task_model')->getSubtasks($id);
        $comments = $this->model('Task_model')->getComments($id);
        $typingRows = $this->model('Task_model')->getTypingUsers($id, $_SESSION['user']['id']);
        $typingUsers = array_column($typingRows, 'user_name');

        echo json_encode([
            'success' => true,
            'task' => $task,
            'subtasks' => $subtasks,
            'comments' => $comments,
            'typing_users' => $typingUsers
        ]);
        exit;
    }

    public function getCommentsRealtime($id) {
        $this->auth();
        header('Content-Type: application/json');

        $id = (int)$id;
        $comments = $this->model('Task_model')->getComments($id);
        $typingRows = $this->model('Task_model')->getTypingUsers($id, $_SESSION['user']['id']);
        $typingUsers = array_column($typingRows, 'user_name');

        echo json_encode([
            'success' => true,
            'comments' => $comments,
            'typing_users' => $typingUsers
        ]);
        exit;
    }

    public function setTyping() {
        $this->auth();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $taskId = (int)($input['task_id'] ?? 0);
        $isTyping = !empty($input['is_typing']);
        $userId = $_SESSION['user']['id'];
        $userName = $_SESSION['user']['name'];

        if ($taskId > 0) {
            if ($isTyping) {
                $this->model('Task_model')->updateTyping($taskId, $userId, $userName);
            } else {
                $this->model('Task_model')->clearTyping($taskId, $userId);
            }
        }

        echo json_encode(['success' => true]);
        exit;
    }

    public function addSubtask() {
        $this->auth();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $taskId = (int)($input['task_id'] ?? 0);
        $title = trim($input['title'] ?? '');

        if (!$taskId || empty($title)) {
            echo json_encode(['success' => false, 'message' => 'Judul sub-tugas tidak boleh kosong']);
            exit;
        }

        $res = $this->model('Task_model')->addSubtask($taskId, $title);
        $subtasks = $this->model('Task_model')->getSubtasks($taskId);

        if ($res > 0) {
            $userId = $_SESSION['user']['id'];
            $userName = $_SESSION['user']['name'] ?? 'Seseorang';
            $task = $this->model('Task_model')->getTaskById($taskId);
            if ($task) {
                $projectId = $task['project_id'];
                $project = $this->model('Project_model')->getProjectById($projectId);
                $leaderId = $project['leader_id'] ?? null;
                $assigneeId = $task['assignee_id'] ?? null;

                $recipients = [];
                if (!empty($leaderId) && (int)$leaderId !== (int)$userId) {
                    $recipients[] = (int)$leaderId;
                }
                if (!empty($assigneeId) && (int)$assigneeId !== (int)$userId && !in_array((int)$assigneeId, $recipients)) {
                    $recipients[] = (int)$assigneeId;
                }

                $titleNotif = 'Sub-Tugas: ' . $task['title'];
                $msgNotif = $userName . ' menambah sub-tugas: "' . $title . '"';
                $linkNotif = BASEURL . '/task?project_id=' . $projectId . '&task_id=' . $taskId;

                foreach ($recipients as $recipientId) {
                    $this->model('Notification_model')->addNotification(
                        $recipientId,
                        'new_subtask',
                        $titleNotif,
                        $msgNotif,
                        $linkNotif,
                        $projectId,
                        $taskId
                    );
                }
            }
        }

        echo json_encode([
            'success' => $res > 0,
            'subtasks' => $subtasks
        ]);
        exit;
    }

    public function toggleSubtask($id) {
        $this->auth();
        header('Content-Type: application/json');

        $res = $this->model('Task_model')->toggleSubtask($id);
        echo json_encode(['success' => $res > 0]);
        exit;
    }

    public function deleteSubtask($id) {
        $this->auth();
        header('Content-Type: application/json');

        $res = $this->model('Task_model')->deleteSubtask($id);
        echo json_encode(['success' => $res > 0]);
        exit;
    }

    public function addComment() {
        $this->auth();
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $taskId = (int)($input['task_id'] ?? 0);
        $comment = trim($input['comment'] ?? '');

        if (!$taskId || empty($comment)) {
            echo json_encode(['success' => false, 'message' => 'Komentar tidak boleh kosong']);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $res = $this->model('Task_model')->addComment($taskId, $userId, $comment);
        $comments = $this->model('Task_model')->getComments($taskId);

        if ($res > 0) {
            $userName = $_SESSION['user']['name'] ?? 'Seseorang';
            $task = $this->model('Task_model')->getTaskById($taskId);
            if ($task) {
                $projectId = $task['project_id'];
                $project = $this->model('Project_model')->getProjectById($projectId);
                $leaderId = $project['leader_id'] ?? null;
                $assigneeId = $task['assignee_id'] ?? null;

                $recipients = [];
                if (!empty($leaderId) && (int)$leaderId !== (int)$userId) {
                    $recipients[] = (int)$leaderId;
                }
                if (!empty($assigneeId) && (int)$assigneeId !== (int)$userId && !in_array((int)$assigneeId, $recipients)) {
                    $recipients[] = (int)$assigneeId;
                }

                $commentSnippet = mb_strlen($comment) > 60 ? mb_substr($comment, 0, 57) . '...' : $comment;
                $titleNotif = 'Komentar: ' . $task['title'];
                $msgNotif = $userName . ': "' . $commentSnippet . '"';
                $linkNotif = BASEURL . '/task?project_id=' . $projectId . '&task_id=' . $taskId;

                foreach ($recipients as $recipientId) {
                    $this->model('Notification_model')->addNotification(
                        $recipientId,
                        'new_comment',
                        $titleNotif,
                        $msgNotif,
                        $linkNotif,
                        $projectId,
                        $taskId
                    );
                }
            }
        }

        echo json_encode([
            'success' => $res > 0,
            'comments' => $comments
        ]);
        exit;
    }

    public function clearNotifications() {
        $this->auth();
        header('Content-Type: application/json');

        $userId = $_SESSION['user']['id'];
        $this->model('Notification_model')->markAllAsRead($userId);

        echo json_encode(['success' => true, 'message' => 'Semua notifikasi berhasil dibersihkan']);
        exit;
    }

    // ================= EXPORT CSV =================
    public function exportCsv() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];
        $projectId = $_GET['project_id'] ?? null;

        $filters = ['project_id' => $projectId];
        $tasks = $this->model('Task_model')->getFilteredTasks($userRole, $userId, $filters);

        $filename = 'laporan_tugas_' . date('Y-m-d_H-i') . '.csv';

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM for Excel
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // CSV Header
        fputcsv($output, ['ID', 'Judul Tugas', 'Proyek', 'Penanggung Jawab', 'Tag / Label', 'Prioritas', 'Status', 'Progres (%)', 'Tenggat Waktu']);

        foreach ($tasks as $t) {
            fputcsv($output, [
                $t['id'],
                $t['title'],
                $t['project_name'],
                $t['assignee_name'],
                $t['tag'],
                ucfirst($t['priority'] ?? 'biasa'),
                ucfirst($t['status']),
                $t['progress'] . '%',
                $t['due_date']
            ]);
        }

        fclose($output);
        exit;
    }

    // ================= TIMELINE / ROADMAP (GANTT) =================
    public function timeline() {
        $this->auth();

        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'];

        $data['judul'] = 'Roadmap & Timeline - Yayasan Assunnah';
        $data['judul_halaman'] = 'Roadmap & Timeline Tugas';
        $data['sub_judul'] = 'Pantau jadwal pengerjaan proyek dan tugas dalam visualisasi garis waktu (Gantt Chart).';

        $data['projects'] = $this->model('Project_model')->getProjectsByRole($userRole, $userId);
        $data['team'] = $this->model('Team_model')->getAllTeam();
        $data['user_role'] = $userRole;

        $projectId = $_GET['project_id'] ?? null;
        $filters = ['project_id' => $projectId];
        $data['active_project_id'] = $projectId;

        $data['tasks'] = $this->model('Task_model')->getFilteredTasks($userRole, $userId, $filters);
        $data['notifications'] = $this->model('Task_model')->getDeadlineNotifications($userRole, $userId);

        $this->view('templates/header', $data);
        $this->view('templates/sidebar', $data);
        $this->view('templates/topbar', $data);
        $this->view('task/timeline', $data);
        $this->view('templates/footer', $data);
    }
}

