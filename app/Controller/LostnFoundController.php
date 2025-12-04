<?php

namespace App\Controller;

use App\App\View;
use App\Service\SessionService;
use App\Repository\LostFoundRepository;
use App\Config\Database;

class LostnFoundController
{
    private SessionService $session;
    private LostFoundRepository $lostFoundRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->lostFoundRepository = new LostFoundRepository();
    }

    /**
     * Display list of lost/found items
     */
    public function index()
    {
        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        $items = $this->lostFoundRepository->getAllItems();

        View::render('component/lost&found/index', [
            'title' => 'Lost & Found',
            'user' => $userId,
            'userRole' => $userRole,
            'datas' => $items
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        View::render('component/lost&found/create', [
            'title' => 'Tambah Item Lost & Found',
            'user' => $this->session->current()
        ]);
    }

    /**
     * Store new item
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /lost_found');
            exit;
        }

        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }

        // Handle image upload
        $fotoPath = null;
        if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] === UPLOAD_ERR_OK) {
            $fotoPath = $this->handleImageUpload($_FILES['foto_barang']);
            if (!$fotoPath) {
                header('Location: /lost_found/create?error=Gagal mengupload gambar');
                exit;
            }
        }

        // Generate unique ID
        $idBarang = $this->generateUniqueId();

        $itemData = [
            'id_barang' => $idBarang,
            'user_id' => $userId,
            'kategori' => $_POST['kategori'] ?? '',
            'nama_barang' => $_POST['nama_barang'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'no_hp' => $_POST['no_hp'] ?? '',
            'email' => $_POST['email'] ?? '',
            'foto_barang' => $fotoPath,
            'status' => 'post'
        ];

        $itemId = $this->lostFoundRepository->createItem($itemData);

        if ($itemId) {
            header('Location: /lost_found?success=' . urlencode('Item berhasil ditambahkan'));
        } else {
            header('Location: /lost_found/create?error=Gagal menambahkan item');
        }
        exit;
    }

    /**
     * Show detail item
     */
    public function detail($id)
    {
        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        $item = $this->lostFoundRepository->getItemById($id);
        
        if (!$item) {
            header('Location: /lost_found?error=Item tidak ditemukan');
            exit;
        }

        View::render('component/lost&found/detail', [
            'title' => 'Detail Item - ' . $item['nama_barang'],
            'user' => $userId,
            'userRole' => $userRole,
            'item' => $item
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        $item = $this->lostFoundRepository->getItemById($id);
        
        if (!$item) {
            header('Location: /lost_found?error=Item tidak ditemukan');
            exit;
        }

        // Check permission: only owner or admin can edit
        if ($item['user_id'] !== $userId && $userRole !== 'admin') {
            header('Location: /lost_found?error=Anda tidak memiliki akses untuk mengedit item ini');
            exit;
        }

        View::render('component/lost&found/edit', [
            'title' => 'Edit Item Lost & Found',
            'user' => $userId,
            'userRole' => $userRole,
            'item' => $item
        ]);
    }

    /**
     * Update item
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /lost_found');
            exit;
        }

        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        $item = $this->lostFoundRepository->getItemById($id);
        
        if (!$item) {
            header('Location: /lost_found?error=Item tidak ditemukan');
            exit;
        }

        // Check permission
        if ($item['user_id'] !== $userId && $userRole !== 'admin') {
            header('Location: /lost_found?error=Anda tidak memiliki akses untuk mengupdate item ini');
            exit;
        }

        $updateData = [
            'kategori' => $_POST['kategori'] ?? '',
            'nama_barang' => $_POST['nama_barang'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'no_hp' => $_POST['no_hp'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];

        // Handle image upload if new image provided
        if (isset($_FILES['foto_barang']) && $_FILES['foto_barang']['error'] === UPLOAD_ERR_OK) {
            $fotoPath = $this->handleImageUpload($_FILES['foto_barang']);
            if ($fotoPath) {
                // Delete old image if exists
                if ($item['foto_barang']) {
                    $oldImagePath = __DIR__ . '/../../public' . $item['foto_barang'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $updateData['foto_barang'] = $fotoPath;
            }
        }

        $success = $this->lostFoundRepository->updateItem($id, $updateData);

        if ($success) {
            header('Location: /lost_found?success=Item berhasil diupdate');
        } else {
            header('Location: /lost_found/edit/' . $id . '?error=Gagal mengupdate item');
        }
        exit;
    }

    /**
     * Delete item
     */
    public function delete($id)
    {
        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        $item = $this->lostFoundRepository->getItemById($id);
        
        if (!$item) {
            echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan']);
            exit;
        }

        // Check permission
        if ($item['user_id'] !== $userId && $userRole !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus item ini']);
            exit;
        }

        $success = $this->lostFoundRepository->deleteItem($id);

        if ($success) {
            // Delete image file if exists
            if ($item['foto_barang']) {
                $imagePath = __DIR__ . '/../../public' . $item['foto_barang'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            echo json_encode(['success' => true, 'message' => 'Item berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus item']);
        }
    }

    /**
     * Mark item as complete
     */
    public function markComplete($id)
    {
        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        $item = $this->lostFoundRepository->getItemById($id);
        
        if (!$item) {
            echo json_encode(['success' => false, 'message' => 'Item tidak ditemukan']);
            exit;
        }

        // Check permission
        if ($item['user_id'] !== $userId && $userRole !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses']);
            exit;
        }

        $success = $this->lostFoundRepository->updateStatus($id, 'selesai');

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Item ditandai sebagai selesai']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengupdate status']);
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
        
        $uploadDir = __DIR__ . '/../../public/uploads/lostfound/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'lostfound_' . time() . '_' . uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return '/uploads/lostfound/' . $filename;
        }
        
        return false;
    }

    /**
     * Generate unique ID for item
     */
    private function generateUniqueId()
    {
        do {
            $idBarang = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
        } while ($this->lostFoundRepository->isIdExists($idBarang));
        return $idBarang;
    }
}
