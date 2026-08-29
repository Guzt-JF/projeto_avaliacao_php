<?php

namespace App\Controllers;

use App\Core\Controller;

class Servicos extends Controller
{
  public function index()
  {
    $this->cadastro();
  }
  public function cadastro()
  {
    $this->view('servicos/cadastro');
  }
}