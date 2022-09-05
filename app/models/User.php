<?php


namespace App\models;


use App\Config\Model;
use App\Controllers\ErrorController;

class User extends Model
{
    private $tableName = 'users';

    public function isUserExists($username)
    {
        $this->connection->begin_transaction();
        try{
            $stmt = $this->connection->prepare("SELECT * FROM {$this->tableName} WHERE email=?");
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $this->connection->commit();
            $result = $stmt->get_result()->fetch_assoc();
            if(!is_null($result)){
                return true;
            }
            return false;
        }catch(\mysqli_sql_exception $exception){
            $this->connection->rollback();
            return false;
        }

    }

    public function attempt($username,$password)
    {
        $this->connection->begin_transaction();
        try {
            $stmt = $this->connection->prepare("SELECT * FROM {$this->tableName} WHERE email=?");
            $stmt->bind_param("s",$username);
            $stmt->execute();
            $this->connection->commit();
            $result = $stmt->get_result()->fetch_assoc();
            if (!is_null($result) && strcmp($result['password'],md5($password)) === 0){
                return $result;
            }
            return null;
        }catch(\mysqli_sql_exception $exception){
            $this->connection->rollback();
            return null;
        }
    }

    public function create($name,$username,$password)
    {
        $this->connection->begin_transaction();
        try{
            $stmt = $this->connection->prepare("INSERT INTO {$this->tableName} (name,email,password) VALUES (?,?,?)");
            $password = md5($password);
            $stmt->bind_param("sss",$name,$username,$password);
            $stmt->execute();
            $this->connection->commit();
            return true;
        }catch(\mysqli_sql_exception $exception){
            $this->connection->rollback();
            return false;
        }
    }
}