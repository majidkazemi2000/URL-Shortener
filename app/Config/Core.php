<?php
namespace App\Config;

use App\Config\Route as Route;
use App\Routes\Api as Api;

class Core{
    public function __construct()
    {
        $route = new Route();
        $route->dispatch();
    }
    
}