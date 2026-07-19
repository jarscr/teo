<?php

declare(strict_types=1);

namespace App;

/**
 * Application configuration loaded from environment variables.
 *
 * Copy .env.example to .env and set your values. Real server environment
 * variables take precedence over the .env file.
 */
class Config
{
    private static bool $loaded = false;

    /**
     * Load .env into the process environment once.
     */
    public static function load(): void
    {
        if (self::$loaded) {
            return;
        }

        $envFile = dirname(__DIR__) . '/.env';

        if (is_readable($envFile)) {
            self::parseEnvFile($envFile);
        }

        self::$loaded = true;
    }

    public static function env(string $key, ?string $default = null): ?string
    {
        self::load();

        $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);

        if ($value === false || $value === null || $value === '') {
            return $default;
        }

        return (string) $value;
    }

    public static function bool(string $key, bool $default = false): bool
    {
        $value = self::env($key);

        if ($value === null) {
            return $default;
        }

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public static function dbHost(): string
    {
        return self::env('DB_HOST', '127.0.0.1') ?? '127.0.0.1';
    }

    public static function dbName(): string
    {
        return self::env('DB_NAME', 'teo') ?? 'teo';
    }

    public static function dbUser(): string
    {
        return self::env('DB_USER', 'teo') ?? 'teo';
    }

    public static function dbPassword(): string
    {
        return self::env('DB_PASSWORD', '') ?? '';
    }

    public static function dbCharset(): string
    {
        return self::env('DB_CHARSET', 'utf8mb4') ?? 'utf8mb4';
    }

    public static function version(): string
    {
        return self::env('APP_VERSION', '1.1.1') ?? '1.1.1';
    }

    public static function lang(): string
    {
        $lang = self::env('APP_LANG', 'es') ?? 'es';

        return preg_match('/^[a-z]{2}(?:_[A-Z]{2})?$/', $lang) === 1 ? $lang : 'es';
    }

    public static function showErrors(): bool
    {
        return self::bool('APP_DEBUG', false);
    }

    private static function parseEnvFile(string $path): void
    {
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if ($name === '' || !preg_match('/^[A-Z_][A-Z0-9_]*$/', $name)) {
                continue;
            }

            // Do not override real environment variables
            if (array_key_exists($name, $_ENV) || getenv($name) !== false) {
                continue;
            }

            if (
                (str_starts_with($value, '"') && str_ends_with($value, '"'))
                || (str_starts_with($value, "'") && str_ends_with($value, "'"))
            ) {
                $value = substr($value, 1, -1);
            }

            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
            putenv("$name=$value");
        }
    }
}
