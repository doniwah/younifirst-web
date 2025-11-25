<?php

namespace App\Repository\Dummy;

use PDO;
use Faker\Factory;

class ForumDummyRepository
{
    private PDO $db;
    private $faker;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->faker = Factory::create('id_ID');
    }


    /**
     * Create some komunitas, anggota and messages.
     */
    public function generateForum(array $userIds, int $komunitasCount = 5, int $messagesPerKomunitas = 5): void
    {
        if (empty($userIds)) {
            throw new \Exception("No users available for forum.");
        }

        // create komunitas
        $insertKomunitas = $this->db->prepare("
            INSERT INTO public.forum_komunitas (nama_komunitas, deskripsi, icon_type, jurusan_filter, created_at, updated_at)
            VALUES (:nama, :deskripsi, :icon_type, :jurusan_filter, now(), now())
            RETURNING komunitas_id
        ");

        $insertAnggota = $this->db->prepare("
            INSERT INTO public.forum_anggota (komunitas_id, user_id, joined_at)
            VALUES (:komunitas_id, :user_id, now())
        ");

        $insertMessage = $this->db->prepare("
            INSERT INTO public.forum_messages (komunitas_id, user_id, message_text, reply_to_message_id, created_at, updated_at)
            VALUES (:komunitas_id, :user_id, :text, NULL, now(), now())
        ");

        for ($k = 0; $k < $komunitasCount; $k++) {
            $this->db->beginTransaction();
            try {
                $insertKomunitas->execute([
                    ':nama' => 'Komunitas ' . ucfirst($this->faker->word()),
                    ':deskripsi' => $this->faker->sentence(6),
                    ':icon_type' => $this->faker->randomElement(['people', 'globe', 'book', 'code']),
                    ':jurusan_filter' => $this->faker->randomElement([null, 'Teknologi Informasi', 'Teknik', 'Bisnis'])
                ]);
                $komunitasId = $this->db->lastInsertId('forum_komunitas_komunitas_id_seq');

                // add some anggota
                $selected = $userIds;
                shuffle($selected);
                $take = array_slice($selected, 0, min(5, count($selected)));
                foreach ($take as $uid) {
                    $insertAnggota->execute([
                        ':komunitas_id' => $komunitasId,
                        ':user_id' => $uid
                    ]);
                }

                // add messages
                for ($m = 0; $m < $messagesPerKomunitas; $m++) {
                    $insertMessage->execute([
                        ':komunitas_id' => $komunitasId,
                        ':user_id' => $take[array_rand($take)],
                        ':text' => $this->faker->sentence(8)
                    ]);
                }

                $this->db->commit();
            } catch (\Exception $e) {
                $this->db->rollBack();
                // continue to next komunitas
            }
        }
    }
}
