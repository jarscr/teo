<?php

declare(strict_types=1);

namespace Core;

use App\Config;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Component\Translation\Loader\PhpFileLoader;
use Symfony\Component\Translation\Translator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * View
 */
class View
{
    /**
     * Render a PHP view file
     *
     * @param array<string, mixed> $args
     *
     * @throws \Exception
     */
    public static function render(string $view, array $args = []): void
    {
        $file = self::resolveViewPath($view, dirname(__DIR__) . '/App/Views');

        extract($args, EXTR_SKIP);

        require $file;
    }

    /**
     * Render a view template using Twig
     *
     * @param array<string, mixed> $args
     *
     * @throws \Exception
     */
    public static function renderTemplate(string $template, array $args = [], ?string $lang = null): void
    {
        $template = str_replace(['\\', "\0"], ['/', ''], $template);
        $template = ltrim($template, '/');

        if ($template === '' || str_contains($template, '..')) {
            throw new \Exception('Invalid template path');
        }

        $lang ??= Config::lang();
        $twig = self::getTwig($lang);

        echo $twig->render($template, $args);
    }

    private static function getTwig(string $lang): Environment
    {
        static $twig = null;
        static $currentLang = null;

        if ($twig !== null && $currentLang === $lang) {
            return $twig;
        }

        $viewsPath = dirname(__DIR__) . '/App/Views';
        $loader = new FilesystemLoader($viewsPath);

        $twig = new Environment($loader, [
            'debug' => Config::showErrors(),
            'autoescape' => 'html',
            'strict_variables' => Config::showErrors(),
        ]);

        $translator = new Translator($lang);
        $translator->addLoader('php', new PhpFileLoader());

        $languagesPath = dirname(__DIR__) . '/App/Languages';

        foreach (['es', 'en'] as $locale) {
            $file = $languagesPath . '/' . $locale . '.php';

            if (is_readable($file)) {
                $translator->addResource('php', $file, $locale);
            }
        }

        $twig->addExtension(new TranslationExtension($translator));

        $currentLang = $lang;

        return $twig;
    }

    /**
     * Resolve a view path and reject directory traversal.
     *
     * @throws \Exception
     */
    private static function resolveViewPath(string $view, string $basePath): string
    {
        $view = str_replace(['\\', "\0"], ['/', ''], $view);
        $view = ltrim($view, '/');

        if ($view === '' || str_contains($view, '..')) {
            throw new \Exception('Invalid view path');
        }

        $baseReal = realpath($basePath);

        if ($baseReal === false) {
            throw new \Exception('Views directory not found');
        }

        $file = $baseReal . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $view);
        $realFile = realpath($file);

        if ($realFile === false || !str_starts_with($realFile, $baseReal . DIRECTORY_SEPARATOR)) {
            throw new \Exception("$view not found");
        }

        if (!is_readable($realFile)) {
            throw new \Exception("$view not found");
        }

        return $realFile;
    }
}
