<?php
// public/index.php

if (session_status() === PHP_SESSION_NONE) {
    // 1. Isolasi: Hindari bentrok sesi dengan aplikasi lain di localhost
    session_name('PM_APP_SESSION');

    // 2. Keamanan: Gunakan strict mode dan isolasi cookie
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.use_trans_sid', 0);

    // 3. Penyimpanan: Gunakan folder privat agar tidak dihapus sistem hosting secara prematur
    $sessionPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'sessions';
    if (!is_dir($sessionPath)) {
        @mkdir($sessionPath, 0777, true);
    }
    if (is_writable($sessionPath)) {
        session_save_path($sessionPath);
    }

    // 4. Durasi: Paksa durasi 12 Jam (43200 detik)
    ini_set('session.gc_maxlifetime', 43200);
    ini_set('session.cookie_lifetime', 43200);
    ini_set('session.gc_probability', 1);
    ini_set('session.gc_divisor', 100);

    // 5. Konfigurasi Cookie yang Sesuai
    session_set_cookie_params([
        'lifetime' => 43200,
        'path' => '/',
        'domain' => '', 
        'secure' => false, // Set true jika menggunakan HTTPS
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}

// 6. Manual Expiration Guard (Activity Heartbeat)
// Paksa logout hanya jika benar-benar tidak aktif selama lebih dari 12 jam
if (isset($_SESSION['LAST_ACCESS']) && (time() - $_SESSION['LAST_ACCESS'] > 43200)) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACCESS'] = time();

require_once '../config/config.php';
require_once '../app/core/App.php';
require_once '../app/core/Controller.php';
require_once '../app/core/Database.php';
require_once '../app/helpers/Flasher.php';

$app = new App();
