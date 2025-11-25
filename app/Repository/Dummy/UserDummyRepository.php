<?php

namespace App\Repository\Dummy;

use PDO;
use Faker\Factory;

class UserDummyRepository
{
    private PDO $db;
    private $faker;

    // Mapping jurusan => prefix letter for email/user_id
    private array $jurusanList = [
        "Teknologi Informasi" => "E",
        "Teknik" => "P",
        "Kesehatan" => "G",
        "Management Agribisnis" => "D",
        "Produksi Pertanian" => "A",
        "Teknologi Pertanian" => "H",
        "Peternakan" => "K",
        "Bahasa Komunikasi dan Pariwisata" => "S",
        "Bisnis" => "B"
    ];

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->faker = Factory::create('id_ID');
    }

    /**
     * Generate users.
     * Returns array of generated user_id (strings) inserted.
     */
    public function generateUsers(int $total = 50): array
    {
        $insertSql = "
            INSERT INTO public.users (user_id, email, username, jurusan, role, password)
            VALUES (:user_id, :email, :username, :jurusan, :role, :password)
        ";
        $stmt = $this->db->prepare($insertSql);

        $generated = [];

        for ($i = 0; $i < $total; $i++) {
            // pick random jurusan key
            $jurusanKeys = array_keys($this->jurusanList);
            $jurusan = $jurusanKeys[array_rand($jurusanKeys)];
            $prefix = $this->jurusanList[$jurusan];

            // user_id: PREFIX + 8 digits (to keep reasonably short)
            $userId = $prefix . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);

            // email: PREFIX + 9 digits + @student.polije.ac.id
            $email = $prefix . str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT) . '@student.polije.ac.id';

            $username = preg_replace('/[^a-z0-9_.]/i', '', $this->faker->userName());
            $passwordHash = password_hash('password123', PASSWORD_BCRYPT);

            try {
                $stmt->execute([
                    ':user_id' => $userId,
                    ':email' => $email,
                    ':username' => $username,
                    ':jurusan' => $jurusan,
                    ':role' => 'mahasiswa',
                    ':password' => $passwordHash
                ]);
                $generated[] = $userId;
            } catch (\PDOException $e) {
                // possible duplicate user_id/email: skip and continue
                // generate a different id and try again up to a few times
                $tries = 0;
                $ok = false;
                while ($tries < 5 && ! $ok) {
                    $tries++;
                    $userId = $prefix . str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
                    $email = $prefix . str_pad((string) random_int(0, 999999999), 9, '0', STR_PAD_LEFT) . '@student.polije.ac.id';
                    try {
                        $stmt->execute([
                            ':user_id' => $userId,
                            ':email' => $email,
                            ':username' => $username,
                            ':jurusan' => $jurusan,
                            ':role' => 'mahasiswa',
                            ':password' => $passwordHash
                        ]);
                        $generated[] = $userId;
                        $ok = true;
                    } catch (\PDOException $e2) {
                        // continue tries
                    }
                }
                // if still not ok, skip this user
            }
        }

        return $generated;
    }

    /**
     * Get all user_ids currently in users table.
     */
    public function getAllUserIds(): array
    {
        $stmt = $this->db->query("SELECT user_id FROM public.users");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
