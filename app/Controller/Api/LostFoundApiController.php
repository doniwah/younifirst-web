<?php

namespace App\Controller\Api;

use App\Service\SessionService;
use App\Repository\LostFoundRepository;
use App\Config\Database;

class LostFoundApiController
{
    private SessionService $session;
    private LostFoundRepository $lostFoundRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->lostFoundRepository = new LostFoundRepository();
    }

    /**
     * Get all items with filters
     */
    public function getAllItems()
    {
        header('Content-Type: application/json');

        try {
            $filters = [
                'kategori' => $_GET['kategori'] ?? '',
                'status' => $_GET['status'] ?? '',
                'search' => $_GET['search'] ?? ''
            ];

            $items = $this->lostFoundRepository->getItemsWithFilters($filters);

            echo json_encode([
                'success' => true,
                'data' => $items,
                'total' => count($items)
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Get item by ID
     */
    public function getItem($id)
    {
        header('Content-Type: application/json');

        try {
            $item = $this->lostFoundRepository->getItemById($id);

            if (!$item) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Item not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $item
            ], JSON_PRETTY_PRINT);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Create new item
     */
    public function createItem()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $userId = $this->session->current();
            if (!$userId) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], JSON_PRETTY_PRINT);
                return;
            }

            // Generate unique ID
            do {
                $idBarang = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
            } while ($this->lostFoundRepository->isIdExists($idBarang));

            $itemData = [
                'id_barang' => $idBarang,
                'user_id' => $userId,
                'kategori' => $data['kategori'] ?? '',
                'nama_barang' => $data['nama_barang'] ?? '',
                'deskripsi' => $data['deskripsi'] ?? '',
                'lokasi' => $data['lokasi'] ?? '',
                'no_hp' => $data['no_hp'] ?? '',
                'email' => $data['email'] ?? '',
                'foto_barang' => $data['foto_barang'] ?? null,
                'status' => 'aktif'
            ];

            $itemId = $this->lostFoundRepository->createItem($itemData);

            if ($itemId) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Item created successfully',
                    'data' => [
                        'item_id' => $itemId,
                        'item' => $this->lostFoundRepository->getItemById($itemId)
                    ]
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to create item'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Update item
     */
    public function updateItem($id)
    {
        header('Content-Type: application/json');

        if (!in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'PATCH'])) {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $userId = $this->session->current();
            if (!$userId) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $item = $this->lostFoundRepository->getItemById($id);
            if (!$item) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Item not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            // Check permission
            $db = Database::getConnection('prod');
            $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $userRole = $user['role'] ?? 'user';

            if ($item['user_id'] !== $userId && $userRole !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Forbidden'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $input = file_get_contents('php://input');
            $data = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid JSON data'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->lostFoundRepository->updateItem($id, $data);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Item updated successfully',
                    'data' => $this->lostFoundRepository->getItemById($id)
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update item'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Delete item
     */
    public function deleteItem($id)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $userId = $this->session->current();
            if (!$userId) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $item = $this->lostFoundRepository->getItemById($id);
            if (!$item) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Item not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            // Check permission
            $db = Database::getConnection('prod');
            $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $userRole = $user['role'] ?? 'user';

            if ($item['user_id'] !== $userId && $userRole !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Forbidden'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->lostFoundRepository->deleteItem($id);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Item deleted successfully'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete item'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }

    /**
     * Mark item as complete
     */
    public function markComplete($id)
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode([
                'success' => false,
                'message' => 'Method not allowed'
            ], JSON_PRETTY_PRINT);
            return;
        }

        try {
            $userId = $this->session->current();
            if (!$userId) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $item = $this->lostFoundRepository->getItemById($id);
            if (!$item) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Item not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            // Check permission
            $db = Database::getConnection('prod');
            $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $userRole = $user['role'] ?? 'user';

            if ($item['user_id'] !== $userId && $userRole !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Forbidden'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->lostFoundRepository->updateStatus($id, 'selesai');

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Item marked as complete'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update status'
                ], JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                    'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], JSON_PRETTY_PRINT);
        }
    }
}
