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

    $css_path = BASE_PATH . 'Public/css/' . $import_folder . '/' . $css_file . '.css';
    $js_path = BASE_PATH . 'Public/js/' . $import_folder . '/' . $js_file . '.js';

    if(file_exists($css_path)){
      echo '<link rel="stylesheet" href="'.BASE_URL.'/Public/css/'.$import_folder.'/' . $css_file . '.css">';
    }

    require BASE_PATH . 'App/Views/' . $view . '.php';

    if(file_exists($js_path)){
      echo '<script src="'.BASE_URL.'/Public/js/'.$import_folder.'/' . $js_file . '.js"></script>';
    }
  }

}