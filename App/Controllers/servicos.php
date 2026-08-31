<?php

namespace App\Controllers;

use App\Core\Controller;
use DateTime;

class Servicos extends Controller
{
  public function index(){
    $this->cadastro();
  }
  public function cadastro(){
    if(!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])){
      header("Location: ".BASE_URL."/auth");
      exit;
    }
    echo $this->checkAndReturnStylesheet('auth', 'panel');
    $this->view('servicos/cadastro');
  }

  public function get($to_return = true){
    $this->verifyMethod('GET');

    $service = $this->model('service');

    $username = $_GET['username'] ?? '';
    $description = $_GET['description'] ?? '';
    $status = $_GET['status'] ?? '';
    $start_date = $_GET['start_date'] ?? '';
    $end_date = $_GET['end_date'] ?? '';
    

    if (!empty($end_date)) {
      $date = DateTime::createFromFormat('Y-m-d', $end_date);

      if ($date !== false) {
        $date->setTime(23, 59, 59);
        $end_date = $date->format('Y-m-d H:i:s');
      }
    }

    $servicos = $service -> getAll([
      'username'    => $username,
      'description' => $description,
      'status'      => $status,
      'start_date'  => $start_date,
      'end_date'    => $end_date,
    ]);

    if($to_return){
      $this->jsonResponse($servicos);
    }
    return $servicos;
  }

  public function filter(){
    $this->verifyMethod('GET');
    $servicos_data = $this->get(false);

    if($servicos_data['erro']){
      $this->jsonResponse($servicos_data);
    }
    $servicos_data = $servicos_data['data'];

    ob_start(); 
    require BASE_PATH . 'App/Views/dashboard/_table.php';
    $page = ob_get_clean();

    $this->jsonResponse(['erro' => 0 , 'msg' => 'Tabela retornada com sucesso', 'data' => $page]);
  }

  public function update(){
    $this->verifyMethod('POST');
    $service = $this->model('service');

    $id = $_POST['id'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $price = str_replace('.', '', $price);
    $price = str_replace(',', '.', $price);

    $servicos = $service -> update([
      'id'          => $id,
      'price'       => $price,
      'description' => $description
    ]);

    $this->jsonResponse($servicos);
  }

  public function delete(){
    $this->verifyMethod('POST');
    $service = $this->model('service');

    $id = $_POST['id'] ?? '';

    $servicos = $service -> delete([
      'id' => $id,
    ]);

    $this->jsonResponse($servicos);
  }

  public function finish(){
    $this->verifyMethod('POST');
    $service = $this->model('service');

    $id = $_POST['id'] ?? '';

    $servicos = $service -> finish([
      'id' => $id,
    ]);

    $this->jsonResponse($servicos);
  }
}