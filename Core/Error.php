<?php
/**
 * Error and exception handler
 *
 * Requiere PHP8.2
 * 
 * Desarrolla JARS Costa Rica
 * www.jarscr.com
 * Telefono: 4000-2528
 * 
 * Programador: Alfredo Rodriguez
 * 
 **/
namespace Core;


class Error
{

    /**
     * Error handler. Convert all errors to Exceptions by throwing an ErrorException.
     *
     * @param int $level  Error level
     * @param string $message  Error message
     * @param string $file  Filename the error was raised in
     * @param int $line  Line number in the file
     *
     * @return void
     */
    public static function errorHandler($level, $message, $file, $line)
    {
        if (error_reporting() !== 1) {  // to keep the @ operator working
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Exception handler.
     *
     * @param Exception $exception  The exception
     *
     * @return void
     */
    public static function exceptionHandler($exception)
    {
        \Sentry\init(['dsn' => 'https://XXXXXX.ingest.sentry.io/6466576','traces_sample_rate' => 1.0,'max_breadcrumbs' => 50,'environment' => 'development','release' => 'teo@1.1.1' ]);
        // Code is 404 (not found) or 500 (general error)
        $code = $exception->getCode();
        if ($code != 404) {
            $code = 500;
        }
        http_response_code($code);

        if (\App\Config::SHOW_ERRORS) {
            echo "<h1>Fatal error</h1>";
            echo "<p>Uncaught exception: '" . get_class($exception) . "'</p>";
            echo "<p>Message: '" . $exception->getMessage() . "'</p>";
            echo "<p>Stack trace:<pre>" . $exception->getTraceAsString() . "</pre></p>";
            echo "<p>Thrown in '" . $exception->getFile() . "' on line " . $exception->getLine() . "</p>";
            \Sentry\captureException($exception);
        } else {
            $log = dirname(__DIR__) . '/logs/' . date('Y-m-d') . '.txt';
            ini_set('error_log', $log);
            \Sentry\captureException($exception);
            $message = "Uncaught exception: '" . get_class($exception) . "'";
            $message .= " with message '" . $exception->getMessage() . "'";
            $message .= "\nStack trace: " . $exception->getTraceAsString();
            $message .= "\nURL: ".$_SERVER['QUERY_STRING'];
            $message .= "\nThrown in '" . $exception->getFile() . "' on line " . $exception->getLine();
            error_log($message);

            $Parametros = array("message"=>$message);
            View::renderTemplate("$code.html",$Parametros);
        }
    }
}

