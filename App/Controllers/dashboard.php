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

    $header = $this->getHeaderData(false);
    $this->view('dashboard/index', ['header'=>$header]);
  }

  public function getHeaderData($to_return = true){
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
}