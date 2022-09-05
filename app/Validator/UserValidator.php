<?php


namespace App\Validator;


use Respect\Validation\Validator;

class UserValidator
{
    public static function login($username,$password){
        return Validator::email()->validate($username) &&
            Validator::stringType()->length(4,64)->validate($password);
    }
    public static function register($name,$username,$password)
    {
        return Validator::email()->validate($username) &&
            Validator::stringType()->length(2,64)->validate($name) &&
            Validator::stringType()->length(4,64)->validate($password);
    }
}