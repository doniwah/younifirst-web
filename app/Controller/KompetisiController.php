<?php

namespace App\Controller;

use App\Config\Database;
use App\App\View;

class KompetisiController
{
    public function index()
    {
        $db = Database::getConnection('prod');

        // Get competitions
        $query = $db->query("
            SELECT lomba_id, nama_lomba, tanggal_lomba, kategori, lokasi, deskripsi, hadiah, poster_lomba
            FROM lomba
            ORDER BY tanggal_lomba DESC
        ");

        $competitions = $query->fetchAll();

        // Get teams
        $queryTeam = $db->query("
            SELECT team_id, nama_team, nama_kegiatan, deskripsi_anggota, max_anggota, role_required, status, poster_lomba
            FROM team
            ORDER BY team_id DESC
        ");

        $teams = $queryTeam->fetchAll();

        View::render('component/kompetisi/index', [
            'title' => 'Kompetisi',
            'competitions' => $competitions,
            'teams' => $teams
        ]);
    }

    public function create()
    {
        if (isset($_POST['nama_lomba'])) {
            $this->createLomba();
        } elseif (isset($_POST['nama_team'])) {
            $this->createTeam();
        } else {
            header("Location: /kompetisi?status=error&message=Invalid request");
            exit;
        }
    }

    public function createLomba()
    {
        $db = Database::getConnection('prod');

        // Cek apakah lomba_id di database bertipe integer atau varchar
        // Jika integer, gunakan angka random saja
        $id = rand(100000, 999999);

        // Support kedua nama field: tanggal_lomba atau deadline
        $tanggal = $_POST['tanggal_lomba'] ?? $_POST['deadline'] ?? null;

        if (!$tanggal) {
            header("Location: /kompetisi?status=error&message=Tanggal lomba harus diisi");
            exit;
        }

        $posterPath = null;
        if (isset($_FILES['poster_lomba']) && $_FILES['poster_lomba']['error'] === UPLOAD_ERR_OK) {
            $posterPath = $this->handleImageUpload($_FILES['poster_lomba']);
            if (!$posterPath) {
                header('Location: /kompetisi/create?error=Gagal mengupload gambar');
                exit;
            }
        }

        $nama = $_POST['nama_lomba'];
        $kategori = $_POST['kategori'] ?? '';
        $lokasi = $_POST['lokasi'] ?? '';
        $deskripsi = $_POST['deskripsi'] ?? '';
        $hadiah = isset($_POST['hadiah']) ? (int)$_POST['hadiah'] : 0;
        $user_id = $_SESSION['user_id'] ?? 1;
        

        try {
            $stmt = $db->prepare("
                INSERT INTO lomba (lomba_id, nama_lomba, deskripsi, status, tanggal_lomba, hadiah, kategori, user_id, lokasi, poster_lomba)
                VALUES (:id, :nama, :desk, 'waiting', :tanggal, :hadiah, :kategori, :user_id, :lokasi, :poster)
            ");

            $stmt->execute([
                ':id' => $id,
                ':nama' => $nama,
                ':desk' => $deskripsi,
                ':tanggal' => $tanggal,
                ':hadiah' => $hadiah,
                ':kategori' => $kategori,
                ':user_id' => $user_id,
                ':lokasi' => $lokasi,
                ':poster' => $posterPath
            ]);

            header("Location: /kompetisi?status=success&message=Lomba berhasil dibuat");
            exit;
        } catch (\PDOException $e) {

            if (strpos($e->getMessage(), 'duplicate key') !== false) {
                // Coba dengan ID yang berbeda
                $id = rand(100000, 999999);
                try {
                    $stmt->execute([
                        ':id' => $id,
                        ':nama' => $nama,
                        ':desk' => $deskripsi,
                        ':tanggal' => $tanggal,
                        ':hadiah' => $hadiah,
                        ':kategori' => $kategori,
                        ':user_id' => $user_id,
                        ':lokasi' => $lokasi,
                        ':poster' => $posterPath
                    ]);
                    header("Location: /kompetisi?status=success&message=Lomba berhasil dibuat");
                    exit;
                } catch (\PDOException $e2) {
                    header("Location: /kompetisi?status=error&message=Gagal membuat lomba: " . $e2->getMessage());
                    exit;
                }
            } else {
                header("Location: /kompetisi?status=error&message=Gagal membuat lomba: " . $e->getMessage());
                exit;
            }
        }
    }

    public function createTeam()
    {
        $db = Database::getConnection('prod');

        // Generate team_id sesuai dengan tipe data di database
        // Jika integer, gunakan angka random
        $team_id = rand(100000, 999999);

        $nama_team = $_POST['nama_team'];
        $nama_kegiatan = $_POST['nama_kegiatan'];
        $deskripsi = $_POST['deskripsi'] ?? $_POST['deskripsi_tim'] ?? '';
        $max_anggota = $_POST['max_anggota'] ?? $_POST['jumlah_anggota'] ?? 1;
        $role_required = $_POST['role_dibutuhkan'] ?? '-';
        $user_id = $_SESSION['user_id'] ?? 1; // Default jika session tidak ada

        try {
            // Insert team
            $stmt = $db->prepare("
                INSERT INTO team (team_id, nama_kegiatan, nama_team, deskripsi_anggota, role_required, max_anggota, role, status)
                VALUES (:id, :kegiatan, :nama, :desk, :role_req, :max, 'ketua', 'waiting')
            ");

            $stmt->execute([
                ':id' => $team_id,
                ':kegiatan' => $nama_kegiatan,
                ':nama' => $nama_team,
                ':desk' => $deskripsi,
                ':role_req' => $role_required,
                ':max' => $max_anggota
            ]);

            // Insert user sebagai ketua
            $stmt2 = $db->prepare("
                INSERT INTO detail_anggota (team_id, user_id, tanggal_gabung, role, status)
                VALUES (:team_id, :user_id, NOW(), 'ketua', 'confirm')
            ");
            $stmt2->execute([
                ':team_id' => $team_id,
                ':user_id' => $user_id
            ]);

            header("Location: /kompetisi?status=success&message=Tim berhasil dibuat");
            exit;
        } catch (\PDOException $e) {
            // Cek jika error karena duplikat ID
            if (strpos($e->getMessage(), 'duplicate key') !== false) {
                // Coba dengan ID yang berbeda
                $team_id = rand(100000, 999999);
                try {
                    $stmt->execute([
                        ':id' => $team_id,
                        ':kegiatan' => $nama_kegiatan,
                        ':nama' => $nama_team,
                        ':desk' => $deskripsi,
                        ':role_req' => $role_required,
                        ':max' => $max_anggota
                    ]);

                    $stmt2->execute([
                        ':team_id' => $team_id,
                        ':user_id' => $user_id
                    ]);

                    header("Location: /kompetisi?status=success&message=Tim berhasil dibuat");
                    exit;
                } catch (\PDOException $e2) {
                    header("Location: /kompetisi?status=error&message=Gagal membuat tim: " . $e2->getMessage());
                    exit;
                }
            } else {
                header("Location: /kompetisi?status=error&message=Gagal membuat tim: " . $e->getMessage());
                exit;
            }
        }
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }
        
        if ($file['size'] > $maxSize) {
            return false;
        }
        
        $uploadDir = __DIR__ . '/../../public/uploads/kompetisi/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'lomba_' . time() . '_' . uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return '/uploads/kompetisi/' . $filename;
        }
        
        return false;
    }
}