<?php


namespace App\models;

use App\Config\Model;
use App\Controllers\ErrorController;

class Link extends Model
{
    private $tableName = 'links';

    public function create($shortLink,$url)
    {
        $this->connection->begin_transaction();
        try{
            $stmt = $this->connection->prepare("INSERT INTO {$this->tableName} (`short_link`,`url`) VALUES (?,?)");
            $stmt->bind_param("ss",$shortLink,$url);
            $stmt->execute();
            $this->connection->commit();
            return true;
        }catch (\mysqli_sql_exception $exception){
            $this->connection->rollback();
            return false;
        }
    }
    public function getLinkByShortLink($shortLink){
        $this->connection->begin_transaction();
        try{
            $stmt = $this->connection->prepare("SELECT * FROM {$this->tableName} WHERE short_link=?");
            $stmt->bind_param("s",$shortLink);
            $stmt->execute();
            $this->connection->commit();
            $result = $stmt->get_result()->fetch_assoc();
            return $result;
        }catch(\mysqli_sql_exception $exception){
            $this->connection->rollback();
            return null;
        }
    }
    public function getLinkById($linkId){
        $this->connection->begin_transaction();
        try {
            $stmt = $this->connection->prepare("SELECT * FROM {$this->tableName} WHERE id=?");
            $stmt->bind_param("s", $linkId);
            $stmt->execute();
            $this->connection->commit();
            $result = $stmt->get_result()->fetch_assoc();
            return $result;
        }catch(\mysqli_sql_exception $exception){
            $this->connection->rollback();
            return null;
        }
    }

    public function delete($linkId)
    {
        $this->connection->begin_transaction();
        try {
            $stmt = $this->connection->prepare("DELETE FROM {$this->tableName} WHERE id=?");
            $stmt->bind_param("s", $linkId);
            $stmt->execute();
            $this->connection->commit();
            return true;
        }catch(\mysqli_sql_exception $exception){
            $this->connection->rollback();
            return false;
        }
    }

    public function update($linkId,$newUrl)
    {
        $oldLink = $this->getLinkById($linkId);

        if(!is_null($oldLink)){
            $this->connection->begin_transaction();

            try{
                $stmt = $this->connection->prepare("UPDATE {$this->tableName} SET short_link=?,url=? WHERE id=?");
                $stmt->bind_param("sss",$oldLink['short_link'],$newUrl,$linkId);
                $stmt->execute();
                $this->connection->commit();
                return $oldLink;
            }catch(\mysqli_sql_exception $exception){
                $this->connection->rollback();
                return null;
            }

        }
        return null;
    }

}