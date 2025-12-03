<?php

namespace App\Controller;

use App\Model\Forum;
use App\Service\SessionService;
use App\App\View;
use App\Repository\UserRepository;
use App\Config\Database;

class ForumController
{
    private SessionService $session;
    private UserRepository $userRepo;

    public function __construct()
    {
        $this->session = new SessionService();
        $this->userRepo = new UserRepository(Database::getConnection());
    }

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

    public function forum()
    {
        $user = $this->requireLogin();
        $komunitas_list = Forum::getAllKomunitas();

        $trending_topics_data = Forum::getTrendingTopics(5);
        $trending_topics = array_map(function($topic) {
            return [
                'id' => $topic['komunitas_id'],
                'title' => $topic['title'],
                'excerpt' => $topic['excerpt'],
                'user_name' => '@' . $topic['user_name'],
                'user_avatar' => 'https://api.dicebear.com/7.x/avataaars/svg?seed=' . $topic['user_name'],
                'views' => $topic['member_count'],
                'comments' => $topic['comments'],
                'thumbnail' => $topic['image_url'] ?? 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=200&h=200&fit=crop'
            ];
        }, $trending_topics_data);

        $user_forums_data = Forum::getUserForums($user->user_id);
        $user_forums = array_map(function($forum) use ($user) {
            return [
                'id' => $forum['komunitas_id'],
                'name' => $forum['name'],
                'code' => $forum['code'] ?? 'Komunitas',
                'user_handle' => '@' . $user->username,
                'members' => $forum['members'],
                'messages' => $forum['messages'],
                'thumbnail' => $forum['image_url'] ?? 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=100&h=100&fit=crop'
            ];
        }, $user_forums_data);

        $available_forums_data = Forum::getAvailableForums($user->user_id);
        $available_forums = array_map(function($forum) {
            return [
                'id' => $forum['komunitas_id'],
                'name' => $forum['nama_komunitas'],
                'code' => $forum['deskripsi'] ?? 'Komunitas',
                'members' => $forum['members'],
                'thumbnail' => $forum['image_url'] ?? 'https://images.unsplash.com/photo-1517048676732-d65bc937f952?w=100&h=100&fit=crop'
            ];
        }, $available_forums_data);

        View::render('component/forum/index', [
            'title'     => 'Forum',
            'user'      => $user,
            'komunitas_list' => $komunitas_list,
            'trending_topics' => $trending_topics,
            'user_forums' => $user_forums,
            'available_forums' => $available_forums
        ]);
    }

    public function chat()
    {
        $user = $this->requireLogin();
        $userId = $user->user_id;

        $komunitas_id = intval($_GET['id'] ?? 0);
        $group_id = intval($_GET['group_id'] ?? 0);

        if ($komunitas_id <= 0) {
            View::redirect('/forum');
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

        $groups = Forum::getGroups($komunitas_id);
        
        if ($group_id <= 0 && !empty($groups)) {
            $group_id = $groups[0]['group_id'];
        }

        $current_group = null;
        if ($group_id > 0) {
            foreach ($groups as $g) {
                if ($g['group_id'] == $group_id) {
                    $current_group = $g;
                    break;
                }
            }
        }

        $members = Forum::getMembers($komunitas_id, 3);

        View::render('component/forum/chat', [
            'title'     => $komunitas['nama_komunitas'],
            'user'      => $user,
            'komunitas' => $komunitas,
            'groups'    => $groups,
            'current_group' => $current_group,
            'messages'  => Forum::getMessages($komunitas_id, $group_id),
            'current_user' => $user,
            'members'   => $members
        ]);
    }

    public function createGroup()
    {
        $user = $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/forum');
            exit();
        }

        $komunitas_id = intval($_POST['komunitas_id'] ?? 0);
        $name = trim($_POST['group_name'] ?? '');
        
        if ($komunitas_id > 0 && !empty($name)) {
            Forum::createGroup($komunitas_id, $name);
        }

        View::redirect('/forum/chat?id=' . $komunitas_id);
    }

    public function editGroup()
    {
        $user = $this->requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/forum');
            exit();
        }

        $komunitas_id = intval($_POST['komunitas_id'] ?? 0);
        $group_id = intval($_POST['group_id'] ?? 0);
        $name = trim($_POST['group_name'] ?? '');
        
        if ($group_id > 0 && !empty($name)) {
            $image_url = null;
            
            if (isset($_FILES['group_image']) && $_FILES['group_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/groups/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = strtolower(pathinfo($_FILES['group_image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = 'group_' . $group_id . '_' . time() . '.' . $fileExtension;
                    $uploadFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['group_image']['tmp_name'], $uploadFile)) {
                        $image_url = '/uploads/groups/' . $fileName;
                    }
                }
            }
            
            Forum::updateGroup($group_id, $name, $image_url);
        }

        View::redirect('/forum/chat?id=' . $komunitas_id . '&group_id=' . $group_id);
    }

    public function create()
    {
        $user = $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['nama_komunitas'] ?? '');
            $description = trim($_POST['deskripsi'] ?? '');
            $status = $_POST['status'] ?? 'public';
            $tags = trim($_POST['tags'] ?? '');
            
            if (empty($name) || empty($description)) {
                View::redirect('/forum/create?error=missing_fields');
                exit();
            }

            $image_url = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/uploads/forums/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileExtension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                if (in_array($fileExtension, $allowedExtensions)) {
                    $fileName = 'forum_' . time() . '_' . uniqid() . '.' . $fileExtension;
                    $uploadFile = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        $image_url = '/uploads/forums/' . $fileName;
                    }
                }
            }

            $komunitasId = Forum::createKomunitas($name, $description, $image_url, $status, $tags);

            if ($komunitasId) {
                Forum::addMember($komunitasId, $user->user_id);
                View::redirect('/forum/chat?id=' . $komunitasId);
            } else {
                View::redirect('/forum/create?error=failed');
            }
            exit();
        }

        View::render('component/forum/create', [
            'title' => 'Buat Forum Baru',
            'user' => $user
        ]);
    }

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
        $group_id = intval($_POST['group_id'] ?? 0);
        $text = trim($_POST['message'] ?? '');
        $reply_to = intval($_POST['reply_to_message_id'] ?? 0) ?: null;

        $image_url = null;
        if (isset($_FILES['message_image']) && $_FILES['message_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/messages/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['message_image']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = 'msg_' . $userId . '_' . time() . '.' . $fileExtension;
                $uploadFile = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['message_image']['tmp_name'], $uploadFile)) {
                    $image_url = '/uploads/messages/' . $fileName;
                }
            }
        }

        if ($komunitas_id <= 0 || ($text === '' && $image_url === null)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit();
        }

        $messageId = Forum::sendMessage($komunitas_id, $userId, $text, $reply_to, $group_id, $image_url);

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
            'reply_to'    => $replyData,
            'image_url'   => $image_url
        ]);

        exit();
    }

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