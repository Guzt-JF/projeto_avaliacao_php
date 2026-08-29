<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class User extends Database
{
  public function listar(array $params){
    $query = $this->conn->prepare("SELECT * FROM user WHERE id_user = :id_user");

    $query->bindValue('id_user', $params['id_user']);
    
    $query->execute();
    $usuario = $query->fetch(PDO::FETCH_OBJ);

    return $usuario;
  }
}