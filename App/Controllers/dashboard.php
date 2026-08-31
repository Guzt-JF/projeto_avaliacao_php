<?php

namespace App\Controllers;

use App\Core\Controller;

class Dashboard extends Controller
{
  public function index(){
    if(!isset($_SESSION['id_user']) || empty($_SESSION['id_user'])){
      header("Location: ".BASE_URL."/auth");
      exit;
    }

    $header = $this->get_header_data(false);
    $this->view('dashboard/index', ['header'=>$header]);
  }

  public function get_header_data($to_return = true){
    $service = $this->model('service');

    $latest_finished = $service->getLastThree(['finished' => true]);
    $latest_unfinished = $service->getLastThree(['finished' => false]);

    $total_user = $service->getTotalUser();

    $data_header['total_user'] = $total_user['data'];
    $data_header['latest_finished'] = $latest_finished['data'];
    $data_header['latest_unfinished'] = $latest_unfinished['data'];
    
    ob_start(); 
    require BASE_PATH . 'App/Views/dashboard/_header.php';
    $header = ob_get_clean();

    if($to_return){
      $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $header]);
    }
    return $header;
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
    require BASE_PATH . 'App/Views/dashboard/_modal_edit.php';
    $modal = ob_get_clean();

    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao retornar dados', 'data' => $modal]);
  }
}