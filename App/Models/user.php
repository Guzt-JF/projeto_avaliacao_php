<?php
// define o namespace como App\Models
namespace App\Models;

// usa o database como extenção da clasese
use App\Core\Database;
// Usa o PDO para definir o tipo de objeto a ser retornado após o fetch
use PDO;
// Usa o throwable para pegar todos os tipos de erros lançados
use Throwable;

// Classe para fazer requisições pra tabela de Usuários
class User extends Database
{
  public function __construct()
  {
    parent::__construct();
    // Verifica se a conexão com o banco foi estabelecida, caso não, retorna a conn vazia
    if($this->conn == null){
      return;
    }
  }
  // função para pegar os dados por email
  public function getByEmail(array $params){
    try{
      // Variavel para armazenar os valores que serão enviados na request
      $binds = [];

      // Script para fazer o select
      $sql = "SELECT id_user, name, email, password, created_at, update_at, ativo FROM user
      WHERE email = :email AND ativo = true";
      $binds[':email'] = $params['email']; 

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // Roda por cada item armazenado no bind para definir os valores a ser enviados
      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      // Executa a query
      $query->execute();

      // Retorna os dados da query
      $usuario = $query->fetch(PDO::FETCH_ASSOC);

      // em caso de sucesso retorna os dados ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $usuario ? $usuario : []];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => []];
    }
  }
  
  // função para Cadastrar novo usuário
  public function insertUser(array $params){
    try{
      // Script para fazer o insert
      $sql = "INSERT INTO user(name, email, password, ativo)
        VALUES(:name,:email,:password,true)";

      // Variavel para armazenar os valores que serão enviados na request
      $binds = [
        ':name'     => $params['username'],
        ':email'    => $params['email'],
        ':password' => $params['password'],
      ];

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // Roda por cada item armazenado no bind para definir os valores a ser enviados
      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      // Executa a query
      $query->execute();

      // armazena o ultimo id cadastrado
      $insertedId = $this->conn->lastInsertId();

      // em caso de sucesso retorna o id recem cadastrado
      return ['erro' => 0, 'msg' => 'Sucesso ao cadastrar dados', 'data' => ['insertedId' => $insertedId]];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao cadastrar dados - '.$err->getMessage(), 'data' => []];
    }
  }
}