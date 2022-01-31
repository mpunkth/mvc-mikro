<?php

namespace Core;

use App\Config;
use PDO;
use PDOException;

abstract class Model
{
    /**
     * Get the PDO database Connection
     *
     * @return PDO|null
     */
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $host = Config::DB_HOST;
            $dbname = Config::DB_NAME;
            $username = Config::DB_USER;
            $password = Config::DB_PASSWORD;
            try {
                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
                $db = new PDO($dsn, $username, $password);

                //Throw an exception when an error occurs
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        return $db;
    }

}