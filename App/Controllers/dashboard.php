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
    $this->view('dashboard/index', ['header' => $header, 'user' => $_SESSION["username"], 'id_user' => $_SESSION['id_user']]);
  }

  public function get_header_data($to_return = true){
    $service = $this->model('service');

    $latest_finished = $service->getLastThree(['finished' => true, 'id_user' => $_SESSION['id_user']]);
    $latest_unfinished = $service->getLastThree(['finished' => false, 'id_user' => $_SESSION['id_user']]);

    $total_user = $service->getTotalUser(['id_user' => $_SESSION['id_user']]);
    $total_commision = $service->getTotalCommission(['id_user' => $_SESSION['id_user']]);

    $data_header['total_user'] = number_format($total_user['data'], 2, ',', '.');
    $data_header['total_commission'] = number_format($total_commision['data'], 2, ',', '.');
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