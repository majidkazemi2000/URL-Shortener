<?php


namespace App\Validator;


use Respect\Validation\Validator;

class LinkValidator
{
    public static function link($url){
        return Validator::stringType()->length(6,512)->validate($url);
    }

}