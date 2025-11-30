<?php

namespace App\Repository\Dummy;

use PDO;
use Faker\Factory;

class EventDummyRepository
{
    private PDO $db;
    private $faker;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->faker = Factory::create('id_ID');
    }


    public function generateEvents(int $total = 10): array
    {
        $insert = $this->db->prepare("
            INSERT INTO public.event (event_id, nama_event, tanggal_selsai, status, poster_event, tanggal_mulai, lokasi, organizer, kapasitas, peserta_terdaftar, deskripsi)
            VALUES (:event_id, :nama_event, :tanggal_selsai, :status, :poster_event, :tanggal_mulai, :lokasi,  :organizer, :kapasitas, :peserta_terdaftar, :deskripsi)
        ");

        $created = [];
        for ($i = 1; $i <= $total; $i++) {
            $eventId = 'E' . str_pad((string) $i, 4, '0', STR_PAD_LEFT); // E0001
            $nama = $this->faker->sentence(3);
            $mulai = $this->faker->dateTimeBetween('+1 days', '+90 days')->format('Y-m-d');
            $sampai = (new \DateTime($mulai))->modify('+1 day')->format('Y-m-d');

            $insert->execute([
                ':event_id' => $eventId,
                ':nama_event' => $nama,
                ':tanggal_selsai' => $sampai,
                ':status' => 'confirm',
                ':poster_event' => null,
                ':tanggal_mulai' => $mulai,
                ':lokasi' => $this->faker->city(),
                ':organizer' => $this->faker->company(),
                ':kapasitas' => $this->faker->numberBetween(50, 500),
                ':peserta_terdaftar' => $this->faker->numberBetween(0, 500),
                ':deskripsi' => $this->faker->paragraph(3)
            ]);

            $created[] = $eventId;
        }

        return $created;
    }
}