<?php

declare(strict_types=1);

namespace App\Models;

use Core\Model;

/**
 * Example user model
 */
class User extends Model
{
    /**
     * Get all users as an associative array.
     *
     * @return list<array<string, mixed>>
     */
    public static function getAll(): array
    {
        // Demo data when the database is not available (e.g. CI without DB).
        // Prefer prepared statements when querying:
        // $db = static::getDB();
        // $stmt = $db->query('SELECT id AS user_id, username FROM users');
        // return $stmt->fetchAll();

        return [
            [
                'user_id' => 1,
                'username' => 'Demo',
            ],
        ];
    }

    /**
     * @return array{lang: string}
     */
    public static function getLangAll(): array
    {
        return static::getLang();
    }

    /**
     * @return list<array{title: string, router: string, icon: string}>
     */
    public static function getAllModules(): array
    {
        return static::getModules();
    }

    /**
     * @return array{year: string, version: string, name: string, development: string, description: string, keywords: string}
     */
    public static function getAllCredits(): array
    {
        return static::getCredits();
    }
}
