<?php 

namespace App\Core;

use PDO;
use PDOException;


class Database {
  public ?PDO $conn;

  public function __construct() {
    try {
      $config = require __DIR__ . '/../Config/database.php';

      $this->conn = new PDO("mysql:host=".$config['host']."port=".$config['port'].";dbname=".$config['dbname'], $config['username'], $config['password']);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      echo 'Erro ao Conectar no banco de dados: ' . $e->getMessage();
    }
  }
}