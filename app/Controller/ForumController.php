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
        $userId = $this->session->current();

        if (!$userId) {
            View::redirect('/users/login');
            exit();
        }

        $user = $this->userRepo->findById($userId);

        if (!$user) {
            View::redirect('/users/login');
            exit();
        }

        return $user;
    }

    /* --------------------------
     * Halaman forum
     * -------------------------- */
    public function forum()
    {
        $user = $this->requireLogin();
        $komunitas_list = Forum::getAllKomunitas();

        // Real Data for Trending Topics
        $trending_topics_data = Forum::getTrendingTopics(5);
        $trending_topics = array_map(function($topic) {
            return [
                'title' => $topic['title'],
                'excerpt' => $topic['excerpt'],
                'user_name' => '@' . $topic['user_name'],
                'user_avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $topic['user_name'],
                'views' => rand(100, 2000) . 'rb', // Mock views for now
                'comments' => $topic['comments'],
                'thumbnail' => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=200&h=200&fit=crop' // Default thumbnail
            ];
        }, $trending_topics_data);

        // Real Data for User Forums
        $user_forums_data = Forum::getUserForums($user->user_id);
        $user_forums = array_map(function($forum) use ($user) {
            return [
                'id' => $forum['komunitas_id'],
                'name' => $forum['name'],
                'code' => $forum['code'] ?? 'Komunitas',
                'user_handle' => '@' . $user->username, // Showing current user as member
                'members' => $forum['members'],
                'messages' => $forum['messages'],
                'thumbnail' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=100&h=100&fit=crop' // Default thumbnail
            ];
        }, $user_forums_data);

        View::render('component/forum/index', [
            'title'     => 'Forum',
            'user'      => $user,
            'komunitas_list' => $komunitas_list,
            'trending_topics' => $trending_topics,
            'user_forums' => $user_forums
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

        // Allow access if user is already a member OR if they meet the criteria
        if (!Forum::isUserMember($komunitas_id, $userId) && !Forum::canUserAccessKomunitas($komunitas_id, $user->jurusan)) {
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

        // Allow access if user is already a member OR if they meet the criteria
        if (!Forum::isUserMember($komunitas_id, $userId) && !Forum::canUserAccessKomunitas($komunitas_id, $user->jurusan)) {
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            exit();
        }

        $messageId = Forum::sendMessage($komunitas_id, $userId, $text, $reply_to);

        if (!$messageId) {
            echo json_encode(['success' => false, 'message' => 'Failed to send message']);
            exit();
        }

        $replyData = null;
        if ($reply_to) {
            $replyMessage = Forum::getMessageById($reply_to);
            if ($replyMessage) {
                $replyData = [
                    'username' => $replyMessage['username'],
                    'text' => $replyMessage['message_text']
                ];
            }
        }

        echo json_encode([
            'success'     => true,
            'message_id'  => $messageId,
            'username'    => $user->username,
            'time'        => date('H:i'),
            'reply_to'    => $replyData
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