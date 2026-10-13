<?php

class Notification extends Controller {
    public function clearAll() {
        if (!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        header('Content-Type: application/json');
        $userId = $_SESSION['user']['id'];
        $this->model('Notification_model')->markAllAsRead($userId);

        echo json_encode(['success' => true, 'message' => 'Semua notifikasi berhasil dibersihkan']);
        exit;
    }
}
