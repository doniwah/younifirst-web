<?php

namespace App\Models;

use App\Models\Database;

class Forum
{
    private static function getConnection()
    {
        return Database::getInstance();
    }

    public static function getAllKomunitas()
    {
        $db = self::getConnection();
        $query = "SELECT 
                    k.komunitas_id,
                    k.nama_komunitas,
                    k.deskripsi,
                    k.icon_type,
                    k.jurusan_filter,
                    COUNT(DISTINCT a.user_id) as jumlah_anggota,
                    (SELECT message_text 
                     FROM forum_messages 
                     WHERE komunitas_id = k.komunitas_id 
                     ORDER BY created_at DESC 
                     LIMIT 1) as latest_message,
                    (SELECT CONCAT(u.username, ' - ', 
                            CASE 
                                WHEN TIMESTAMPDIFF(MINUTE, fm.created_at, NOW()) < 1 THEN 'Baru saja'
                                WHEN TIMESTAMPDIFF(MINUTE, fm.created_at, NOW()) < 60 THEN CONCAT(TIMESTAMPDIFF(MINUTE, fm.created_at, NOW()), ' menit lalu')
                                WHEN TIMESTAMPDIFF(HOUR, fm.created_at, NOW()) < 24 THEN CONCAT(TIMESTAMPDIFF(HOUR, fm.created_at, NOW()), ' jam lalu')
                                ELSE CONCAT(TIMESTAMPDIFF(DAY, fm.created_at, NOW()), ' hari lalu')
                            END)
                     FROM forum_messages fm
                     JOIN users u ON fm.user_id = u.user_id
                     WHERE fm.komunitas_id = k.komunitas_id 
                     ORDER BY fm.created_at DESC 
                     LIMIT 1) as latest_message_info
                  FROM forum_komunitas k
                  LEFT JOIN forum_anggota a ON k.komunitas_id = a.komunitas_id
                  GROUP BY k.komunitas_id
                  ORDER BY 
                    CASE WHEN k.nama_komunitas = 'Komunitas Global' THEN 0 ELSE 1 END,
                    k.nama_komunitas ASC";

        $stmt = $db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function canUserAccessKomunitas($komunitas_id, $jurusan)
    {
        $db = self::getConnection();
        $query = "SELECT jurusan_filter FROM forum_komunitas WHERE komunitas_id = :komunitas_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->execute();
        $komunitas = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$komunitas) {
            return false;
        }

        if ($komunitas['jurusan_filter'] === null || $komunitas['jurusan_filter'] === '') {
            return true;
        }

        return $komunitas['jurusan_filter'] === $jurusan;
    }

    public static function getKomunitasById($komunitas_id)
    {
        $db = self::getConnection();
        $query = "SELECT k.*, 
                         COUNT(DISTINCT a.user_id) as jumlah_anggota
                  FROM forum_komunitas k
                  LEFT JOIN forum_anggota a ON k.komunitas_id = a.komunitas_id
                  WHERE k.komunitas_id = :komunitas_id
                  GROUP BY k.komunitas_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function isUserMember($komunitas_id, $user_id)
    {
        $db = self::getConnection();
        $query = "SELECT * FROM forum_anggota 
                  WHERE komunitas_id = :komunitas_id AND user_id = :user_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public static function addMember($komunitas_id, $user_id)
    {
        $db = self::getConnection();
        $query = "INSERT INTO forum_anggota (komunitas_id, user_id) 
                  VALUES (:komunitas_id, :user_id)";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public static function getMessages($komunitas_id)
    {
        $db = self::getConnection();
        $query = "SELECT 
                m.message_id,
                m.message_text,
                m.created_at,
                m.reply_to_message_id,
                u.user_id,
                u.username,
                rm.message_text as reply_message_text,
                ru.username as reply_username
               FROM forum_messages m
               JOIN users u ON m.user_id = u.user_id
               LEFT JOIN forum_messages rm ON m.reply_to_message_id = rm.message_id
               LEFT JOIN users ru ON rm.user_id = ru.user_id
               WHERE m.komunitas_id = :komunitas_id
               ORDER BY m.created_at ASC";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function sendMessage($komunitas_id, $user_id, $message_text, $reply_to_message_id = null)
    {
        $db = self::getConnection();
        $query = "INSERT INTO forum_messages 
              (komunitas_id, user_id, message_text, reply_to_message_id) 
              VALUES (:komunitas_id, :user_id, :message_text, :reply_to_message_id)";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':message_text', $message_text);
        $stmt->bindParam(':reply_to_message_id', $reply_to_message_id);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }

    public static function deleteMessage($message_id, $user_id)
    {
        $db = self::getConnection();


        $query_check = "SELECT user_id FROM forum_messages WHERE message_id = :message_id";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bindParam(':message_id', $message_id);
        $stmt_check->execute();
        $message = $stmt_check->fetch(\PDO::FETCH_ASSOC);

        if (!$message || $message['user_id'] != $user_id) {
            return false;
        }

        $query = "DELETE FROM forum_messages WHERE message_id = :message_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':message_id', $message_id);
        return $stmt->execute();
    }

    public static function getAnggotaCount($komunitas_id)
    {
        $db = self::getConnection();
        $query = "SELECT COUNT(*) as total FROM forum_anggota 
                  WHERE komunitas_id = :komunitas_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }

    public static function getMessageById($message_id)
    {
        $db = self::getConnection();
        $query = "SELECT m.*, u.username 
              FROM forum_messages m
              JOIN users u ON m.user_id = u.user_id
              WHERE m.message_id = :message_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':message_id', $message_id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}