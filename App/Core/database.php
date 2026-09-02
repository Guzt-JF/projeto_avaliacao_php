<?php 

// define o namespace como App\Core
namespace App\Core;

// usa o PDO para estabelece a conexão com o banco de dados e fazer as requisições
use PDO;

// usa o Throwable para capturar erros da conexão com o banco de dados
use Throwable;

// clase de database, basicamente cuida da conexão com o banco de dados do sistema
class Database {
  // define a conexão como publica para poder ser invocado pelas classes que herdam da database
  public ?PDO $conn;

  public function __construct() {
    try {
      // importa os dados da conexão de um arquivo php
      $config = require BASE_PATH . '/App/Config/database.php';

      // estabelece uma conexão com o banco de dados
      $this->conn = new PDO("mysql:host=".$config['host'].";port=".$config['port'].";dbname=".$config['dbname'], $config['username'], $config['password']);
      // Configura o banco para lançar um erro em caso de falha da requisição
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Throwable $e) {
      // retorna a conexão vazia em caso de erro
      $this->conn = null;
    }
  }
}