<?php
// define o namespace como App\Controllers
namespace App\Controllers;

// usa o controller como extenção da clasese
use App\Core\Controller;
use App\Core\Mail;
// usa Datetime para criar uma data para um filtro
use DateTime;

// Classe para a controller responsavel pelas coisas relacionadas ao serviço
class Servicos extends Controller
{
  // função para pegar os dados de serviços num geral
  // essa função pode retornar os dados direto, ou mandar via json
  // fiz assim para facilitar as funções que pegam dados com essa controller
  public function get($to_return = true){
    // Verifica se o metodo da request é GET
    $this->verifyMethod('GET');

    // Importa a model de service
    $service = $this->model('service');

    // armazena os valroes a ser usadoss e garante q os valores existam, mesmo q estejam vazios
    $username = $_GET['username'] ?? '';
    $description = $_GET['description'] ?? '';
    $status = $_GET['status'] ?? '';
    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';
    
    // Caso tenha uma end date, mude ela para incluir o ultimo minuto do dia
    // assim, o filtro inclui registros gravados no dia especificado
    if (!empty($end_date)) {
      $date = DateTime::createFromFormat('Y-m-d', $end_date);

      if ($date !== false) {
        $date->setTime(23, 59, 59);
        $end_date = $date->format('Y-m-d H:i:s');
      }
    }

    // Pega os dados de serviço
    $servicos = $service -> get([
      'username'    => $username,
      'description' => $description,
      'status'      => $status,
      'start_date'  => $start_date,
      'end_date'    => $end_date,
    ]);

    // retorne os dados do jeito especificado
    if($to_return){
      $this->jsonResponse($servicos);
    }
    return $servicos;
  }

  // função para filtrar e já retornar a view da table de serviços
  public function filter(){
    // Verifica se o metodo da request é GET
    $this->verifyMethod('GET');
    $servicos_data = $this->get(false);

    // em caso de falha retorna uma mensagem de erro pro usuário
    if($servicos_data['erro']){
      $this->jsonResponse($servicos_data);
    }
    // caso contrario guarda os dados de serviços e pega os dados da sesion
    $servicos_data = $servicos_data['data'];
    $data = ['id_user' => $_SESSION['id_user']];

    // Realiza o import da table e grava em uma variavel
    ob_start(); 
    require BASE_PATH . 'App/Views/dashboard/_table.php';
    $page = ob_get_clean();

    // retorna a table filtrada
    $this->jsonResponse(['erro' => 0 , 'msg' => 'Tabela retornada com sucesso', 'data' => $page]);
  }

  // Função para atualizar serviços
  public function update(){
    // Verifica se o metodo da request é POST
    $this->verifyMethod('POST');

    // Importa a model de service
    $service = $this->model('service');

    // armazena os valroes a ser usadoss e garante q os valores existam, mesmo q estejam vazios
    $id = $_POST['id'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    // converte o tipo de preço do padrão da moeda brasileira para uma que o mysql entende
    $price = str_replace('.', '', $price);
    $price = (float) str_replace(',', '.', $price);

    // Atualiza o serivço com os dados providenciados
    $servicos = $service -> update([
      'id'          => $id,
      'id_user'     => $_SESSION['id_user'],
      'price'       => $price,
      'description' => $description,
    ]);

    // retorna a resposta do update
    $this->jsonResponse($servicos);
  }

  // Função para cadastrar serviços
  public function insert(){
    // Verifica se o metodo da request é POST
    $this->verifyMethod('POST');
    // Importa a model de service
    $service = $this->model('service');

    // armazena os valroes a ser usadoss e garante q os valores existam, mesmo q estejam vazios
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    // converte o tipo de preço do padrão da moeda brasileira para uma que o mysql entende
    $price = str_replace('.', '', $price);
    $price = (float) str_replace(',', '.', $price);
  
    // Cadastra um novo serviço
    $servicos = $service -> insert([
      'price'       => $price,
      'description' => $description,
      'id_user'     => $_SESSION['id_user']
    ]);

    // retorna a resposta do insert
    $this->jsonResponse($servicos);
  }

  // Função para deletar serviços
  public function delete(){
    // Verifica se o metodo da request é POST
    $this->verifyMethod('POST');
    // Importa a model de service
    $service = $this->model('service');

    // armazena os valroes a ser usadoss e garante q os valores existam, mesmo q estejam vazios
    $id = $_POST['id'] ?? '';

    // Deleta o Serviço
    $servicos = $service -> delete([
      'id'      => $id,
      'id_user' => $_SESSION['id_user']
    ]);

    // retorna a resposta do delete
    $this->jsonResponse($servicos);
  }

  // Função para finalizar serviços
  public function finish(){
    // Verifica se o metodo da request é POST
    $this->verifyMethod('POST');
    // Importa a model de service
    $service = $this->model('service');

    // armazena os valroes a ser usadoss e garante q os valores existam, mesmo q estejam vazios
    $id = $_POST['id'] ?? '';
    
    // Retorna os dados do serviço
    $servico = $service -> get([
      'id' => $id,
    ]);
    
    // caso não encontre nada retorne uma mensagem de erro
    if(!sizeof($servico["data"])){
      $this->jsonResponse(['erro' => 1, 'msg' => 'Dados não encontrados', 'data' => []]);
    }

    // caso encontre armazene os dados
    $servico = $servico["data"][0];

    // busca a porcentagem devida de acordo com o preço
    $price = (float) $servico["price"];

    $commission = $this->getCommission($price);

    // finaliza o serviço
    $servicos = $service->finish([
      'id'         => $id,
      'id_user'    => $_SESSION['id_user'],
      'commission' => $commission,
    ]);

    // em caso de falha retorna uma mensagem de erro pro usuário
    if($servicos['erro']){
      $this->jsonResponse($servicos);
    }
    
    // formata o id do serviço no padrão da table
    $id_serv = str_pad($servico["id_service"], 7, '0', STR_PAD_LEFT);

    // agrega os dados que vão ser exibidos no email
    $data_email = [
      'id'    => $id_serv,
      'name'  => $servico['description'],
      'user'  => $servico["username"],
      'total' => number_format($commission, 2, ',', '.'),
    ];

    // Realiza o import do html do email e grava em uma variavel
    ob_start(); 
    require BASE_PATH . 'App/Views/emails/service_finished.php';
    $email_html = ob_get_clean(); 

    // envia um novo email
    $mail = new Mail();
    $result = $mail->send(
      $servico["email"],
      'Serviço N° ' . $id_serv . ' Concluido com Sucesso!',
      $email_html
    );

    // caso o email não seja enviado com sucesso, anexe essa mensagem ao final do retorno pro usuário
    $email_sent = $result['erro'] ? ' porém, o Email de notificação não foi enviado!' :'';

    // retorna a resposta da finalização
    $this->jsonResponse(['erro' => 0, 'msg' => "Serviço finalizado com sucesso" . $email_sent, 'data' => []]);
  }

  // função para pegar os dados da modal de edição de serviços
  public function modal_edit(){
    // Verifica se o metodo da request é GET
    $this->verifyMethod('GET');
    // Importa a model de service
    $service = $this->model('service');

    $response = $service->get(['id' => $_GET['id']]);
    
    // em caso de falha retorna uma mensagem de erro pro usuário
    if($response['erro']){
      $this->jsonResponse($response);
    }
    
    // caso não encontre nada retorne uma mensagem de erro
    if(!sizeof($response["data"])){
      $this->jsonResponse(['erro' => 1, 'msg' => 'Dados não encontrados', 'data' => []]);
    }

    // guarda os dados a ser exibidos na modal em uma variavel
    $modal_data = [
      'id'          => $response["data"][0]["id_service"],
      'price'       => number_format($response["data"][0]["price"], 2, ',', '.'),
      'description' => $response["data"][0]["description"],
    ];
    
    // Realiza o import da modal e grava em uma variavel
    ob_start(); 
    require BASE_PATH . 'App/Views/servicos/_modal_edit.php';
    $modal = ob_get_clean();

    // retorna a modal
    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $modal]);
  }

  // função para pegar os dados da modal de cadastro de serviços
  public function modal_cad(){
    // Verifica se o metodo da request é GET
    $this->verifyMethod('GET');
    // Importa a model de service
    $service = $this->model('service');

    // Realiza o import da modal e grava em uma variavel
    ob_start(); 
    require BASE_PATH . 'App/Views/servicos/_modal_cad.php';
    $modal = ob_get_clean();

    // retorna a modal
    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $modal]);
  }
  
  // função privada para pegar o valor da comissão
  private function getCommission(float $price){
    $commission_percentage = 0;

    // a porcentagem depende do valor da comissão
    if($price <= 1000){
      $commission_percentage = 5;
    }
    else if($price > 1000 && $price <= 10000){
      $commission_percentage = 10;
    }
    else if($price > 10000){
      $commission_percentage = 20;
    }

    // calcula o valor da comissão
    return $price * ($commission_percentage / 100);
  }
}