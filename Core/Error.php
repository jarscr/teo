<?php

declare(strict_types=1);

namespace Core;

use App\Config;
use Throwable;

/**
 * Error and exception handler
 */
class Error
{
    /**
     * Convert errors to ErrorException. Respects the @ operator.
     *
     * @throws \ErrorException
     */
    public static function errorHandler(
        int $level,
        string $message,
        string $file,
        int $line
    ): bool {
        if ((error_reporting() & $level) === 0) {
            return false;
        }

        throw new \ErrorException($message, 0, $level, $file, $line);
    }

    /**
     * Exception handler.
     */
    public static function exceptionHandler(Throwable $exception): void
    {
        $code = (int) $exception->getCode();

        if ($code !== 404) {
            $code = 500;
        }

        http_response_code($code);

        if (Config::showErrors()) {
            echo '<h1>Fatal error</h1>';
            echo '<p>Uncaught exception: \'' . self::e(get_class($exception)) . '\'</p>';
            echo '<p>Message: \'' . self::e($exception->getMessage()) . '\'</p>';
            echo '<p>Stack trace:<pre>' . self::e($exception->getTraceAsString()) . '</pre></p>';
            echo '<p>Thrown in \'' . self::e($exception->getFile()) . '\' on line '
                . (int) $exception->getLine() . '</p>';
            return;
        }

        $logDir = dirname(__DIR__) . '/logs';

        if (!is_dir($logDir)) {
            mkdir($logDir, 0750, true);
        }

        $log = $logDir . '/' . date('Y-m-d') . '.txt';
        ini_set('error_log', $log);

        $message = "Uncaught exception: '" . get_class($exception) . "'";
        $message .= " with message '" . $exception->getMessage() . "'";
        $message .= "\nStack trace: " . $exception->getTraceAsString();
        $message .= "\nURL: " . ($_SERVER['REQUEST_URI'] ?? '');
        $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
        error_log($message);

        try {
            View::renderTemplate($code . '.html', [
                'credits' => [
                    'year' => date('Y'),
                    'version' => Config::version(),
                    'name' => 'TEO Simple PHP Framework',
                    'development' => 'JARS Costa Rica',
                ],
                'modules' => [],
            ], Config::lang());
        } catch (Throwable) {
            echo $code === 404 ? 'Page not found' : 'An error occurred';
        }
    }

    private static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
