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

        // Inisialisasi variabel error
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Log untuk debugging
            error_log("Login attempt - Email: " . $email);

            // Validasi input tidak kosong
            if (empty($email) || empty($password)) {
                $error = "Email dan password harus diisi!";
                error_log("Login failed: Empty email or password");
            } else {
                $user = User::findByEmail($email);

                if ($user) {
                    error_log("User found: " . $user['email']);

                    // Cek apakah password di database sudah di-hash
                    $isPasswordHashed = strlen($user['password']) >= 60;

                    if ($isPasswordHashed) {
                        // Password sudah di-hash, gunakan password_verify
                        $passwordMatch = password_verify($password, $user['password']);
                    } else {
                        // Password masih plain text, bandingkan langsung (TIDAK AMAN!)
                        $passwordMatch = ($password === $user['password']);
                        error_log("WARNING: Password stored in plain text!");
                    }

                    if ($passwordMatch) {
                        // Regenerate session ID untuk keamanan
                        session_regenerate_id(true);

                        // Set session dengan struktur yang konsisten
                        $_SESSION['user'] = [
                            'user_id' => $user['user_id'],
                            'email' => $user['email'] ?? $user['username']
                        ];

                        // PENTING: Tambahkan juga flat session variables untuk kompatibilitas
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

        // Pass variabel $error ke view
        ob_start();
        include __DIR__ . '/../view/auth/login.php';
        return ob_get_clean();
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Log logout
        if (isset($_SESSION['user_id'])) {
            error_log("User logout - ID: " . $_SESSION['user_id']);
        }

        // Hapus semua session variables
        $_SESSION = array();

        // Hapus session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();
        header('Location: /login');
        exit;
    }
}