<?php

namespace App\Domain;

class User
{
    public string $user_id;
    public string $email;
    public string $username;
    public string $jurusan;
    public string $role;
    public string $password;
    public ?string $nama_lengkap = null;
    public ?string $angkatan = null;
    public ?string $tgl_lahir = null;
    public bool $is_notification_active = true;
}
