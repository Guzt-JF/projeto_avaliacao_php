<?php
// Define o namespace como App\Models
namespace App\Models;

// Usa o database como extenção da clasese
use App\Core\Database;
// Usa o PDO para definir o tipo de objeto a ser retornado após o fetch
use PDO;
// Usa o throwable para pegar todos os tipos de erros lançados
use Throwable;

// Classe para fazer requisições pra tabela de serviços
class Service extends Database
{
  // função para pegar os dados num geral
  public function get(array $params){
    try{
      // Variavel para armazenar os valores que serão enviados na request
      $binds = [];

      // Script para fazer o SELECT
      $sql = "SELECT s.*, u.name as user_name, u.email FROM service s
        INNER JOIN user u ON u.id_user = s.user_id_user
        WHERE 1=1";

      // Caso o Id seja definido adiciona ele ao script e ao bind
      if(isset($params['id']) && !empty($params['id'])){
        $sql .= " AND s.id_service = :id";
        $binds[':id'] = $params['id']; 
      }

      // Caso a descrição seja definida adiciona ele ao script e ao bind
      if(isset($params['description']) && !empty($params['description'])){
        $sql .= " AND LOWER(s.description) LIKE LOWER(:description)";
        $binds[':description'] = "%".$params['description']."%"; 
      }

      // Caso o status seja definido adiciona ele ao script e ao bind
      if(isset($params['status']) && !empty($params['status'])){
        if($params['status'] == 1){
          $sql .= " AND s.finished_at IS NULL";
        }
        else{
          $sql .= " AND s.finished_at IS NOT NULL";
        }
      }
      
      // Caso o start_date seja definido adiciona ele ao script e ao bind
      if(isset($params['start_date']) && !empty($params['start_date'])){
        $sql .= " AND s.created_at >= :start_date";
        $binds[':start_date'] = $params['start_date']; 
      }

      // Caso o end_date seja definido adiciona ele ao script e ao bind
      if(isset($params['end_date']) && !empty($params['end_date'])){
        $sql .= " AND s.created_at <= :end_date";
        $binds[':end_date'] = $params['end_date']; 
      }

      // Caso o username seja definido adiciona ele ao script e ao bind
      if(isset($params['username']) && !empty($params['username'])){
        $sql .= " AND LOWER(u.name) = LOWER(:username)";
        $binds[':username'] = $params['username']; 
      }

      // Adiciona ordenação a query
      $sql .= " ORDER BY s.id_service DESC";

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // Roda por cada item armazenado no bind para definir os valores a ser enviados
      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      // Executa a query
      $query->execute();

      // Retorna os dados da query
      $servicos = $query->fetchAll(PDO::FETCH_ASSOC);

      // em caso de sucesso retorna os dados ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $servicos];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => []];
    }
  }

  // Função para pegar os 3 mais recentes, tanto entre os finalizados quanto entre os pendentes
  // a função até permite ambos, mas eu não uso essa opção, apenas por escolha mesmo 
  public function getLastThree(array $params){
    try{
      // Script para fazer o SELECT
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

      $sql .= " ORDER BY s.finished_at DESC
      LIMIT 3";

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);
      
      // armazena o bind pro id
      $query->bindValue(':id', $params['id_user']);

      // Executa a query
      $query->execute();
      
      // Retorna os dados da query
      $servicos = $query->fetchAll(PDO::FETCH_ASSOC);

      // em caso de sucesso retorna os dados ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $servicos];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => []];
    }
  }

  // Função para pegar o totol que um usuário rendeu com os serviços finalizados
  public function getTotalUser($params = []){
    try{
      // Script para fazer o SELECT
      $sql = "SELECT SUM(price) as total FROM service s
        WHERE user_id_user = :id
        AND finished_at IS NOT NULL";

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // armazena o bind pro id
      $query->bindValue(':id', $params['id_user']);

      // Executa a query
      $query->execute();

      // Retorna os dados da query
      $data = $query->fetch(PDO::FETCH_ASSOC);

      // em caso de sucesso retorna os dados ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $data['total']];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => 0];
    }
  }

  // Função para pegar o totol que um usuário rendeu de commissões para si mesmo
  // com seus serviços finalizados
  public function getTotalCommission($params = []){
    try{
      // Script para fazer o SELECT
      $sql = "SELECT SUM(commission_user) as total FROM service s
        WHERE user_id_user = :id
        AND finished_at IS NOT NULL";

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // armazena o bind pro id
      $query->bindValue(':id', $params['id_user']);

      // Executa a query
      $query->execute();

      // Retorna os dados da query
      $data = $query->fetch(PDO::FETCH_ASSOC);

      // em caso de sucesso retorna os dados ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $data['total']];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao retornar dados - '.$err->getMessage(), 'data' => 0];
    }
  }

  // Função para inserir dados no banco de serviços
  public function insert(array $params){
    try{
      // Script para fazer o INSERT
      $sql = "INSERT INTO service(description, price, user_id_user)
        VALUES(:description, :price, :user_id_user)";

      // Variavel para armazenar os valores que serão enviados na request
      $binds = [
        ':description'     => $params['description'],
        ':price'           => $params['price'],
        ':user_id_user'    => $params['id_user'],
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

  // Função para atualizar dados no banco de serviços
  public function update(array $params){
    try{
      // Script para fazer o UPDATE
      $sql = "UPDATE service
      SET description = :description,
      price = :price
      WHERE id_service = :id
      AND user_id_user = :id_user
      AND finished_at IS NULL";

      // Variavel para armazenar os valores que serão enviados na request
      $binds = [
        ':id'              => $params['id'],
        ':id_user'         => $params['id_user'],
        ':description'     => $params['description'],
        ':price'           => $params['price'],
      ];

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // Roda por cada item armazenado no bind para definir os valores a ser enviados
      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      // Executa a query
      $query->execute();

      // caso nenhum item tenha sido alterado retorne uma mensagem de erro ao usuário
      if ($query->rowCount() == 0) {
        return ['erro' => 1, 'msg' => 'Não foi possivel alterar nenhum registro', 'data' => []];
      } 

      // em caso de sucesso retorna uma mensagem ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao atualizar o serviço', 'data' => []];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao atualizar o serviço - '.$err->getMessage(), 'data' => []];
    }
  }

  // Função para deletar dados no banco de serviços
  public function delete(array $params){
    try{
      // Script para fazer o DELETE
      $sql = "DELETE FROM service
              WHERE id_service = :id
              AND user_id_user = :id_user
              AND finished_at IS NULL";

      // Variavel para armazenar os valores que serão enviados na request
      $binds = [
        ':id'      => $params['id'],
        ':id_user' => $params['id_user'],
      ];

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // Roda por cada item armazenado no bind para definir os valores a ser enviados
      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }

      // Executa a query
      $query->execute();

      // caso nenhum item tenha sido alterado retorne uma mensagem de erro ao usuário
      if ($query->rowCount() == 0) {
        return ['erro' => 1, 'msg' => 'Não foi possivel excluir nenhum registro', 'data' => []];
      } 

      // em caso de sucesso retorna uma mensagem ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao deletar serviço', 'data' => []];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao deletar serviço - '.$err->getMessage(), 'data' => []];
    }
  }

  // Função para finalizar um serviço
  public function finish(array $params){
    try{
      // Script para fazer o UPDATE
      $sql = "UPDATE service
      SET finished_at = now(),
      commission_user = :commission_user
      WHERE id_service = :id
      AND user_id_user = :id_user
      AND finished_at IS NULL";

      // Variavel para armazenar os valores que serão enviados na request
      $binds = [
        ':id'              => $params['id'],
        ':id_user'         => $params['id_user'],
        ':commission_user' => $params['commission'],
      ];

      // prepara a query para a execução
      $query = $this->conn->prepare($sql);

      // Roda por cada item armazenado no bind para definir os valores a ser enviados
      foreach($binds as $key => $bind){
        $query->bindValue($key, $bind);
      }
      
      // Executa a query
      $query->execute();

      // caso nenhum item tenha sido alterado retorne uma mensagem de erro ao usuário
      if ($query->rowCount() == 0) {
        return ['erro' => 1, 'msg' => 'Não foi possivel finalizar nenhum registro', 'data' => []];
      } 

      // em caso de sucesso retorna uma mensagem ao usuário
      return ['erro' => 0, 'msg' => 'Sucesso ao finalizar serviço', 'data' => []];
    }
    catch(Throwable $err){
      // em caso de falha retorna mensagem de erro ao usuário
      return ['erro' => 1, 'msg' => 'Erro ao finalizar serviço - '.$err->getMessage(), 'data' => []];
    }
  }

}