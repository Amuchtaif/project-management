<?php
// app/core/Controller.php

class Controller {
    /**
     * Helper to check if user is logged in
     * If not logged in, redirect to login page without flash message
     */
    public function auth() {
        if (!isset($_SESSION['user'])) {
            // Only show flash message if user specifically tried to access a page/URL (not on first home page visit)
            if (isset($_GET['url']) && !empty($_GET['url'])) {
                Flasher::setFlash('Akses', 'Ditolak, silakan login dahulu.', 'danger');
            }
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        // Heartbeat: Update database activity every 5 minutes internally
        if (!isset($_SESSION['DB_LAST_UPDATE']) || (time() - $_SESSION['DB_LAST_UPDATE'] > 300)) {
            $this->model('User_model')->updateLastActive($_SESSION['user']['id']);
            $_SESSION['DB_LAST_UPDATE'] = time();
        }
    }

    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once '../app/views/' . $view . '.php';
        } else {
            die("View $view not found.");
        }
    }

    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }
}
