<?php

namespace Core;

/**
 * View
 *
 * Requiere PHP7.3
 * 
 * Desarrolla JARS Costa Rica
 * www.jarscr.com
 * Telefono: 4000-2528
 * 
 * Programador: Alfredo Rodriguez
 * 
 **/
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Bridge\Twig\Extension\TranslationExtension;

class View
{

    /**
     * Render a view file
     *
     * @param string $view  The view file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     *
     * @return void
     */
    public static function renderTemplate($template, $args = [],$lang)
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);

            $translator = new Translator($lang);
            $translator->addLoader('php',new PhpFileLoader());
            $translator->addResource(
                'php',
                '../Languages/es.php',
                'es'
            );
            $twig->addExtension(new TranslationExtension($translator));
            
        }

        echo $twig->render($template, $args);
    }
}
