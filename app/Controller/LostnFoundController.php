<?php

namespace App\Controller;

use App\Models\Database;

class LostnFoundController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function lost_found()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Ambil data dari database
        $items = $this->getAllItems();

        ob_start();
        include __DIR__ . '/../view/component/lost&found/index.php';
        return ob_get_clean();
    }

    private function getAllItems()
    {
        try {
            $query = "SELECT 
                        lf.id_barang,
                        lf.user_id,
                        lf.kategori,
                        lf.tanggal,
                        lf.lokasi,
                        lf.no_hp,
                        lf.email,
                        lf.deskripsi,
                        lf.nama_barang,
                        u.username
                      FROM lost_found lf
                      LEFT JOIN users u ON lf.user_id = u.user_id
                      ORDER BY lf.tanggal DESC";

            $stmt = $this->db->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            error_log("Error fetching lost_found items: " . $e->getMessage());
            return [];
        }
    }

    public function create()
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ambil user_id dari session (sudah pasti ada di $_SESSION['user_id'])
            $user_id = $_SESSION['user_id'];

            $kategori = $_POST['kategori'] ?? '';
            $lokasi = $_POST['lokasi'] ?? '';
            $no_hp = $_POST['no_hp'] ?? '';
            $email = $_POST['email'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $nama_barang = $_POST['nama_barang'] ?? '';

            // Validasi input required
            if (empty($kategori) || empty($nama_barang) || empty($deskripsi) || empty($lokasi) || empty($no_hp)) {
                error_log("Missing required fields");
                header('Location: /lost_found?error=missing_fields');
                exit;
            }

            try {
                // Generate id_barang unik (10 karakter)
                $id_barang = $this->generateUniqueId();

                $query = "INSERT INTO lost_found 
                         (id_barang, user_id, kategori, lokasi, no_hp, email, deskripsi, nama_barang, tanggal) 
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    $id_barang,
                    $user_id,
                    $kategori,
                    $lokasi,
                    $no_hp,
                    $email,
                    $deskripsi,
                    $nama_barang
                ]);

                error_log("Lost & Found item created successfully by user_id: " . $user_id);
                header('Location: /lost_found?success=1');
                exit;
            } catch (\PDOException $e) {
                error_log("Error creating lost_found item: " . $e->getMessage());
                header('Location: /lost_found?error=database');
                exit;
            }
        }
    }

    private function generateUniqueId()
    {
        do {
            // Generate random string 10 karakter
            $id_barang = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);

            // Cek apakah ID sudah ada di database
            $query = "SELECT COUNT(*) FROM lost_found WHERE id_barang = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id_barang]);
            $exists = $stmt->fetchColumn() > 0;
        } while ($exists);

        return $id_barang;
    }
}
