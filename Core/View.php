<?php

namespace Core;


use Exception;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

/**
 * View Class
 */

class View
{
    /**
     * Render a view file
     *
     * @param $view
     * @param array $args

     * @throws Exception
     */
    public static function render($view, array $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view"; // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     * @param $template
     * @param array $args
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function renderTemplate($template, array $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new FilesystemLoader('../App/Views');
            /*$twig = new \Twig\Environment($loader, [
                'cache' => '../App/Views/twig-cache'
            ]);*/
            $twig = new Environment($loader);
        }
        echo $twig->render($template, $args);
    }
}