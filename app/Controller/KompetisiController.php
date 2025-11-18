<?php

namespace App\Controller;

use App\Model\Competition;
use App\Service\SessionService;
use App\App\View;
use App\Model\Team;

class KompetisiController
{

    private SessionService $session;
    private Competition $competition;
    private Team $teams;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->competition = new Competition();
        $this->teams = new Team();
    }

    public function index()
    {

        $competitions = $this->competition->readAll();
        $teams = $this->teams->readAll();

        View::render('component/kompetisi/index', [
            'title' => 'Kompetisi',
            'user' => $this->session->current(),
            'competitions' => $competitions,
            'teams' => $teams
        ]);
    }


    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /kompetisi');
            exit;
        }

        $user = $this->session->current();
        if (!$user) {
            header('Location: /users/login?message=Silakan login terlebih dahulu');
            exit;
        }

        $poster_path = '';
        if (isset($_FILES['poster_lomba']) && $_FILES['poster_lomba']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . '/../../public/uploads/posters/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $filename = uniqid() . '-' . basename($_FILES['poster_lomba']['name']);
            $target_file = $upload_dir . $filename;
            if (move_uploaded_file($_FILES['poster_lomba']['tmp_name'], $target_file)) {
                $poster_path = '/uploads/posters/' . $filename;
            }
        }

        $this->competition->nama_lomba = $_POST['nama_lomba'] ?? '';
        $this->competition->kategori = $_POST['kategori'] ?? '';
        $this->competition->deskripsi = $_POST['deskripsi'] ?? '';
        $this->competition->tanggal_lomba = $_POST['deadline'] ?? '';
        $this->competition->lokasi = $_POST['lokasi'] ?? '';
        $this->competition->hadiah = $_POST['hadiah'] ?? '0';
        $this->competition->user_id = $_SESSION['user_id'];
        $this->competition->poster_lomba = $poster_path;

        if (empty($this->competition->nama_lomba) || empty($this->competition->tanggal_lomba)) {
            echo "Validasi gagal: Field kosong<br>";
            View::render('component/kompetisi/index', [
                'title' => 'Kompetisi',
                'user' => $this->session->current()
            ]);
            exit();
        }

        if ($this->competition->create()) {
            header('Location: /kompetisi?status=success&message=Lomba berhasil diposting! Menunggu persetujuan admin');
            exit();
        } else {
            header('Location: /kompetisi?status=error&message=Gagal memposting lomba');
            exit();
        }
    }

    public function detail($params = [])
    {
        $id = $params['id'] ?? null;

        if (!$id) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid competition ID']);
            return;
        }

        $this->competition->lomba_id = $id;

        if ($this->competition->readOne()) {
            // Return competition data as JSON
            header('Content-Type: application/json');
            echo json_encode([
                'lomba_id' => $this->competition->lomba_id,
                'nama_lomba' => $this->competition->nama_lomba,
                'deskripsi' => $this->competition->deskripsi,
                'kategori' => $this->competition->kategori,
                'tanggal_lomba' => $this->competition->tanggal_lomba,
                'lokasi' => $this->competition->lokasi,
                'hadiah' => $this->competition->hadiah,
                'status' => $this->competition->status,
                'user_id' => $this->competition->user_id
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Competition not found']);
        }
    }

    public function approve($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard?status=error&message=Unauthorized access');
            exit();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard?status=error&message=Invalid competition ID');
            exit();
        }

        $this->competition->lomba_id = $id;

        if ($this->competition->approve()) {
            header('Location: /dashboard?status=success&message=Lomba berhasil di-approve');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal approve lomba');
            exit();
        }
    }

    // Reject competition (Admin only)
    public function reject($params = [])
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check if admin
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /dashboard?status=error&message=Unauthorized access');
            exit();
        }

        $id = $params['id'] ?? null;

        if (!$id) {
            header('Location: /dashboard?status=error&message=Invalid competition ID');
            exit();
        }

        $this->competition->lomba_id = $id;

        if ($this->competition->reject()) {
            header('Location: /dashboard?status=success&message=Lomba berhasil di-reject');
            exit();
        } else {
            header('Location: /dashboard?status=error&message=Gagal reject lomba');
            exit();
        }
    }
}
