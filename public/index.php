<?php


/**
 * Front Controller
 *
 * PHP Version 8
 */

//echo "Requested URL = " . $_SERVER["QUERY_STRING"];

// Require the controller class
// legacy!! require '../App/Controllers/Posts.php';


use Core\Router;

require_once '../vendor/autoload.php'; // composer autoloader

/**
 * Autoloader
 *
 * LEGACY!!
 *
 */
/*spl_autoload_register(function ($class){
    $root = dirname(__DIR__); // get the parent directory
    $file = $root . '/' . str_replace('.\\', '/', $class) . '.php';
    if (is_readable($file)){
       require $file;
    }
});*/

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
// legacy!! require '../Core/Router.php';

$router = new Router();

//echo get_class($router);

// Add Routes
$router->add('', ['controller' => 'home', 'action' => 'index']);
//$router->add('posts', ['controller' => 'posts', 'action' => 'index']);
//$router->add('posts/new', ['controller' => 'posts', 'action' => 'new']);
$router->add('{controller}/{action}');
$router->add('{controller}/{id:\d+}/{action}');
$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);

/*
// Display the routing table
echo '<pre>';
//var_dump($router->getRoutes());
echo htmlspecialchars(print_r($router->getRoutes(), true));
echo '</pre>';

$url = $_SERVER['QUERY_STRING'];

if ($router->match($url)) {
    echo '<pre>';
    var_dump($router->getParams());
    echo '</pre>';
} else {
    echo "No Rout found for URL: " . $url;
}
*/
$router->dispatch($_SERVER['QUERY_STRING']);