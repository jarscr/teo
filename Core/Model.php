<?php

declare(strict_types=1);

namespace Core;

use App\Config;
use PDO;
use PDOException;

/**
 * Base model
 */
abstract class Model
{
    /**
     * Get the shared PDO database connection.
     *
     * @throws PDOException
     */
    protected static function getDB(): PDO
    {
        static $db = null;

        if ($db === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                Config::dbHost(),
                Config::dbName(),
                Config::dbCharset()
            );

            $db = new PDO($dsn, Config::dbUser(), Config::dbPassword(), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        }

        return $db;
    }

    /**
     * @return array{year: string, version: string, name: string, development: string, description: string, keywords: string}
     */
    protected static function getCredits(): array
    {
        static $credits = null;

        if ($credits === null) {
            $credits = [
                'year' => date('Y'),
                'version' => Config::version(),
                'name' => 'TEO Simple PHP Framework',
                'development' => 'JARS Costa Rica',
                'description' => 'TEO Simple PHP Framework for building web applications in PHP',
                'keywords' => 'teo,framework, jarscr, php, free',
            ];
        }

        return $credits;
    }

    /**
     * @return array{lang: string}
     */
    protected static function getLang(): array
    {
        return ['lang' => Config::lang()];
    }

    /**
     * @return list<array{title: string, router: string, icon: string}>
     */
    protected static function getModules(): array
    {
        static $modules = null;

        if ($modules === null) {
            $modules = [
                [
                    'title' => 'Home',
                    'router' => '/',
                    'icon' => 'house-fill',
                ],
                [
                    'title' => 'About Us',
                    'router' => '#',
                    'icon' => 'building',
                ],
            ];
        }

        return $modules;
    }
}
