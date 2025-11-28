<?php

namespace App\Model;

use App\Config\Database;

class Testimonial
{
    public static function getAll()
    {
        $db = Database::getConnection('prod');
        $stmt = $db->query("SELECT * FROM testimonials ORDER BY id ASC");
        return $stmt->fetchAll();
    }
}
