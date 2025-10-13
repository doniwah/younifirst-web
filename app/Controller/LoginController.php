<?php

namespace App\Controller;

use App\Models\User;

class LoginController
{
    public function login()
    {
        // Pastikan session hanya start sekali
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Inisialisasi variabel error
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validasi input tidak kosong
            if (empty($email) || empty($password)) {
                $error = "Email dan password harus diisi!";
            } else {
                $user = User::findByEmail($email);

                if ($user) {
                    // Cek apakah password di database sudah di-hash
                    $isPasswordHashed = strlen($user['password']) >= 60;

                    if ($isPasswordHashed) {
                        // Password sudah di-hash, gunakan password_verify
                        $passwordMatch = password_verify($password, $user['password']);
                    } else {
                        // Password masih plain text, bandingkan langsung (TIDAK AMAN!)
                        $passwordMatch = ($password === $user['password']);
                    }

                    if ($passwordMatch) {
                        $_SESSION['user'] = [
                            'user_id' => $user['user_id'],
                            'email' => $user['email'] ?? $user['username']
                        ];
                        header('Location: /dashboard');
                        exit;
                    } else {
                        $error = "Password salah!";
                    }
                } else {
                    $error = "Email tidak ditemukan!";
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
        session_destroy();
        header('Location: /login');
        exit;
    }
}
