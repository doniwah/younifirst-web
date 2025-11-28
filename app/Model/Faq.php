<?php

namespace App\Model;

use App\Config\Database;

class Faq
{
    public static function getAll()
    {
        $db = Database::getConnection('prod');
        $stmt = $db->query("SELECT * FROM faqs ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}
