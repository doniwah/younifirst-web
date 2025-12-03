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
        $allEvents = $this->eventRepository->getAllEvents($userRole);
        $upcomingEvents = $this->eventRepository->getUpcomingEvents(5);
        $trendingEvents = $this->eventRepository->getTrendingEvents(5);
        $userEvents = $this->eventRepository->getUserEvents($userId, 5);

        View::render('component/event/index', [
            'title' => 'Event Management',
            'user' => $userId,
            'userRole' => $userRole,
            'events' => $allEvents,
            'upcomingEvents' => $upcomingEvents,
            'trendingEvents' => $trendingEvents,
            'userEvents' => $userEvents
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
        
        // Process multi-day events - get first and last date
        $tanggalHari = $_POST['tanggal_hari'] ?? [];
        $waktuMulai = $_POST['waktu_mulai'] ?? [];
        $waktuSelesai = $_POST['waktu_selesai'] ?? [];
        
        $tanggalMulai = !empty($tanggalHari) ? $tanggalHari[0] : null;
        $tanggalSelesai = !empty($tanggalHari) ? end($tanggalHari) : $tanggalMulai;
        
        // Combine first date with first time for waktu_pelaksanaan
        $waktuPelaksanaan = null;
        if ($tanggalMulai && !empty($waktuMulai[0])) {
            $waktuPelaksanaan = $waktuMulai[0];
        }
        
        // Process tags
        $tags = isset($_POST['tags']) ? implode(',', $_POST['tags']) : '';
        
        // Process registration deadline
        $dlPendaftaran = null;
        if (!empty($_POST['batas_tanggal_tutup']) && !empty($_POST['batas_waktu_tutup'])) {
            $dlPendaftaran = $_POST['batas_tanggal_tutup'] . ' ' . $_POST['batas_waktu_tutup'];
        }
        
        // Get contact person (WhatsApp)
        $contactPerson = $_POST['whatsapp'] ?? '';
        
        // Get Instagram URL
        $urlInstagram = $_POST['instagram'] ?? '';
        
        $eventData = [
            'nama_event' => $_POST['nama_event'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_selesai' => $tanggalSelesai,
            'lokasi' => $_POST['lokasi'] ?? '',
            'organizer' => $_POST['organizer'] ?? 'Campus Nexus',
            'kapasitas' => (int)($_POST['kapasitas'] ?? 100),
            'poster_event' => $posterPath,
            'kategori' => $tags,
            'harga' => $_POST['harga'] ?? '0',
            'dl_pendaftaran' => $dlPendaftaran,
            'waktu_pelaksanaan' => $waktuPelaksanaan,
            'user_id' => $userId,
            'contact_person' => $contactPerson,
            'url_instagram' => $urlInstagram,
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

        // Handle image upload
        $posterPath = null;
        if (isset($_FILES['poster_event']) && $_FILES['poster_event']['error'] === UPLOAD_ERR_OK) {
            $posterPath = $this->handleImageUpload($_FILES['poster_event']);
        }

        $eventData = [
            'nama_event' => $_POST['nama_event'] ?? '',
            'deskripsi' => $_POST['deskripsi'] ?? '',
            'tanggal_mulai' => $_POST['tanggal_mulai'] ?? '',
            'tanggal_selsai' => $_POST['tanggal_selsai'] ?? '',
            'lokasi' => $_POST['lokasi'] ?? '',
            'organizer' => $_POST['organizer'] ?? '',
            'kapasitas' => (int)($_POST['kapasitas'] ?? 0),
            'kategori' => $_POST['kategori'] ?? '',
            'harga' => $_POST['harga'] ?? 0
        ];

        if ($posterPath) {
            $eventData['poster_event'] = $posterPath;
        }

        $result = $this->eventRepository->updateEvent($id, $eventData);

        if ($result) {
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
            header('Location: /event?error=Anda tidak memiliki akses untuk menghapus event');
            exit;
        }

        $result = $this->eventRepository->deleteEvent($id);

        if ($result) {
            header('Location: /event?success=Event berhasil dihapus');
        } else {
            header('Location: /event?error=Gagal menghapus event');
        }
        exit;
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
            header('Location: /event?error=Anda tidak memiliki akses untuk mengkonfirmasi event');
            exit;
        }

        $result = $this->eventRepository->confirmEvent($id);

        if ($result) {
            header('Location: /event?success=Event berhasil dikonfirmasi');
        } else {
            header('Location: /event?error=Gagal mengkonfirmasi event');
        }
        exit;
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 15 * 1024 * 1024; // 15MB

        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        if ($file['size'] > $maxSize) {
            return false;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/events/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return '/uploads/events/' . $filename;
        }

        return false;
    }
}