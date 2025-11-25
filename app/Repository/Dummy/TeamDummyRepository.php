<?php

namespace App\Repository\Dummy;

use PDO;
use Faker\Factory;

class TeamDummyRepository
{
    private PDO $db;
    private $faker;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->faker = Factory::create('id_ID');
    }

    /**
     * Generate teams and optionally add members (detail_anggota).
     * teamCount = number of teams
     * membersPerTeam = average members per team (including leader)
     */
    public function generateTeams(array $userIds, int $teamCount = 10, int $membersPerTeam = 3): array
    {
        if (empty($userIds)) {
            throw new \Exception("No users available for teams.");
        }

        $insertTeam = $this->db->prepare("
            INSERT INTO public.team (team_id, nama_kegiatan, nama_team, deskripsi_anggota, role_required, max_anggota, role, status)
            VALUES (:team_id, :nama_kegiatan, :nama_team, :deskripsi_anggota, :role_required, :max_anggota, :role, :status)
        ");

        $insertDetail = $this->db->prepare("
            INSERT INTO public.detail_anggota (team_id, user_id, tanggal_gabung, role, status)
            VALUES (:team_id, :user_id, :tanggal_gabung, :role, :status)
        ");

        $createdTeams = [];

        for ($t = 1; $t <= $teamCount; $t++) {
            $teamId = 'T' . str_pad((string) $t, 4, '0', STR_PAD_LEFT); // T0001, T0002...
            $namaKegiatan = $this->faker->word();
            $namaTeam = ucfirst($this->faker->words(2, true));
            $deskripsi = $this->faker->sentence();
            $roleRequired = implode(', ', $this->faker->randomElements(['ketua', 'anggota', 'designer', 'developer', 'researcher'], 2));
            $maxAnggota = max(1, (int) $this->faker->numberBetween(3, 6));
            $role = 'anggota';
            $status = 'waiting';

            // insert team
            $insertTeam->execute([
                ':team_id' => $teamId,
                ':nama_kegiatan' => $namaKegiatan,
                ':nama_team' => $namaTeam,
                ':deskripsi_anggota' => $deskripsi,
                ':role_required' => $roleRequired,
                ':max_anggota' => $maxAnggota,
                ':role' => $role,
                ':status' => $status
            ]);

            // pick random users to be members (one leader + members)
            $shuffle = $userIds;
            shuffle($shuffle);
            $members = array_slice($shuffle, 0, min($membersPerTeam, count($shuffle)));

            // leader is first
            $leader = $members[0] ?? $userIds[array_rand($userIds)];
            // insert leader as ketua
            $insertDetail->execute([
                ':team_id' => $teamId,
                ':user_id' => $leader,
                ':tanggal_gabung' => date('Y-m-d H:i:s'),
                ':role' => 'ketua',
                ':status' => 'confirm'
            ]);

            // insert other members
            for ($m = 1; $m < count($members); $m++) {
                $insertDetail->execute([
                    ':team_id' => $teamId,
                    ':user_id' => $members[$m],
                    ':tanggal_gabung' => date('Y-m-d H:i:s'),
                    ':role' => 'anggota',
                    ':status' => 'confirm'
                ]);
            }

            $createdTeams[] = $teamId;
        }

        return $createdTeams;
    }
}
