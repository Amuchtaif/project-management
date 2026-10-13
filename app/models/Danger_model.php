<?php

class Danger_model {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Mengambil ringkasan statistik data sistem saat ini
     */
    public function getDataCounts() {
        $counts = [
            'tasks' => 0,
            'projects' => 0,
            'project_members' => 0,
            'team' => 0,
            'activities' => 0
        ];

        try {
            $this->db->query("SELECT COUNT(*) as total FROM tasks");
            $counts['tasks'] = (int)($this->db->single()['total'] ?? 0);

            $this->db->query("SELECT COUNT(*) as total FROM projects");
            $counts['projects'] = (int)($this->db->single()['total'] ?? 0);

            $this->db->query("SELECT COUNT(*) as total FROM project_members");
            $counts['project_members'] = (int)($this->db->single()['total'] ?? 0);

            // Hitung anggota selain admin utama (id = 1)
            $this->db->query("SELECT COUNT(*) as total FROM users WHERE id != 1");
            $counts['team'] = (int)($this->db->single()['total'] ?? 0);

            $this->db->query("SELECT COUNT(*) as total FROM activities");
            $counts['activities'] = (int)($this->db->single()['total'] ?? 0);
        } catch (Exception $e) {
            error_log("Error getDataCounts: " . $e->getMessage());
        }

        return $counts;
    }

    /**
     * Menjalankan reset data sesuai opsi yang dipilih
     * @param array $options Array of keys: ['tasks', 'projects', 'team', 'activities']
     * @return array Status dan detail data yang berhasil direset
     */
    public function executeReset($options = []) {
        if (empty($options) || !is_array($options)) {
            return [
                'success' => false,
                'message' => 'Tidak ada opsi reset yang dipilih.'
            ];
        }

        $countsBefore = $this->getDataCounts();
        $resetLog = [];

        try {
            // Matikan sementara foreign key checks untuk kebersihan truncate/delete relasional
            $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
            $this->db->execute();

            // 1. Reset Tugas / Tasks
            if (in_array('tasks', $options)) {
                $this->db->query("TRUNCATE TABLE tasks");
                $this->db->execute();
                $resetLog[] = 'Papan Tugas (' . $countsBefore['tasks'] . ' tugas dihapus)';
            }

            // 2. Reset Proyek / Projects
            if (in_array('projects', $options)) {
                $this->db->query("TRUNCATE TABLE projects");
                $this->db->execute();

                $this->db->query("TRUNCATE TABLE project_members");
                $this->db->execute();

                // Karena tasks bergantung pada project_id, pastikan tasks juga bersih
                if (!in_array('tasks', $options)) {
                    $this->db->query("TRUNCATE TABLE tasks");
                    $this->db->execute();
                    $resetLog[] = 'Tugas terkait proyek turut dibersihkan';
                }

                $resetLog[] = 'Manajemen Proyek (' . $countsBefore['projects'] . ' proyek dihapus)';
            }

            // 3. Reset Anggota Tim / Team (Kecuali Admin Utama id: 1)
            if (in_array('team', $options)) {
                // Hapus user selain id 1
                $this->db->query("DELETE FROM users WHERE id != 1");
                $this->db->execute();

                // Hapus relasi project_members untuk user selain 1
                $this->db->query("DELETE FROM project_members WHERE user_id != 1");
                $this->db->execute();

                // Update tugas yang masih ada agar ditugaskan ke id 1
                $this->db->query("UPDATE tasks SET assignee_id = 1 WHERE assignee_id != 1");
                $this->db->execute();

                // Bersihkan tabel legacy team jika ada
                try {
                    $this->db->query("TRUNCATE TABLE team");
                    $this->db->execute();
                } catch(Exception $ex) {}

                $resetLog[] = 'Anggota Tim (' . $countsBefore['team'] . ' anggota dihapus, Admin Utama ID 1 dipertahankan)';
            }

            // 4. Reset Log Aktivitas
            if (in_array('activities', $options)) {
                $this->db->query("TRUNCATE TABLE activities");
                $this->db->execute();
                $resetLog[] = 'Log Aktivitas (' . $countsBefore['activities'] . ' riwayat dihapus)';
            }

            // Aktifkan kembali foreign key checks
            $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
            $this->db->execute();

            return [
                'success' => true,
                'message' => 'Reset berhasil dieksekusi.',
                'details' => $resetLog
            ];
        } catch (Exception $e) {
            // Pastikan foreign key checks dihidupkan kembali jika terjadi error
            try {
                $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
                $this->db->execute();
            } catch(Exception $ex) {}

            return [
                'success' => false,
                'message' => 'Gagal mereset data: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mengambil statistik ringkas database untuk kebutuhan backup
     */
    public function getBackupStats() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            return ['tables' => 0, 'rows' => 0, 'size_kb' => 0, 'db_name' => DB_NAME];
        }
        $conn->set_charset("utf8mb4");

        $tables = [];
        $result = $conn->query("SHOW TABLES");
        $totalRows = 0;
        if ($result) {
            while ($row = $result->fetch_row()) {
                $t = $row[0];
                $tables[] = $t;
                $cntRes = $conn->query("SELECT COUNT(*) FROM `$t`");
                if ($cntRes) {
                    $totalRows += (int)$cntRes->fetch_row()[0];
                }
            }
        }

        $sizeRes = $conn->query("
            SELECT ROUND(SUM(data_length + index_length) / 1024, 1) AS size_kb 
            FROM information_schema.TABLES 
            WHERE table_schema = '" . DB_NAME . "'
        ");
        $sizeKb = $sizeRes ? (float)($sizeRes->fetch_assoc()['size_kb'] ?? 0) : 0;

        $conn->close();
        return [
            'tables' => count($tables),
            'rows' => $totalRows,
            'size_kb' => $sizeKb,
            'db_name' => DB_NAME
        ];
    }

    /**
     * Menghasilkan file teks SQL cadangan (Full Database Backup Dumper)
     */
    public function generateDatabaseBackup() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            throw new Exception("Koneksi database gagal: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");

        $tables = [];
        $result = $conn->query("SHOW TABLES");
        while ($row = $result->fetch_row()) {
            $tables[] = $row[0];
        }

        $sql = "-- ========================================================\n";
        $sql .= "-- BACKUP DATABASE SISTEM: " . DB_NAME . "\n";
        $sql .= "-- Waktu Pembuatan : " . date('Y-m-d H:i:s') . "\n";
        $sql .= "-- Host             : " . DB_HOST . "\n";
        $sql .= "-- Dihasilkan Oleh  : Administrator Utama (ID: 1)\n";
        $sql .= "-- ========================================================\n\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
        $sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n";
        $sql .= "SET AUTOCOMMIT = 0;\n";
        $sql .= "START TRANSACTION;\n";
        $sql .= "SET time_zone = '+07:00';\n\n";

        foreach ($tables as $table) {
            $res = $conn->query("SHOW CREATE TABLE `$table`");
            $createRow = $res->fetch_row();

            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "-- Struktur Tabel `$table`\n";
            $sql .= "-- --------------------------------------------------------\n";
            $sql .= "DROP TABLE IF EXISTS `$table`;\n";
            $sql .= $createRow[1] . ";\n\n";

            $res = $conn->query("SELECT * FROM `$table`");
            $numRows = $res->num_rows;
            if ($numRows > 0) {
                $sql .= "-- Dumping Data Tabel `$table` (" . $numRows . " baris)\n";
                $sql .= "INSERT INTO `$table` VALUES \n";
                $first = true;
                while ($row = $res->fetch_row()) {
                    if (!$first) {
                        $sql .= ",\n";
                    }
                    $first = false;
                    $rowValues = array_map(function($val) use ($conn) {
                        if (is_null($val)) return "NULL";
                        return "'" . $conn->real_escape_string($val) . "'";
                    }, $row);
                    $sql .= "(" . implode(", ", $rowValues) . ")";
                }
                $sql .= ";\n\n";
            }
        }

        $sql .= "COMMIT;\n";
        $sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";
        $sql .= "-- ========================================================\n";
        $sql .= "-- Akhir dari file backup database\n";
        $sql .= "-- ========================================================\n";

        $conn->close();
        return $sql;
    }

    /**
     * Memulihkan database dari file .sql
     */
    public function restoreDatabase($filePath) {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            return [
                'success' => false,
                'message' => 'Berkas SQL tidak dapat dibaca atau tidak ditemukan.'
            ];
        }

        $sqlContent = file_get_contents($filePath);
        if (empty(trim($sqlContent))) {
            return [
                'success' => false,
                'message' => 'Berkas SQL kosong.'
            ];
        }

        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($conn->connect_error) {
            return [
                'success' => false,
                'message' => 'Koneksi database gagal: ' . $conn->connect_error
            ];
        }
        $conn->set_charset("utf8mb4");

        // Matikan foreign key checks untuk kebersihan restore
        $conn->query("SET FOREIGN_KEY_CHECKS = 0");

        if ($conn->multi_query($sqlContent)) {
            do {
                if ($result = $conn->store_result()) {
                    $result->free();
                }
            } while ($conn->more_results() && $conn->next_result());
        }

        if ($conn->errno) {
            $err = $conn->error;
            $conn->query("SET FOREIGN_KEY_CHECKS = 1");
            $conn->close();
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memulihkan database: ' . $err
            ];
        }

        $conn->query("SET FOREIGN_KEY_CHECKS = 1");
        $conn->close();

        return [
            'success' => true,
            'message' => 'Database berhasil dipulihkan secara penuh.'
        ];
    }
}
