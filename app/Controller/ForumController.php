<?php

namespace App\Controller;

use App\Models\Forum;
use App\Models\User;

class ForumController
{
    public function __construct()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function getUserId()
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }

        if (isset($_SESSION['user']['user_id'])) {
            return $_SESSION['user']['user_id'];
        }

        return null;
    }

    public function forum()
    {

        $isLoggedIn = isset($_SESSION['user']) || isset($_SESSION['user_id']);

        if (!$isLoggedIn) {
            header("Location: /login");
            exit();
        }

        $user_id = $this->getUserId();

        if (!$user_id) {
            header("Location: /login");
            exit();
        }

        $user_data = User::getUserById($user_id);

        if (!$user_data) {
            header("Location: /login");
            exit();
        }

        $current_user_jurusan = $user_data['jurusan'] ?? null;


        $komunitas_list = Forum::getAllKomunitas();


        require_once __DIR__ . '/../view/component/forum/index.php';
    }

    public function chat()
    {

        $isLoggedIn = isset($_SESSION['user']) || isset($_SESSION['user_id']);

        if (!$isLoggedIn) {
            header("Location: /login");
            exit();
        }

        $user_id = $this->getUserId();

        if (!$user_id) {
            header("Location: /login");
            exit();
        }

        $user_data = User::getUserById($user_id);

        if (!$user_data) {
            header("Location: /login");
            exit();
        }

        $komunitas_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($komunitas_id <= 0) {
            header("Location: /forum");
            exit();
        }

        $jurusan = $user_data['jurusan'] ?? null;

        if (!Forum::canUserAccessKomunitas($komunitas_id, $jurusan)) {

            header("Location: /forum?error=access_denied");
            exit();
        }

        $komunitas = Forum::getKomunitasById($komunitas_id);

        if (!$komunitas) {
            header("Location: /forum");
            exit();
        }


        if (!Forum::isUserMember($komunitas_id, $user_id)) {
            Forum::addMember($komunitas_id, $user_id);
        }


        $messages = Forum::getMessages($komunitas_id);
        $current_user = $user_data;
        require_once __DIR__ . '/../view/component/forum/chat.php';
    }

    public function sendMessage()
    {
        header('Content-Type: application/json');

        $user_id = $this->getUserId();

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $komunitas_id = isset($_POST['komunitas_id']) ? intval($_POST['komunitas_id']) : 0;
            $message_text = isset($_POST['message']) ? trim($_POST['message']) : '';
            $reply_to_message_id = isset($_POST['reply_to_message_id']) ? intval($_POST['reply_to_message_id']) : null;

            if ($komunitas_id > 0 && !empty($message_text)) {
                $user_data = User::getUserById($user_id);

                if (!Forum::canUserAccessKomunitas($komunitas_id, $user_data['jurusan'] ?? null)) {
                    echo json_encode(['success' => false, 'message' => 'Access denied']);
                    exit();
                }

                $message_id = Forum::sendMessage($komunitas_id, $user_id, $message_text, $reply_to_message_id);

                if ($message_id) {
                    $response = [
                        'success' => true,
                        'message_id' => $message_id,
                        'username' => $user_data['username'],
                        'time' => date('H:i')
                    ];


                    if ($reply_to_message_id) {
                        $reply_message = Forum::getMessageById($reply_to_message_id);
                        $response['reply_to'] = [
                            'username' => $reply_message['username'] ?? '',
                            'text' => $reply_message['message_text'] ?? ''
                        ];
                    }

                    echo json_encode($response);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid data']);
            }
        }
        exit();
    }

    public function deleteMessage()
    {
        header('Content-Type: application/json');

        $user_id = $this->getUserId();

        if (!$user_id) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;

            if ($message_id > 0) {
                $result = Forum::deleteMessage($message_id, $user_id);

                if ($result) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Cannot delete message']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
            }
        }
        exit();
    }
}
