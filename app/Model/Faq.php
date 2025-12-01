<?php

namespace App\Model;

use App\Config\Database;

class Faq
{
    public static function getAll()
    {
        try {
            $db = Database::getConnection('prod');
            $stmt = $db->query("SELECT * FROM faqs ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("FAQs table error: " . $e->getMessage());
            return [];
        }
    }
}
