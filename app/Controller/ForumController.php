<?php

namespace App\Controller;

use App\Model\Forum;
use App\Service\SessionService;
use App\App\View;
use App\Repository\UserRepository;
use App\Config\Database; // pastikan sesuai folder Database kamu

class ForumController
{
    private SessionService $session;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->userRepo = new UserRepository(Database::getConnection());
    }

    /* --------------------------
     * Helper untuk cek login
     * -------------------------- */
    private function requireLogin()
    {
        // Ambil user dari session (User object)
        $user = $this->session->current();

        if (!$user) {
            View::redirect('/users/login');
            exit();
        }

        // Jika session menyimpan user_id saja, bisa di-handle di sini:
        if (is_array($user) && isset($user->user_id)) {
            // berarti session lama → ambil user dari repo
            $user = $this->userRepo->findById($user->user_id);
        }

        // Jika masih null berarti gagal ambil dari DB
        if (!$user) {
            View::redirect('/users/login');
            exit();
        }

        return $user; // Ini object App\Domain\User
    }

    /* --------------------------
     * Halaman forum
     * -------------------------- */
    public function forum()
    {
        $user = $this->requireLogin();
        $komunitas_list = Forum::getAllKomunitas();
        View::render('component/forum/index', [
            'title'     => 'Forum',
            'user'      => $user,
            'komunitas_list' => $komunitas_list
        ]);
    }

    /* --------------------------
     * Halaman chat
     * -------------------------- */
    public function chat()
    {
        $user = $this->requireLogin();
        $userId = $user->user_id;

        $komunitas_id = intval($_GET['id'] ?? 0);

        if ($komunitas_id <= 0) {
            View::redirect('/forum');
            exit();
        }

        if (!Forum::canUserAccessKomunitas($komunitas_id, $user->jurusan)) {
            View::redirect('/forum?error=access_denied');
            exit();
        }

        $komunitas = Forum::getKomunitasById($komunitas_id);

        if (!$komunitas) {
            View::redirect('/forum');
            exit();
        }

        if (!Forum::isUserMember($komunitas_id, $userId)) {
            Forum::addMember($komunitas_id, $userId);
        }

        View::render('component/forum/chat', [
            'title'     => $komunitas['nama_komunitas'],
            'user'      => $user,
            'komunitas' => $komunitas,
            'messages'  => Forum::getMessages($komunitas_id),
            'current_user' => $user
        ]);
    }

    /* --------------------------
     * Kirim pesan (AJAX)
     * -------------------------- */
    public function sendMessage()
    {
        header('Content-Type: application/json');

        $user = $this->requireLogin();
        $userId = $user->user_id;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit();
        }

        $komunitas_id = intval($_POST['komunitas_id'] ?? 0);
        $text = trim($_POST['message'] ?? '');
        $reply_to = intval($_POST['reply_to_message_id'] ?? 0) ?: null;

        if ($komunitas_id <= 0 || $text === '') {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit();
        }

        if (!Forum::canUserAccessKomunitas($komunitas_id, $user->jurusan)) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit();
        }

        $messageId = Forum::sendMessage($komunitas_id, $userId, $text, $reply_to);

        if (!$messageId) {
            echo json_encode(['success' => false, 'message' => 'Failed to send message']);
            exit();
        }

        echo json_encode([
            'success'     => true,
            'message_id'  => $messageId,
            'username'    => $user->username,
            'time'        => date('H:i')
        ]);

        exit();
    }

    /* --------------------------
     * Hapus pesan
     * -------------------------- */
    public function deleteMessage()
    {
        header('Content-Type: application/json');

        $user = $this->requireLogin();
        $userId = $user->user_id;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit();
        }

        $message_id = intval($_POST['message_id'] ?? 0);

        if ($message_id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
            exit();
        }

        $result = Forum::deleteMessage($message_id, $userId);

        echo json_encode(['success' => $result]);
        exit();
    }
}