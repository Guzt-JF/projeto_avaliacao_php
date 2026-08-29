<?php

namespace App\Controllers;

use App\core\Controller;

class Auth extends Controller
{
  public function index()
  {
    $this->model('user');
    $this->view('auth/index');
  }
  public function registro()
  {
    $this->view('auth/registro');
  }
}