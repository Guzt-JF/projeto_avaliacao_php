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
    $css_folder = explode('/',$view)[0];
    $css_path = BASE_PATH . 'Public/css/' . $css_folder . '/styles.css';
    if(file_exists($css_path)){
      echo '<link rel="stylesheet" href="'.BASE_URL.'/Public/css/'.$css_folder.'/styles.css">';
    }
    require BASE_PATH . 'App/Views/' . $view . '.php';
  }

}