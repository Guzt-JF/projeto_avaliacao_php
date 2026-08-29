<?php

namespace App\Core;

class Controller
{
  public function model(string $model) {
    require __DIR__ . '/../Models/' . $model . '.php';
    $classe = 'App\\Models\\' . $model;
    return new $classe();
  }

  public function view(string $view, array $data = []) {
    require __DIR__ . '/../Views/' . $view . '.php';
  }

}