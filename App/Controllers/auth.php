<?php

namespace App\Controllers;

use App\Core\Controller;

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