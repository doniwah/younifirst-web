<?php

namespace App\Repository;

use App\Config\Database;
use PDO;

class ModerationRepository
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }


    public function getPendingItems()
    {
        $items = [];

        // 1. Competitions (Table: lomba)
        // Schema: lomba_id, nama_lomba, status (lomba_status_enum), created_at
        $sqlComp = "SELECT lomba_id as id, nama_lomba as title, 'competition' as type, created_at FROM lomba WHERE status = 'waiting'";
        $stmtComp = $this->db->query($sqlComp);
        while ($row = $stmtComp->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        // 2. Teams (Table: team)
        // Schema: team_id, nama_team, status (lomba_status_enum), tenggat_join (no created_at)
        $sqlTeam = "SELECT team_id as id, nama_team as title, 'team' as type, tenggat_join as created_at FROM team WHERE status = 'waiting'";
        $stmtTeam = $this->db->query($sqlTeam);
        while ($row = $stmtTeam->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        // 3. Events (Table: event)
        // Schema: event_id, nama_event, status (status_enum), created_at
        $sqlEvent = "SELECT event_id as id, nama_event as title, 'event' as type, created_at FROM event WHERE status = 'waiting'";
        $stmtEvent = $this->db->query($sqlEvent);
        while ($row = $stmtEvent->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        // 4. Lost & Found (Table: lost_found)
        // Schema: id_barang, nama_barang, status (statuslostfound), created_at
        // We cast to text to avoid "invalid input value for enum" errors if the value doesn't match.
        // We'll check for 'aktif', 'pending', or 'waiting' by casting.
        $sqlLost = "SELECT id_barang as id, nama_barang as title, 'lost_found' as type, created_at FROM lost_found WHERE status::text IN ('aktif', 'pending', 'waiting')";
        $stmtLost = $this->db->query($sqlLost);
        while ($row = $stmtLost->fetch(PDO::FETCH_ASSOC)) {
            $items[] = $row;
        }

        // Sort by created_at desc
        usort($items, function($a, $b) {
            $timeA = !empty($a['created_at']) ? strtotime($a['created_at']) : 0;
            $timeB = !empty($b['created_at']) ? strtotime($b['created_at']) : 0;
            return $timeB - $timeA;
        });

        return $items;
    }

    public function updateStatus($type, $id, $status)
    {
        $table = '';
        $idColumn = 'id';
        $dbStatus = $status;

        // Map statuses
        if ($status === 'approved') {
            if ($type === 'team') {
                $dbStatus = 'confirm';
            } elseif ($type === 'competition') {
                $dbStatus = 'active'; // Assuming 'active' is the approved state for lomba
            } elseif ($type === 'event') {
                $dbStatus = 'confirm'; // Event uses 'confirm'
            } elseif ($type === 'lost_found') {
                $dbStatus = 'aktif'; // Keep as aktif
            }
        } elseif ($status === 'rejected') {
             if ($type === 'team' || $type === 'competition' || $type === 'event') {
                $dbStatus = 'rejected'; // Assuming 'rejected' exists in enum
            } elseif ($type === 'lost_found') {
                $dbStatus = 'selesai'; // Mark as selesai (done/removed)
            }
        }

        switch ($type) {
            case 'competition':
                $table = 'lomba';
                $idColumn = 'lomba_id';
                break;
            case 'team':
                $table = 'team';
                $idColumn = 'team_id';
                break;
            case 'event':
                $table = 'event';
                $idColumn = 'event_id';
                break;
            case 'lost_found':
                $table = 'lost_found';
                $idColumn = 'id_barang';
                break;
            default:
                return false;
        }

        $sql = "UPDATE $table SET status = ? WHERE $idColumn = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$dbStatus, $id]);
    }
}
