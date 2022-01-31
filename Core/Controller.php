<?php

namespace Core;

use Exception;

/**
 * Base controller
 *
 * PHP version 5.4
 */

abstract class Controller
{
    /**
     * Parameters from the matched route
     *
     * @var array
     */
    protected array $route_params = [];

    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     * @return void
     */
    public function __construct($route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * __call is executed when an undefined, or private method is called
     *
     * @param $name
     * @param $args
     * @throws Exception
     */
    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)){
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new Exception("Method $method not found in controller" . get_class($this));
        }
    }

    /**
     * Before filter - called after an action method
     */
    protected function before()
    {
    }

    /**
     * After filter - called after an action method
     */
    protected function after()
    {
    }


}