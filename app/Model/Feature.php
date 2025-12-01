<?php

namespace App\Model;

use App\Config\Database;

class Feature
{
    public static function getAll()
    {
        try {
            $db = Database::getConnection('prod');
            $stmt = $db->query("SELECT * FROM features ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Features table error: " . $e->getMessage());
            return [];
        }
    }
}
