<?php

declare(strict_types=1);
namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class Post extends Model
{
    /**
     * Get all Data from Table
     *
     * @return array|false
     */
    public static function getAll(): bool|array
    {
        $db = static::getDB();
        $stmt = $db->query("SELECT id, title, content FROM posts ORDER BY created_at");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}