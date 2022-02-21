<?php

namespace App\Controllers\Admin;

use Core\Controller;

/**
 * User admin controller
 *
 */
class Users extends Controller
{
    /**
     * Before filter
     */
    protected function before()
    {
        // Make sure an admin user is logged in for example
    }

    /**
     * Show the index page
     */
    public function indexAction()
    {
        echo "User admin index";
    }


}