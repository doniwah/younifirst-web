<?php

namespace App\Controller\Api;

use App\Service\SessionService;
use App\Repository\EventRepository;
use App\Config\Database;

class EventApiController
{
    private SessionService $session;
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->eventRepository = new EventRepository();
    }

    /**
     * Get all events with pagination and filters
     */
    public function getAllEvents()
    {
        header('Content-Type: application/json');

        try {
            $page = max(1, (int)($_GET['page'] ?? 1));
            $limit = max(1, min(100, (int)($_GET['limit'] ?? 10)));
            $status = $_GET['status'] ?? '';
            
            $userRole = 'user';
            $userId = $this->session->current();
            if ($userId) {
                $db = Database::getConnection('prod');
                $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
                $stmt->execute([$userId]);
                $user = $stmt->fetch();
                $userRole = $user['role'] ?? 'user';
            }

            $events = $this->eventRepository->getAllEvents($userRole);
            
            // Filter by status if provided
            if (!empty($status)) {
                $events = array_filter($events, function($event) use ($status) {
                    return $event['status'] === $status;
                });
            }
            
            // Pagination
            $totalItems = count($events);
            $totalPages = ceil($totalItems / $limit);
            $offset = ($page - 1) * $limit;
            $events = array_slice($events, $offset, $limit);

            echo json_encode([
                'success' => true,
                'data' => array_values($events),
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_items' => $totalItems,
                    'items_per_page' => $limit
                ]
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
     * Get event by ID
     */
    public function getEvent($id)
    {
        header('Content-Type: application/json');

        try {
            $eventId = (int)$id;
            $event = $this->eventRepository->getEventById($eventId);

            if (!$event) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Event not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            echo json_encode([
                'success' => true,
                'data' => $event
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
     * Create new event
     */
    public function createEvent() {
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
        // Deteksi apakah data dikirim sebagai JSON atau form-data
        if (isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
            // JSON request
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
        } else {
            // Form-data request (bisa dengan file)
            $data = $_POST;
            
            // Handle file upload jika ada
            if (isset($_FILES['poster_event']) && $_FILES['poster_event']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../uploads/events/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Generate unique filename
                $timestamp = time();
                $randomString = bin2hex(random_bytes(8));
                $fileName = $randomString . '_' . $timestamp . '.jpg';
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['poster_event']['tmp_name'], $filePath)) {
                    $data['poster_event'] = '/uploads/events/' . $fileName;
                } else {
                    $data['poster_event'] = '';
                }
            } else {
                $data['poster_event'] = $data['poster_event'] ?? '';
            }
        }

        // Validation - Field yang benar-benar required
        $requiredFields = ['nama_event', 'deskripsi', 'tanggal_mulai', 'lokasi', 'organizer', 'kapasitas', 'kategori', 'user_id'];
        $missingFields = [];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty(trim($data[$field]))) {
                $missingFields[] = $field;
            }
        }

        if (!empty($missingFields)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Missing required fields: ' . implode(', ', $missingFields)
            ], JSON_PRETTY_PRINT);
            return;
        }
        
        // Get user role dari user_id
        $userRole = 'user';
        $userId = $data['user_id'];
        
        if ($userId) {
            $db = Database::getConnection('prod');
            $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $userRole = $user['role'] ?? 'user';
        }

        // Siapkan data event dengan nilai default
        $eventData = [
            'nama_event' => trim($data['nama_event']),
            'deskripsi' => trim($data['deskripsi']),
            'tanggal_mulai' => $data['tanggal_mulai'],
            'lokasi' => trim($data['lokasi']),
            'organizer' => trim($data['organizer']),
            'kapasitas' => (int)$data['kapasitas'],
            'kategori' => trim($data['kategori']),
            'harga' => isset($data['harga']) ? (int)$data['harga'] : 0, // Default 0 jika tidak ada
            'poster_event' => $data['poster_event'] ?? '',
            'contact_person' => $data['contact_person'] ?? '', // Default string kosong
            'url_instagram' => $data['url_instagram'] ?? '', // Default string kosong
            'user_id' => $data['user_id'],
            'status' => $userRole === 'admin' ? 'confirm' : 'waiting'
        ];

        // Tambahkan field opsional jika ada
        if (isset($data['waktu_pelaksanaan']) && !empty(trim($data['waktu_pelaksanaan']))) {
            $eventData['waktu_pelaksanaan'] = trim($data['waktu_pelaksanaan']);
        }
        
        if (isset($data['dl_pendaftaran']) && !empty(trim($data['dl_pendaftaran']))) {
            $eventData['dl_pendaftaran'] = trim($data['dl_pendaftaran']);
        }

        $eventId = $this->eventRepository->createEvent($eventData);

        if ($eventId) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Event created successfully',
                'data' => [
                    'event_id' => $eventId,
                    'event' => $this->eventRepository->getEventById($eventId)
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create event'
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
     * Update event
     */
    public function updateEvent($id)
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
            // Check admin permission
            $userId = $this->session->current();
            if (!$userId) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            $db = Database::getConnection('prod');
            $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $userRole = $user['role'] ?? 'user';
            
            if ($userRole !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Forbidden: Admin access required'
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            $eventId = (int)$id;
            $event = $this->eventRepository->getEventById($eventId);

            if (!$event) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Event not found'
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

            $updateData = [];
            $allowedFields = [
                'nama_event', 'deskripsi', 'tanggal_mulai', 'tanggal_selsai',
                'lokasi', 'organizer', 'kapasitas', 'kategori', 'harga', 'poster_event', 'status'
            ];

            foreach ($allowedFields as $field) {
                if (isset($data[$field])) {
                    if ($field === 'kapasitas') {
                        $updateData[$field] = (int)$data[$field];
                    } else {
                        $updateData[$field] = $data[$field];
                    }
                }
            }

            if (empty($updateData)) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'No valid fields to update'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->eventRepository->updateEvent($eventId, $updateData);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Event updated successfully',
                    'data' => $this->eventRepository->getEventById($eventId)
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update event'
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
     * Delete event
     */
    public function deleteEvent($id)
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
            // Check admin permission
            $userId = $this->session->current();
            if (!$userId) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            $db = Database::getConnection('prod');
            $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $userRole = $user['role'] ?? 'user';
            
            if ($userRole !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Forbidden: Admin access required'
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            $eventId = (int)$id;
            $event = $this->eventRepository->getEventById($eventId);

            if (!$event) {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Event not found'
                ], JSON_PRETTY_PRINT);
                return;
            }

            $success = $this->eventRepository->deleteEvent($eventId);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Event deleted successfully'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to delete event'
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
     * Confirm event (admin only)
     */
    public function confirmEvent($id)
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
            // Check admin permission
            $userId = $this->session->current();
            if (!$userId) {
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            $db = Database::getConnection('prod');
            $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
            $userRole = $user['role'] ?? 'user';
            
            if ($userRole !== 'admin') {
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'message' => 'Forbidden: Admin access required'
                ], JSON_PRETTY_PRINT);
                return;
            }
            
            $eventId = (int)$id;
            $success = $this->eventRepository->confirmEvent($eventId);

            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Event confirmed successfully'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to confirm event'
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
     * Register for event
     */
    public function registerForEvent($id)
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
            
            $eventId = (int)$id;
            $success = $this->eventRepository->registerForEvent($eventId, $userId);

            if ($success) {
                http_response_code(201);
                echo json_encode([
                    'success' => true,
                    'message' => 'Successfully registered for event'
                ], JSON_PRETTY_PRINT);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to register. Event may be full or you are already registered.'
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
     * Get event registrations
     */
    public function getEventRegistrations($id)
    {
        header('Content-Type: application/json');

        try {
            $eventId = (int)$id;
            $registrations = $this->eventRepository->getEventRegistrations($eventId);

            echo json_encode([
                'success' => true,
                'data' => $registrations
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
}
