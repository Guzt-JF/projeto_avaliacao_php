<?php

namespace App\Core;

class Controller
{
  public function model(string $model) {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $basePath = dirname($scriptName);

    require BASE_PATH . 'App/Models/' . $model . '.php';
    $classe = 'App\\Models\\' . $model;
    return new $classe();
  }

  public function view(string $view, array $data = []) {
    $parts = explode('/', $view);
    $import_folder = $parts[0];
    $has_method = isset($parts[1]) && !empty($parts[1]) && $parts[1] !== 'index';
    $css_file = $has_method ? $parts[1] : 'styles';
    $js_file = $has_method ? $parts[1] : 'main';

    $stylesheet = $this->checkAndReturnStylesheet($import_folder, $css_file);
    $script = $this->checkAndReturnScript($import_folder, $js_file);

    ob_start(); 
    require BASE_PATH . 'App/Views/' . $view . '.php';
    $page = ob_get_clean(); 

    require BASE_PATH . 'App/Views/base.php';
  }

  public function checkAndReturnStylesheet(string $import_folder, string $css_file){
    $css_path = BASE_PATH . 'Public/css/' . $import_folder . '/' . $css_file . '.css';

    if(file_exists($css_path)){
      return '<link rel="stylesheet" href="'.BASE_URL.'/Public/css/'.$import_folder.'/' . $css_file . '.css">';
    }
    return '';
  }

  public function checkAndReturnScript(string $import_folder, string $js_file){
    $js_path = BASE_PATH . 'Public/js/' . $import_folder . '/' . $js_file . '.js';

    if(file_exists($js_path)){
      return '<script src="'.BASE_URL.'/Public/js/'.$import_folder.'/' . $js_file . '.js"></script>';
    }
    return '';
  }

  public function verifyMethod(string $method){
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
      $this->jsonResponse(['erro' => 1, 'msg' => 'Método não permitido', 'data' => []],405);
    }

    return true;
  }

  public function jsonResponse(array $data, $http = 200){
    http_response_code($http);

    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
  }
}