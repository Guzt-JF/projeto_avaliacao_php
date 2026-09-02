<?php
// define o namespace como App\Controllers
namespace App\Controllers;

// usa o controller como extenção da clasese
use App\Core\Controller;

// Classe para a controller responsavel pelas coisas relacionadas ao dashboard
class Dashboard extends Controller
{
  // Função para abrir a pagina do dashboard
  public function index(){
    // Verifica se a session pro id do usuário existe, indicando login
    if(!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])){
      // caso não, redireciona o usuário para o login
      header("Location: ".BASE_URL."/auth");
      exit;
    }

    // caso sim, pega os dados do topo da pagina e envia o usuário para o dashboard
    $header = $this->get_header_data(false);
    $this->view('dashboard/index', ['header' => $header, 'user' => $_SESSION["username"], 'id_user' => $_SESSION['id_user']]);
  }

  // Função para pegar os dados do topo do dashboard
  // essa função pode retornar os dados direto, ou mandar via json
  // fiz assim para facilitar as funções que pegam dados com essa controller
  public function get_header_data($to_return = true){
    // Importa a model de service
    $service = $this->model('service');

    // Verifica se a conexão com o banco foi estabelecida, caso não retorna um erro
    if($service->conn == null){
      if($to_return){
        $this->jsonResponse(['erro' => 1, 'msg'=> 'Erro ao Conectar no banco de dados, verifique suas credenciais e o servidor', 'data' => []]);
      }
      else{
        $erro = 'Não foi possivel conectar com o servidor';
        require_once BASE_PATH . '/Public/404.php';
        exit;
      }
    }


    // Pega os 3 ultimos serviços finalizados 
    $latest_finished = $service->getLastThree(['finished' => true, 'id_user' => $_SESSION['id_user']]);
    // Pega os 3 ultimos serviços pendentes 
    $latest_unfinished = $service->getLastThree(['finished' => false, 'id_user' => $_SESSION['id_user']]);

    // Pega os total gerado pelo usuário com serviços concluidos
    $total_user = $service->getTotalUser(['id_user' => $_SESSION['id_user']]);
    // Pega os total de comissões gerado pelo e para o usuário com serviços concluidos
    $total_commision = $service->getTotalCommission(['id_user' => $_SESSION['id_user']]);

    // corrige a formatação e armazena os dados em uma variavel
    $data_header['total_user'] = number_format($total_user['data'], 2, ',', '.');
    $data_header['total_commission'] = number_format($total_commision['data'], 2, ',', '.');
    $data_header['latest_finished'] = $latest_finished['data'];
    $data_header['latest_unfinished'] = $latest_unfinished['data'];
    
    
    // Realiza o import do header e grava em uma variavel
    ob_start(); 
    require BASE_PATH . 'App/Views/dashboard/_header.php';
    $header = ob_get_clean();

    // retorne os dados do jeito especificado
    if($to_return){
      $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $header]);
    }
    return $header;
  }
}