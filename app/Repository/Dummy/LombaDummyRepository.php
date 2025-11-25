<?php

namespace App\Repository\Dummy;

use PDO;
use Faker\Factory;

class LombaDummyRepository
{
    private PDO $db;
    private $faker;

    private array $kategoriList = [
        "Technology",
        "Komik",
        "Banyak",
        "Sports",
        "Art",
        "Business"
    ];





    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->faker = Factory::create('id_ID');
    }

    private array $namaLombaList = [
        "Lomba Desain Poster Nasional",
        "Lomba Karya Tulis Ilmiah",
        "Lomba UI/UX Design",
        "Lomba Pengembangan Aplikasi Mobile",
        "Lomba Inovasi Teknologi Tepat Guna",
        "Business Plan Competition",
        "Lomba Debat Mahasiswa",
        "Lomba Fotografi Kreatif",
        "Lomba Video Kreatif",
        "Hackathon Kampus",
        "Lomba Cerdas Cermat",
        "Lomba Esai Nasional",
        "Lomba Animasi 3D",
        "Lomba Robot Line Follower",
        "Lomba Bisnis Digital",
        "Lomba E-Sport Mobile Legends",
        "Lomba Public Speaking",
        "Lomba Startup Pitching",
        "Lomba Pidato Bahasa Indonesia",
        "Lomba Bahasa Inggris Speech Contest"
    ];
    public function generateLomba(array $userIds, int $total = 20): void
    {
        if (empty($userIds)) {
            throw new \Exception("No users available to assign as lomba owner.");
        }

        $sql = "
            INSERT INTO public.lomba
            (nama_lomba, deskripsi, status, tanggal_lomba, hadiah, kategori, user_id, poster_lomba, lokasi)
            VALUES
            (:nama_lomba, :deskripsi, :status, :tanggal_lomba, :hadiah, :kategori, :user_id, :poster_lomba, :lokasi)
        ";
        $stmt = $this->db->prepare($sql);

        for ($i = 0; $i < $total; $i++) {
            $stmt->execute([
                ':nama_lomba' => $this->faker->randomElement($this->namaLombaList),
                ':deskripsi' => $this->faker->paragraph(2),
                ':status' => 'confirm',
                ':tanggal_lomba' => $this->faker->date('Y-m-d', '+3 months'),
                ':hadiah' => random_int(0, 10000000),
                ':kategori' => $this->faker->randomElement($this->kategoriList),
                ':user_id' => $userIds[array_rand($userIds)],
                ':poster_lomba' => null,
                ':lokasi' => $this->faker->city()
            ]);
        }
    }
}
