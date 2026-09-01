<?php

namespace App\Controllers;

use App\Core\Controller;
use DateTime;

class Servicos extends Controller
{

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

    $servicos = $service -> get([
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
    $price = (float) str_replace(',', '.', $price);
  
    $commision_percentage = 0;

    if($price <= 1000){
      $commision_percentage = 5;
    }
    else if($price > 1000 && $price <= 10000){
      $commision_percentage = 10;
    }
    else if($price > 10000){
      $commision_percentage = 20;
    }

    $commision = $price * ($commision_percentage / 100);

    $servicos = $service -> update([
      'id'          => $id,
      'price'       => $price,
      'description' => $description,
      'commision'   => $commision
    ]);

    $this->jsonResponse($servicos);
  }

  public function insert(){
    $this->verifyMethod('POST');
    $service = $this->model('service');

    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $price = str_replace('.', '', $price);
    $price = (float) str_replace(',', '.', $price);
  
    $commision_percentage = 0;

    if($price <= 1000){
      $commision_percentage = 5;
    }
    else if($price > 1000 && $price <= 10000){
      $commision_percentage = 10;
    }
    else if($price > 10000){
      $commision_percentage = 20;
    }

    $commision = $price * ($commision_percentage / 100);

    $servicos = $service -> insert([
      'price'       => $price,
      'description' => $description,
      'commision'   => $commision
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

  public function modal_edit(){
    $this->verifyMethod('GET');
    $service = $this->model('service');

    $response = $service->get(['id' => $_GET['id']]);
    
    if($response['erro']){
      $this->jsonResponse($response);
    }
    
    if(!sizeof($response["data"])){
      $this->jsonResponse(['erro' => 1, 'msg' => 'Dados não encontrados', 'data' => []]);
    }

    $modal_data = [
      'id'          => $response["data"][0]["id_service"],
      'price'       => number_format($response["data"][0]["price"], 2, ',', '.'),
      'description' => $response["data"][0]["description"],
    ];
    
    ob_start(); 
    require BASE_PATH . 'App/Views/servicos/_modal_edit.php';
    $modal = ob_get_clean();

    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $modal]);
  }

  public function modal_cad(){
    $this->verifyMethod('GET');
    $service = $this->model('service');

    ob_start(); 
    require BASE_PATH . 'App/Views/servicos/_modal_cad.php';
    $modal = ob_get_clean();

    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $modal]);
  }
}