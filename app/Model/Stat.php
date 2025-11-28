<?php

namespace App\Model;

use App\Config\Database;

class Stat
{
    public static function getAll()
    {
        $db = Database::getConnection('prod');
        $stmt = $db->query("SELECT * FROM stats ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}
