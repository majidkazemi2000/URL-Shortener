<?php
session_start();
require_once('./vendor/autoload.php');
require_once('./app/Routes/api.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

new App\Config\Database();
new App\Config\Core();




