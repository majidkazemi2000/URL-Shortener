<?php


namespace App\Controllers;

use App\Config\Controller;
use App\Config\Request;
use App\models\User;
use App\Validator\UserValidator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use http\Exception\UnexpectedValueException;

class AuthController extends Controller
{
    private $user = null;

    public function __construct()
    {
        $this->user = new User();
    }
    public function isAuthenticated()
    {
        $token = $this->getBearerToken();
        if(is_null($token)){
            return false;
        }
        try {
            $tokenData = JWT::decode($token, new Key($_ENV['JWT_KEY'], $_ENV['JWT_ALGO']));
            $email = $tokenData->userName;
            if($this->user->isUserExists($email)) {
                return true;
            }
            return false;
        }catch (\Exception $err){
            return false;
        }
    }
    public function login(){

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if(!UserValidator::login($username,$password)){
            return ErrorController::pageNotFound();
        }

        if($this->isAuthenticated()){
            return ErrorController::duplicateLogin();
        }

        $user = $this->user->attempt($username,$password);
        if($user === null){
            return ErrorController::loginError();
        }

        $this->redirect(201,'Created',["token"=>$this->createToken($username)]);
    }

    public function register()
    {
        $name = $_POST['name'] ?? null;
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;

        if(!UserValidator::register($name,$username,$password)){
            return ErrorController::pageNotFound();
        }

        if($this->isAuthenticated($username)){
            return ErrorController::duplicateLogin();
        }

        if($this->user->isUserExists($username)){
            return ErrorController::duplicateRegister();
        }

        if($this->user->create($name,$username,$password)){
            $this->redirect(201,'OK',["message"=>"user created successfully"]);
        }
    }
    private function createToken($username){
        $date = new \DateTimeImmutable();
        $expireAt= $date->modify('+60 minutes')->getTimestamp();
        $serverName = $_ENV['BASE_URL'];
        $jwt_data = [
            'iat'  => $date->getTimestamp(),
            'iss'  => $serverName,
            'nbf'  => $date->getTimestamp(),
            'exp'  => $expireAt,
            'userName' => $username,
        ];
        $token = JWT::encode(
            $jwt_data,
            $_ENV['JWT_KEY'],
            $_ENV['JWT_ALGO']
        );

        return $token;
    }
    private function getBearerToken() {
        $headers = getallheaders()["Authorization"] ?? null;
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }


}