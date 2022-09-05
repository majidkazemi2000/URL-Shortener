<?php
namespace App\Config;

use App\Controllers\ErrorController as ErrorController;
use App\Config\Request as Request;
use Error;
class Route{
    const methods = ['get','post','put','delete'];
    private static $getRoutes = [];
    private static $postRoutes = [];
    private static $putRoutes = [];
    private static $deleteRoutes = [];

    public static function __callStatic($name, $arguments)
    {
        if(!in_array($name,self::methods)){
            throw new Error("method not defined in routes");
        }
        self::${$name."Routes"}[$arguments[0]] = $arguments[1];
    }   

    public function dispatch(){
        $matchRoute = $this->matchRoute();
        if(count($matchRoute) === 0){
            ErrorController::pageNotFound();
        }
        $classParam = explode('@',$matchRoute[0])[0];
        $class = "App\Controllers\\{$classParam}";
        $method = explode('@',$matchRoute[0])[1];
        if(!class_exists($class)){
            throw new Error("class {$class} not exists");
        }
        if(!method_exists($class,$method)){
            throw new Error("method {$method} not exists");
        }

        $controller = new $class();
        $controller->$method(new Request($matchRoute[1]));
    }
    public function matchRoute(){

        $url = $this->getUrl();
        $httpMethod = strtolower($_SERVER['REQUEST_METHOD']) ?? 'get';

        foreach(self::${$httpMethod."Routes"} as $route => $action){
            $pattern = "@^" . preg_replace('/:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', $route) . "$@D";
            
            $params = [];

            if(preg_match($pattern, $url, $params)){

                $keys = [];
                $paramKey = [];
                preg_match_all("/:([0-9a-zA-Z])\w+/",$route,$keys);
                foreach($keys[0] as $key){
                    $key = substr($key,1);
                    array_push($paramKey,$key);
                }

                $paramsArray = [];

            
                array_shift($params);

                foreach($params as $index => $param){
                    $paramsArray[$paramKey[$index]] = $param;
                }
                return [$action, $paramsArray];
            }

        }
        return [];
    }

    private function getUrl(){
        $url = $_SERVER['REQUEST_URI'] ?? '/';
        $url = substr($url,5);
        if($url !== '/'){
            $url = rtrim($url,'/');
        }
        return $url;
    }
}