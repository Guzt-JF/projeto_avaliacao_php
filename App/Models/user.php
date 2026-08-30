<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

class User extends Database
{
  public function getAll(array $params){
    try{
      $binds = [];

      $sql = "SELECT id_user, name, email, created_at, update_at, ativo FROM user WHERE 1=1";

      if(isset($params['id']) && !empty($params['id'])){
        $sql .= " AND id_user = :id";
        $binds[':id'] = $params['id']; 
      }
      if(isset($params['email']) && !empty($params['email'])){
        $sql .= " AND email = :email";
        $binds[':email'] = $params['email']; 
      }

      $query = $this->conn->prepare($sql);

      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      $query->execute();
      $usuario = $query->fetchAll(PDO::FETCH_ASSOC);

      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $usuario];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => []];
    }
  }
  public function getByEmail(array $params){
    try{
      $binds = [];

      $sql = "SELECT id_user, name, email, password, created_at, update_at, ativo FROM user WHERE email = :email AND ativo = true";
      $binds[':email'] = $params['email']; 

      $query = $this->conn->prepare($sql);

      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      $query->execute();
      $usuario = $query->fetch(PDO::FETCH_ASSOC);

      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $usuario ? $usuario : []];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => []];
    }
  }
  public function insertUser(array $params){
    try{
      $sql = "INSERT INTO user(name, email, password, ativo)
        VALUES(:name,:email,:password,true)";

      $binds = [
        ':name'     => $params['username'],
        ':email'    => $params['email'],
        ':password' => $params['password'],
      ];

      $query = $this->conn->prepare($sql);

      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      $query->execute();
      $insertedId = $this->conn->lastInsertId();

      return ['erro' => 0, 'msg' => 'Sucesso ao cadastrar dados', 'data' => ['insertedId' => $insertedId]];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao cadastrar dados - '.$err->getMessage(), 'data' => []];
    }
  }
}