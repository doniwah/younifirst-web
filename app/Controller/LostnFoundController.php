<?php

namespace App\Controller;

use App\Model\LostnFoundModel;
use App\Service\SessionService;
use App\App\View;

class LostnFoundController
{
    private SessionService $session;
    private LostnFoundModel $dataBarang;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->dataBarang = new LostnFoundModel();
    }

    public function lost_found()
    {
        View::render('component/lost&found/index', [
            'title' => 'Kompetisi',
            'user' => $this->session->current(),
            'datas' => $this->dataBarang->getAllItems()
        ]);
    }

    public function create()
    {
        // $user = $this->sessionService->current();

        if (!$this->session->current()) {
            header('Location: /users/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user_id = $this->session->current()->user_id; 
            $kategori = $_POST['kategori'] ?? '';
            $lokasi = $_POST['lokasi'] ?? '';
            $no_hp = $_POST['no_hp'] ?? '';
            $email = $_POST['email'] ?? '';
            $deskripsi = $_POST['deskripsi'] ?? '';
            $nama_barang = $_POST['nama_barang'] ?? '';

            if (empty($kategori) || empty($nama_barang) || empty($deskripsi) || empty($lokasi) || empty($no_hp)) {
                header('Location: /lost_found?error=missing_fields');
                exit;
            }

            $id_barang = $this->generateUniqueId();

            $data = [
                'id_barang' => $id_barang,
                'user_id' => $user_id,
                'kategori' => $kategori,
                'lokasi' => $lokasi,
                'no_hp' => $no_hp,
                'email' => $email,
                'deskripsi' => $deskripsi,
                'nama_barang' => $nama_barang
            ];

            if ($this->dataBarang->insertItem($data)) {
                header('Location: /lost_found?success=1');
            } else {
                header('Location: /lost_found?error=database');
            }
            exit;
        }
    }

    private function generateUniqueId()
    {
        do {
            $id_barang = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        } while ($this->dataBarang->isIdExists($id_barang));
        return $id_barang;
    }
}
