<?php
namespace App\Config;

use Error;

class Request{
    public $args = [];
    public function __construct($args)
    {
        $this->args = $args;
    }

    public function __get($name)
    {
        if(!array_key_exists($name,$this->args)){
            throw new Error("property {$name} not found");
        }
        return $this->args[$name];
    }
}