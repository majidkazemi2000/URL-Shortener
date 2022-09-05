<?php


namespace App\Helpers;


use App\Controllers\ErrorController;

class Url
{
    public static function redirectToUrl($link)
    {
        if ($link === null){
            ErrorController::pageNotFound();
            exit();
        }
        header("Location:{$link}");
        exit();
    }

    public static function createShortLink($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}