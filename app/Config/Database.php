<?php

namespace App\Config;

use Error;
use mysqli;

class Database{
    public static $connection = null;
    public function __construct()
    {
        if(self::$connection == null){
            self::$connection = new mysqli(
                $_ENV['DATABASE_HOST'],
                $_ENV['DATABASE_USERNAME'],
                $_ENV['DATABASE_PASSWORD'],
                $_ENV['DATABASE_NAME']
            );
            if (self::$connection->connect_errno) {
                throw new Error("failed to connection with database.");
                exit();
            }
            self::$connection->set_charset('utf8');

        }
    }


    public static function getConnection()
    {
        return self::$connection;
    }
}