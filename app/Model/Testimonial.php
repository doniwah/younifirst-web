<?php

namespace App\Model;

use App\Config\Database;

class Testimonial
{
    public static function getAll()
    {
        try {
            $db = Database::getConnection('prod');
            $stmt = $db->query("SELECT * FROM testimonials ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            // Return empty array if table doesn't exist
            error_log("Testimonials table error: " . $e->getMessage());
            return [];
        }
    }
}
