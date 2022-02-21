<?php
declare(strict_types=1);
namespace Core;

use Exception;

/**
 * Router
 *
 */
class Router
{
    /**
     * Routing Table inside an associative array
     */
    protected array $routes = [];

    /**
     * Parameters from matched route
     *
     * @var array
     */
    protected array $params = [];

    /**
     * adds a route to routing table
     *
     * @param string $route
     * @param array $params
     */
    public function add(string $route, array $params = [])
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route);

        // Convert variables e.g. {controller}
        $route = preg_replace('/{([a-z0-9-]+)}/', '(?<\1>[a-z0-9-]+)', $route);

        // Convert variables with custom regex e.g. {id:\d+}
        $route = preg_replace('/{([a-z]+):([^}]+)}/', '(?<\1>\2)', $route);;

        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * URl match to routing method
     *
     * @param string $url
     * @return bool
     */
    public function match(string $url): bool
    {
        /* Leftover regex from fixed url structure. it matches /controller/action only
                $reg_ex = '/^(?<controller>[a-z0-9-]+)\/(?<action>[a-z0-9-]+)$/';
        */

        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * returns routing params
     *
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * returns all routes
     *
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Dispatch the route, creating the controller object and running the
     * action method
     *
     * @param string $url The route URL
     *
     * @return void
     * @throws Exception
     */

    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            // substituted by the next line!! $controller = "App\Controllers\\$controller";
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();
                } else {
                    throw new Exception("Method $action (in controller $controller cannot be called directly");
                }
            } else {
                throw new Exception("Controller class $controller not found");
            }
        } else {
            throw new Exception('No route matched', 404);
        }
    }

    /**
     * Convert the string with hyphens to StudlyCaps,
     * e.g. post-authors => PostAuthors
     *
     * @param string $string
     * @return string
     */
    protected function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    /**
     * Convert the string with hyphens to CamelCase,
     * e.g. add-new => addNew
     *
     * @param string $string
     * @return string
     */
    protected function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any). As the full
     * query string is used for the route, any variables at the end will need
     * to be removed before the route is matched to the routing table. For
     * example:
     *
     *   URL                           $_SERVER['QUERY_STRING']  Route
     *   -------------------------------------------------------------------
     *   localhost                     ''                        ''
     *   localhost/?                   ''                        ''
     *   localhost/?page=1             page=1                    ''
     *   localhost/posts?page=1        posts&page=1              posts
     *   localhost/posts/index         posts/index               posts/index
     *   localhost/posts/index?page=1  posts/index&page=1        posts/index
     *
     * A URL of the format localhost/?page (one variable name, no value) won't
     * work however. (NB. The .htaccess file converts the first ? to a & when
     * it's passed through to the $_SERVER variable).
     *
     * @param string $url The full URL
     *
     * @return string The URL with the query string variables removed
     */
    protected function removeQueryStringVariables(string $url): string
    {
        if ($url != '') {

//            echo " incoming url $url  <br>"; //debug

            $parts = explode('&', $url, 2);

            //debug
            /*            foreach ($parts as $key => $part) {
                            echo "remove query sting variable part $key $part <br>";
                        }*/


            if (!str_contains($parts[0], '=')) {
                $url = $parts[0];
            } else {
                $url = ''; // Fallback to Home route ?!
            }
        }
        //echo "outgoing url $url <br>"; // debug
        return $url;
    }

    /**
     * Get the namespace for the controller class. The namespaces defined in the
     * route parameters is added if present.
     *
     * @return string
     */
    protected function getNamespace(): string
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }


}