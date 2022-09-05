<?php

namespace App\Config;


class Model
{
    public $connection;

    public function __construct()
    {
        $this->connection = Database::getConnection();
    }
}