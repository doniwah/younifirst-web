<?php

namespace App\Repository\Dummy;

use PDO;
use Faker\Factory;

class LostFoundDummyRepository
{
    private PDO $db;
    private $faker;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->faker = Factory::create('id_ID');
    }

    private array $namaList = [
        "Dompet",
        "Kunci Motor",
        "Handphone",
        "Laptop",
        "Tas Ransel",
        "Kacamata",
        "Jam Tangan",
        "Buku Catatan",
        "Payung",
        "Topi",
        "Sepatu",
        "Earphone",
        "Flashdisk",
        "Baju",
        "Botol Minum",
        "Stnk",
        "Kartu Identitas",
    ];

    /**
     * Generate lost & found entries.
     * id_barang format requested: LF0001 ...
     */
    public function generateLostFound(array $userIds, int $total = 10): array
    {
        if (empty($userIds)) {
            throw new \Exception("No users available for lost_found.");
        }

        $insert = $this->db->prepare("
            INSERT INTO public.lost_found (id_barang, user_id, kategori, tanggal, lokasi, no_hp, email, deskripsi, nama_barang)
            VALUES (:id_barang, :user_id, :kategori, :tanggal, :lokasi, :no_hp, :email, :deskripsi, :nama_barang)
        ");

        $created = [];
        for ($i = 1; $i <= $total; $i++) {
            $idBarang = 'LF' . str_pad((string) $i, 4, '0', STR_PAD_LEFT); // LF0001
            $userId = $userIds[array_rand($userIds)];
            $kategori = $this->faker->randomElement(['hilang', 'menemukan']);
            $tanggal = $this->faker->dateTimeBetween('-30 days', 'now')->format('Y-m-d H:i:s');
            $lokasi = $this->faker->streetName();
            $noHp = $this->faker->numerify('08##########');
            // pick user's email if exists
            $stmtEmail = $this->db->prepare("SELECT email FROM public.users WHERE user_id = :uid LIMIT 1");
            $stmtEmail->execute([':uid' => $userId]);
            $email = $stmtEmail->fetchColumn() ?: ($this->faker->safeEmail);

            $insert->execute([
                ':id_barang' => $idBarang,
                ':user_id' => $userId,
                ':kategori' => $kategori,
                ':tanggal' => $tanggal,
                ':lokasi' => $lokasi,
                ':no_hp' => $noHp,
                ':email' => $email,
                ':deskripsi' => $this->faker->sentence(),
                ':nama_barang' => $this->faker->randomElement($this->namaList)
            ]);

            $created[] = $idBarang;
        }

        return $created;
    }
}
