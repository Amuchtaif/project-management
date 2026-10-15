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

    public function dismiss($id = null) {
        if (!isset($_SESSION['user'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        header('Content-Type: application/json');

        if (!$id) {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? ($_POST['id'] ?? null);
        }

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID notifikasi diperlukan']);
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $this->model('Notification_model')->markAsRead((int)$id, $userId);

        echo json_encode(['success' => true, 'message' => 'Notifikasi berhasil ditandai dibaca']);
        exit;
    }
}
