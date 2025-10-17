<?php

namespace App\Controller;

use App\Models\Competition;
use App\Models\Database;

class KompetisiController
{

    // Show kompetisi page
    public function index()
    {
        // Initialize competition model
        $competition = new Competition();

        // Get all competitions
        $stmt = $competition->readAll();
        $competitions = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Pass data to view
        require_once __DIR__ . '/../view/component/kompetisi/index.php';
    }

    // Alias untuk kompatibilitas dengan route lama
    public function kompetisi()
    {
        $this->index();
    }

    // Create new competition
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Check if user is logged in
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['user_id'])) {
                header('Location: /login?message=Silakan login terlebih dahulu');
                exit();
            }

            // Initialize competition model
            $competition = new Competition();

            // Handle file upload for poster
            $poster_lomba = '';
            if (isset($_FILES['poster_lomba']) && $_FILES['poster_lomba']['error'] === 0) {
                $target_dir = __DIR__ . "/../../public/uploads/posters/";

                // Create directory if not exists
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $file_extension = pathinfo($_FILES['poster_lomba']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid() . '_' . time() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;

                // Validate file type
                $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array(strtolower($file_extension), $allowed_types)) {
                    // Validate file size (max 5MB)
                    if ($_FILES['poster_lomba']['size'] <= 5242880) {
                        if (move_uploaded_file($_FILES['poster_lomba']['tmp_name'], $target_file)) {
                            $poster_lomba = '/uploads/posters/' . $new_filename;
                        }
                    } else {
                        header('Location: /kompetisi?status=error&message=Ukuran file terlalu besar (max 5MB)');
                        exit();
                    }
                } else {
                    header('Location: /kompetisi?status=error&message=Format file tidak didukung');
                    exit();
                }
            }

            // Set competition properties from POST data
            $competition->nama_lomba = $_POST['nama_lomba'] ?? '';
            $competition->poster_lomba = $poster_lomba;
            $competition->tanggal_lomba = $_POST['tanggal_lomba'] ?? '';
            $competition->user_id = $_SESSION['user_id'];

            // Validate input
            if (empty($competition->nama_lomba) || empty($competition->tanggal_lomba)) {
                header('Location: /kompetisi?status=error&message=Semua field harus diisi');
                exit();
            }

            // Create competition
            if ($competition->create()) {
                // Success - redirect with success message
                header('Location: /kompetisi?status=success&message=Lomba berhasil diposting');
                exit();
            } else {
                // Error - redirect with error message
                header('Location: /kompetisi?status=error&message=Gagal memposting lomba');
                exit();
            }
        }
    }

    // Get competition details
    public function detail($params = [])
    {
        // Extract ID from params array (untuk Router Anda)
        $id = $params['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid competition ID']);
            return;
        }

        $competition = new Competition();
        $competition->lomba_id = $id;

        if ($competition->readOne()) {
            // Return competition data as JSON
            header('Content-Type: application/json');
            echo json_encode([
                'lomba_id' => $competition->lomba_id,
                'nama_lomba' => $competition->nama_lomba,
                'poster_lomba' => $competition->poster_lomba,
                'status' => $competition->status,
                'tanggal_lomba' => $competition->tanggal_lomba,
                'user_id' => $competition->user_id
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Competition not found']);
        }
    }
}