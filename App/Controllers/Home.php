<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Home controller
 *
 */

class Home extends Controller
{
    /**
     * Before Filter
     */
    protected function before()
    {
        echo "(before) ";
//        return false;
    }

    /**
     * After filter
     */
    protected function after()
    {
        echo " (after)";
    }

    /**
     * Show the index page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction()
    {
//        echo 'Hello from the index action in the Home controller!';
//        View::render('Home/index.php', [
//            'name' => 'Dave',
//            'colors' => ['red', 'green', 'blue']
//        ]);

        View::renderTemplate('Home/index.html.twig', [
            'name' => 'Dave',
            'colors' => ['red', 'green', 'blue']
        ]);
    }

}