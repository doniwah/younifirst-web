<?php

namespace App\Model;

use App\Config\Database;

class Forum
{
    private static function getConnection()
    {
        return Database::getConnection('prod');
    }

    public static function getKomunitas($jurusan = null)
    {
        $db = self::getConnection();
        $query = "SELECT k.*, 
                         COUNT(DISTINCT a.user_id) as jumlah_anggota,
                         COUNT(DISTINCT m.message_id) as jumlah_pesan
                  FROM forum_komunitas k
                  LEFT JOIN forum_anggota a ON k.komunitas_id = a.komunitas_id
                  LEFT JOIN forum_messages m ON k.komunitas_id = m.komunitas_id
                  WHERE 1=1";

        if ($jurusan) {
            $query .= " AND (k.jurusan_filter IS NULL OR k.jurusan_filter = '' OR k.jurusan_filter = :jurusan)";
        }

        $query .= " GROUP BY k.komunitas_id 
                    ORDER BY k.nama_komunitas ASC";

        $stmt = $db->prepare($query);
        if ($jurusan) {
            $stmt->bindParam(':jurusan', $jurusan);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Alias for getKomunitas
    public static function getAllKomunitas($jurusan = null)
    {
        return self::getKomunitas($jurusan);
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

        $filter = $komunitas['jurusan_filter'];
        if (empty($filter)) {
            return true;
        }

        return $filter === $jurusan;
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
        $query = "SELECT COUNT(*) as count FROM forum_anggota 
                  WHERE komunitas_id = :komunitas_id AND user_id = :user_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public static function addMember($komunitas_id, $user_id)
    {
        $db = self::getConnection();
        $query = "INSERT INTO forum_anggota (komunitas_id, user_id, joined_at) 
                  VALUES (:komunitas_id, :user_id, NOW())
                  ON CONFLICT DO NOTHING";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public static function getMessages($komunitas_id, $group_id = null)
    {
        $db = self::getConnection();
        
        if ($group_id) {
            $query = "SELECT m.*, u.username
                      FROM forum_messages m
                      JOIN users u ON m.user_id = u.user_id
                      WHERE m.komunitas_id = :komunitas_id AND m.group_id = :group_id
                      ORDER BY m.created_at ASC";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':komunitas_id', $komunitas_id);
            $stmt->bindParam(':group_id', $group_id);
        } else {
            $query = "SELECT m.*, u.username
                      FROM forum_messages m
                      JOIN users u ON m.user_id = u.user_id
                      WHERE m.komunitas_id = :komunitas_id AND (m.group_id IS NULL OR m.group_id = 0)
                      ORDER BY m.created_at ASC";
            
            $stmt = $db->prepare($query);
            $stmt->bindParam(':komunitas_id', $komunitas_id);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getMessagesAfter($komunitas_id, $group_id, $after_id)
    {
        $db = self::getConnection();
        $query = "SELECT m.*, u.username
                  FROM forum_messages m
                  JOIN users u ON m.user_id = u.user_id
                  WHERE m.komunitas_id = :komunitas_id 
                    AND m.group_id = :group_id
                    AND m.message_id > :after_id
                  ORDER BY m.created_at ASC";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':after_id', $after_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function sendMessage($komunitas_id, $user_id, $message_text, $reply_to_message_id = null, $group_id = null, $image_url = null)
    {
        $db = self::getConnection();
        $query = "INSERT INTO forum_messages (komunitas_id, user_id, message_text, reply_to_message_id, group_id, image_url, created_at) 
                  VALUES (:komunitas_id, :user_id, :message_text, :reply_to, :group_id, :image_url, NOW())";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':message_text', $message_text);
        $stmt->bindParam(':reply_to', $reply_to_message_id);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':image_url', $image_url);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }

    public static function deleteMessage($message_id, $user_id)
    {
        $db = self::getConnection();
        
        $checkQuery = "SELECT user_id FROM forum_messages WHERE message_id = :message_id";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':message_id', $message_id);
        $checkStmt->execute();
        $message = $checkStmt->fetch(\PDO::FETCH_ASSOC);
        
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
        $query = "SELECT COUNT(*) as count FROM forum_anggota WHERE komunitas_id = :komunitas_id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count'];
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

    public static function getTrendingTopics($limit = 5)
    {
        $db = self::getConnection();
        $query = "SELECT k.komunitas_id, k.nama_komunitas as title, k.deskripsi as excerpt, k.image_url,
                         COALESCE(
                            (SELECT u.username 
                             FROM forum_messages m2 
                             JOIN users u ON m2.user_id = u.user_id 
                             WHERE m2.komunitas_id = k.komunitas_id 
                             GROUP BY u.username 
                             ORDER BY COUNT(*) DESC 
                             LIMIT 1), 
                            'Admin'
                         ) as user_name,
                         (SELECT COUNT(*) FROM forum_anggota fa WHERE fa.komunitas_id = k.komunitas_id) as member_count,
                         COUNT(DISTINCT m.message_id) as comments
                  FROM forum_komunitas k
                  LEFT JOIN forum_messages m ON k.komunitas_id = m.komunitas_id
                  GROUP BY k.komunitas_id, k.nama_komunitas, k.deskripsi, k.image_url
                  ORDER BY comments DESC
                  LIMIT :limit";

        $stmt = $db->prepare($query);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getUserForums($user_id)
    {
        $db = self::getConnection();
        $query = "SELECT k.*, k.nama_komunitas as name,
                         COUNT(DISTINCT a.user_id) as members,
                         COUNT(DISTINCT m.message_id) as messages
                  FROM forum_komunitas k
                  JOIN forum_anggota fa ON k.komunitas_id = fa.komunitas_id
                  LEFT JOIN forum_anggota a ON k.komunitas_id = a.komunitas_id
                  LEFT JOIN forum_messages m ON k.komunitas_id = m.komunitas_id
                  WHERE fa.user_id = :user_id
                  GROUP BY k.komunitas_id, k.nama_komunitas
                  ORDER BY k.nama_komunitas ASC";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getAvailableForums($user_id)
    {
        $db = self::getConnection();
        $query = "SELECT k.*, k.nama_komunitas,
                         COUNT(DISTINCT a.user_id) as members
                  FROM forum_komunitas k
                  LEFT JOIN forum_anggota a ON k.komunitas_id = a.komunitas_id
                  WHERE k.komunitas_id NOT IN (
                      SELECT komunitas_id FROM forum_anggota WHERE user_id = :user_id
                  )
                  GROUP BY k.komunitas_id
                  ORDER BY k.nama_komunitas ASC
                  LIMIT 10";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getGroups($komunitas_id)
    {
        $db = self::getConnection();
        $query = "SELECT g.*, 
                         COUNT(DISTINCT msg.message_id) as message_count,
                         MAX(msg.created_at) as last_message_time,
                         (SELECT message_text FROM forum_messages 
                          WHERE group_id = g.group_id 
                          ORDER BY created_at DESC LIMIT 1) as last_message
                  FROM forum_groups g
                  LEFT JOIN forum_messages msg ON g.group_id = msg.group_id
                  WHERE g.komunitas_id = :komunitas_id
                  GROUP BY g.group_id, g.name, g.icon, g.komunitas_id, g.created_at, g.image_url
                  ORDER BY g.created_at ASC";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function createGroup($komunitas_id, $name, $icon = 'hash')
    {
        $db = self::getConnection();
        $query = "INSERT INTO forum_groups (komunitas_id, name, icon, created_at) 
                  VALUES (:komunitas_id, :name, :icon, NOW())";
        
        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':icon', $icon);
        
        return $stmt->execute();
    }

    public static function getGroupById($group_id)
    {
        $db = self::getConnection();
        $query = "SELECT * FROM forum_groups WHERE group_id = :group_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function updateGroup($group_id, $name, $image_url = null)
    {
        $db = self::getConnection();
        
        if ($image_url !== null) {
            $query = "UPDATE forum_groups SET name = :name, image_url = :image_url WHERE group_id = :group_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':image_url', $image_url);
            $stmt->bindParam(':group_id', $group_id);
        } else {
            $query = "UPDATE forum_groups SET name = :name WHERE group_id = :group_id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':group_id', $group_id);
        }
        
        return $stmt->execute();
    }

    public static function createKomunitas($name, $description, $imageUrl, $status, $tags)
    {
        $db = self::getConnection();
        $query = "INSERT INTO forum_komunitas (nama_komunitas, deskripsi, image_url, status, tags, created_at, updated_at) 
                  VALUES (:name, :description, :image_url, :status, :tags, NOW(), NOW())";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_url', $imageUrl);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':tags', $tags);

        if ($stmt->execute()) {
            return $db->lastInsertId();
        }
        return false;
    }

    public static function getMembers($komunitas_id, $limit = 3)
    {
        $db = self::getConnection();
        $query = "SELECT u.username 
                  FROM forum_anggota fa
                  JOIN users u ON fa.user_id = u.user_id
                  WHERE fa.komunitas_id = :komunitas_id
                  ORDER BY fa.joined_at DESC
                  LIMIT :limit";

        $stmt = $db->prepare($query);
        $stmt->bindParam(':komunitas_id', $komunitas_id);
        $stmt->bindValue(':limit', (int)$limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}