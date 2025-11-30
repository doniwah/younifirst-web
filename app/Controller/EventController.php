<?php

namespace App\Controller;

use App\App\View;
use App\Service\SessionService;
use App\Repository\EventRepository;
use App\Config\Database;

class EventController
{
    private SessionService $session;
    private EventRepository $eventRepository;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->eventRepository = new EventRepository();
    }

    /**
     * Display list of events
     */
    public function index()
    {
        $userId = $this->session->current();
        
        // Get user role from database
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        // Get events based on role
        $events = $this->eventRepository->getAllEvents($userRole);

        View::render('component/event/index', [
            'title' => 'Event Management',
            'user' => $userId,
            'userRole' => $userRole,
            'events' => $events
        ]);
    }

    /**
     * Show create event form
     */
    public function create()
    {
        View::render('component/event/create', [
            'title' => 'Tambah Event Baru',
            'user' => $this->session->current()
        ]);
    }

    /**
     * Store new event
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /event');
            exit;
        }

        $userId = $this->session->current();
        
        if (!$userId) {
            header('Location: /users/login');
            exit;
        }
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        // Handle image upload
        $posterPath = null;
        if (isset($_FILES['poster_event']) && $_FILES['poster_event']['error'] === UPLOAD_ERR_OK) {
            $posterPath = $this->handleImageUpload($_FILES['poster_event']);
            if (!$posterPath) {
                header('Location: /event/create?error=Gagal mengupload gambar');
                exit;
            }
        }
        
        $eventData = [
            'nama_event' => $_POST['nama_event'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'tanggal_mulai' => $_POST['tanggal_mulai'] ?? '',
            'tanggal_selsai' => $_POST['tanggal_selsai'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'organizer' => $_POST['organizer'] ?? '',
            'kapasitas' => (int)($_POST['kapasitas'] ?? 0),
            'poster_event' => $posterPath,
            'status' => $userRole === 'admin' ? 'confirm' : 'waiting'
        ];

        $eventId = $this->eventRepository->createEvent($eventData);

        if ($eventId) {
            $message = $userRole === 'admin' 
                ? 'Event berhasil dibuat dan dikonfirmasi' 
                : 'Event berhasil dibuat dan menunggu konfirmasi admin';
            header('Location: /event?success=' . urlencode($message));
        } else {
            header('Location: /event/create?error=Gagal membuat event');
        }
        exit;
    }

    /**
     * Show edit event form
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
        
        // Only admin can edit events
        if ($userRole !== 'admin') {
            header('Location: /event?error=Anda tidak memiliki akses untuk mengedit event');
            exit;
        }
        
        $event = $this->eventRepository->getEventById($id);
        
        if (!$event) {
            header('Location: /event?error=Event tidak ditemukan');
            exit;
        }

        View::render('component/event/edit', [
            'title' => 'Edit Event',
            'user' => $userId,
            'userRole' => $userRole,
            'event' => $event
        ]);
    }

    /**
     * Update event
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /event');
            exit;
        }

        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        // Only admin can update events
        if ($userRole !== 'admin') {
            header('Location: /event?error=Anda tidak memiliki akses untuk mengupdate event');
            exit;
        }

        $updateData = [
            'nama_event' => $_POST['nama_event'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'tanggal_mulai' => $_POST['tanggal_mulai'] ?? '',
            'tanggal_selsai' => $_POST['tanggal_selsai'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'organizer' => $_POST['organizer'] ?? '',
            'kapasitas' => (int)($_POST['kapasitas'] ?? 0)
        ];
        
        // Handle image upload if new image provided
        if (isset($_FILES['poster_event']) && $_FILES['poster_event']['error'] === UPLOAD_ERR_OK) {
            $posterPath = $this->handleImageUpload($_FILES['poster_event']);
            if ($posterPath) {
                // Delete old image if exists
                $event = $this->eventRepository->getEventById($id);
                if ($event && $event['poster_event']) {
                    $oldImagePath = __DIR__ . '/../../public' . $event['poster_event'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
                $updateData['poster_event'] = $posterPath;
            }
        }

        $success = $this->eventRepository->updateEvent($id, $updateData);

        if ($success) {
            header('Location: /event?success=Event berhasil diupdate');
        } else {
            header('Location: /event/edit/' . $id . '?error=Gagal mengupdate event');
        }
        exit;
    }

    /**
     * Delete event
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
        
        // Only admin can delete events
        if ($userRole !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk menghapus event']);
            exit;
        }
        
        // Get event to delete image
        $event = $this->eventRepository->getEventById($id);
        
        $success = $this->eventRepository->deleteEvent($id);

        if ($success) {
            // Delete image file if exists
            if ($event && $event['poster_event']) {
                $imagePath = __DIR__ . '/../../public' . $event['poster_event'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            echo json_encode(['success' => true, 'message' => 'Event berhasil dihapus']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menghapus event']);
        }
    }

    /**
     * Confirm event (admin only)
     */
    public function confirm($id)
    {
        $userId = $this->session->current();
        
        // Get user role
        $db = Database::getConnection('prod');
        $stmt = $db->prepare("SELECT role FROM users WHERE user_id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
        $userRole = $user['role'] ?? 'user';
        
        // Only admin can confirm events
        if ($userRole !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Anda tidak memiliki akses untuk mengkonfirmasi event']);
            exit;
        }
        
        $success = $this->eventRepository->confirmEvent($id);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'Event berhasil dikonfirmasi']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mengkonfirmasi event']);
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
        
        $uploadDir = __DIR__ . '/../../public/uploads/events/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'event_' . time() . '_' . uniqid() . '.' . $extension;
        $uploadPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return '/uploads/events/' . $filename;
        }
        
        return false;
    }
}