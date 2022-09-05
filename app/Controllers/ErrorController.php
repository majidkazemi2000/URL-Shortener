<?php
namespace App\Controllers;

class ErrorController{
    public static function pageNotFound(){
        static::redirect(404,"Not Found","چنین صفحه ای وجود ندارد");
    }
    public static function notAuthorized(){
        static::redirect(401,"Unauthorized","برای دسترسی به این آدرس احراز هویت کنید");
    }
    public static function loginError(){
        static::redirect(401,"Unauthorized","نام کاربری یا رمز عبور اشتباه است");
    }

    public static function jwtTokenError(){
        static::redirect(401,"Unauthorized","توکن احراز هویت نامعتبر است");
    }

    public static function duplicateLogin(){
        static::redirect(405,"Method Not Allowed","شما در سایت احراز هویت شده اید");
    }

    public static function duplicateRegister(){
        static::redirect(405,"Method Not Allowed","این کاربر قبلا در سایت ثبت شده است");
    }

    public static function urlInvalid(){
        static::redirect(400,"Bad Request","ورودی URL نامعتبر است");
    }
    public static function storeLinkFailed(){
        static::redirect(406,"Not Acceptable","ذخیره لینک ناموفق بود");
    }

    public static function updateLinkFailed()
    {
        static::redirect(406,"Not Acceptable","ویرایش لینک ناموفق بود");
    }

    public static function deleteLinkFailed()
    {
        static::redirect(406,"Not Acceptable","حذف لینک ناموفق بود");
    }
    private static function redirect($errorCode,$type,$message){
        header('Content-Type: application/json; charset=utf-8');
        header("HTTP/1.0 {$errorCode} {$type}");
        echo json_encode(array('message' => $message),JSON_FORCE_OBJECT);
        exit();
    }
}
