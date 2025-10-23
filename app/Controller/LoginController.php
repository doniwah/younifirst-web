<?php

namespace App\Controller;

use App\Models\User;

class LoginController
{
    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';


            error_log("Login attempt - Email: " . $email);

            if (empty($email) || empty($password)) {
                $error = "Email dan password harus diisi!";
                error_log("Login failed: Empty email or password");
            } else {
                $user = User::findByEmail($email);

                if ($user) {
                    error_log("User found: " . $user['email']);

                    $isPasswordHashed = strlen($user['password']) >= 60;

                    if ($isPasswordHashed) {

                        $passwordMatch = password_verify($password, $user['password']);
                    } else {

                        $passwordMatch = ($password === $user['password']);
                        error_log("WARNING: Password stored in plain text!");
                    }

                    if ($passwordMatch) {

                        session_regenerate_id(true);


                        $_SESSION['user'] = [
                            'user_id' => $user['user_id'],
                            'email' => $user['email'] ?? $user['username']
                        ];

                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['email'] = $user['email'] ?? $user['username'];
                        $_SESSION['role'] = $user['role'] ?? 'user';

                        error_log("Login success - User ID: " . $_SESSION['user_id']);
                        error_log("Session ID: " . session_id());
                        error_log("Session data: " . print_r($_SESSION, true));

                        header('Location: /dashboard');
                        exit;
                    } else {
                        $error = "Password salah!";
                        error_log("Login failed: Invalid password");
                    }
                } else {
                    $error = "Email tidak ditemukan!";
                    error_log("Login failed: User not found");
                }
            }
        }

        ob_start();
        include __DIR__ . '/../view/auth/login.php';
        return ob_get_clean();
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user_id'])) {
            error_log("User logout - ID: " . $_SESSION['user_id']);
        }

        $_SESSION = array();

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();
        header('Location: /login');
        exit;
    }
}
