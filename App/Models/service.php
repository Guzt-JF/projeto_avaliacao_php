<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Throwable;

class Service extends Database
{
  public function get(array $params){
    try{
      $binds = [];

      $sql = "SELECT s.*, u.name as user_name FROM service s
        INNER JOIN user u ON u.id_user = s.user_id_user
        WHERE 1=1";

      if(isset($params['id']) && !empty($params['id'])){
        $sql .= " AND s.id_service = :id";
        $binds[':id'] = $params['id']; 
      }

      if(isset($params['description']) && !empty($params['description'])){
        $sql .= " AND LOWER(s.description) LIKE LOWER(:description)";
        $binds[':description'] = "%".$params['description']."%"; 
      }
      if(isset($params['status']) && !empty($params['status'])){
        if($params['status'] == 1){
          $sql .= " AND s.finished_at IS NULL";
        }
        else{
          $sql .= " AND s.finished_at IS NOT NULL";
        }
      }
      if(isset($params['start_date']) && !empty($params['start_date'])){
        $sql .= " AND s.created_at >= :start_date";
        $binds[':start_date'] = $params['start_date']; 
      }
      if(isset($params['end_date']) && !empty($params['end_date'])){
        $sql .= " AND s.created_at <= :end_date";
        $binds[':end_date'] = $params['end_date']; 
      }
      if(isset($params['username']) && !empty($params['username'])){
        $sql .= " AND LOWER(u.name) = LOWER(:username)";
        $binds[':username'] = $params['username']; 
      }

      $sql .= " ORDER BY s.id_service DESC";
      $query = $this->conn->prepare($sql);

      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      $query->execute();
      $servicos = $query->fetchAll(PDO::FETCH_ASSOC);

      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $servicos];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => []];
    }
  }

  public function getLastThree(array $params){
    try{
      $sql = "SELECT s.id_service, s.description FROM service s
        WHERE s.user_id_user = :id";
        
      if(isset($params['finished'])){
        if($params['finished']){
          $sql .= " AND s.finished_at IS NOT NULL";
        }
        else{
          $sql .= " AND s.finished_at IS NULL";
        }
      }

      $sql .= " ORDER BY s.created_at DESC
      LIMIT 3";

      $query = $this->conn->prepare($sql);
      
      $query->bindValue(':id', $params['id_user']);

      $query->execute();
      $servicos = $query->fetchAll(PDO::FETCH_ASSOC);

      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $servicos];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => []];
    }
  }

  public function getTotalUser($params = []){
    try{
      $sql = "SELECT SUM(price) as total FROM service s
        WHERE user_id_user = :id
        AND finished_at IS NOT NULL";

      $query = $this->conn->prepare($sql);
      $query->bindValue(':id', $params['id_user']);
      $query->execute();

      $data = $query->fetch(PDO::FETCH_ASSOC);

      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $data['total']];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => 0];
    }
  }

  public function insert(array $params){
    try{
      $sql = "INSERT INTO service(description, price, commission_user, user_id_user)
        VALUES(:description, :price, :commission_user, :user_id_user)";

      $binds = [
        ':description'     => $params['description'],
        ':price'           => $params['price'],
        ':commission_user' => $params['commision'],
        ':user_id_user'    => $params['id_user'],
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

  public function update(array $params){
    try{
      $sql = "UPDATE service
      SET description = :description,
      price = :price,
      commission_user = :commission_user
      WHERE id_service = :id
      AND user_id_user = :id_user
      AND finished_at IS NULL";

      $binds = [
        ':id'              => $params['id'],
        ':id_user'         => $params['id_user'],
        ':description'     => $params['description'],
        ':price'           => $params['price'],
        ':commission_user' => $params['price'],
      ];

      $query = $this->conn->prepare($sql);

      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      $query->execute();

      if ($query->rowCount() == 0) {
        return ['erro' => 1, 'msg' => 'Não foi possivel alterar nenhum registro', 'data' => []];
      } 

      return ['erro' => 0, 'msg' => 'Sucesso ao atualizar o serviço', 'data' => []];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao atualizar o serviço - '.$err->getMessage(), 'data' => []];
    }
  }

  public function finish(array $params){
    try{
      $sql = "UPDATE service
      SET finished_at = now()
      WHERE id_service = :id
      AND user_id_user = :id_user
      AND finished_at IS NULL";

      $binds = [
        ':id'      => $params['id'],
        ':id_user' => $params['id_user'],
      ];

      $query = $this->conn->prepare($sql);

      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      $query->execute();

      if ($query->rowCount() == 0) {
        return ['erro' => 1, 'msg' => 'Não foi possivel finalizar nenhum registro', 'data' => []];
      } 

      return ['erro' => 0, 'msg' => 'Sucesso ao finalizar serviço', 'data' => []];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao finalizar serviço - '.$err->getMessage(), 'data' => []];
    }
  }

  public function delete(array $params){
    try{
      $sql = "DELETE FROM service
              WHERE id_service = :id
              AND user_id_user = :id_user
              AND finished_at IS NULL";

      $binds = [
        ':id'      => $params['id'],
        ':id_user' => $params['id_user'],
      ];

      $query = $this->conn->prepare($sql);

      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }

      $query->execute();

      if ($query->rowCount() == 0) {
        return ['erro' => 1, 'msg' => 'Não foi possivel excluir nenhum registro', 'data' => []];
      } 

      return ['erro' => 0, 'msg' => 'Sucesso ao deletar serviço', 'data' => []];
    }
    catch(Throwable $err){
      return ['erro' => 1, 'msg' => 'Erro ao deletar serviço - '.$err->getMessage(), 'data' => []];
    }
  }
}