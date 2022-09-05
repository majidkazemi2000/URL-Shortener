<?php


namespace App\Config;


class Controller
{
    public function redirect($responseCode,$type,$data){
        header('Content-Type: application/json; charset=utf-8');
        header("HTTP/1.0 {$responseCode} {$type}");
        echo json_encode($data,JSON_FORCE_OBJECT);
        exit();
    }
}