<?php

namespace App\Controllers;

use App\Core\Controller;

class Auth extends Controller
{
  public function index() {
    $this->view('auth/index');
    echo $this->checkAndReturnStylesheet('auth', 'panel');
    echo $this->checkAndReturnScript('auth', 'function');
  }

  public function registro() {
    $this->view('auth/registro');
    echo $this->checkAndReturnStylesheet('auth', 'panel');
    echo $this->checkAndReturnScript('auth', 'function');
  }

  public function signin() {
    $this->verifyMethod('POST');

    $user_model = $this->model('user');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if (!$email || !$password) {
      $this->jsonResponse(['erro' => 1, 'msg' => 'Email ou senha inválidos', 'data' => []]);
    }

    $returned_user = $user_model->getByEmail([
      'email' => $email
    ]);

    if($returned_user['erro']){
      $this->jsonResponse($returned_user);
    }
    
    $user_data = $returned_user['data'];

    if(!sizeof($user_data) || 
      (
        isset($params['pasword'])  &&
        !empty($params['pasword']) &&
        !password_verify($params['pasword'], $user_data['password'])
      )
    ){
      $this->jsonResponse(['erro' => 1, 'msg' => 'Email ou senha inválidos', 'data' => []]);
    }

    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao logar', 'data' => $user_data]);
  }

  public function signup() {
    $this->verifyMethod('POST');

    $user_model = $this->model('user');
    $username = filter_input(INPUT_POST, 'username');
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    if (!$email || !$password || !$username) {
      $this->jsonResponse([
        'erro' => 1,
        'msg'  => 'Campos Vazios',
        'data' => [
          'email'    => !$email,
          'password' => !$password,
          'username' => !$username,
        ]
      ]);
    }

    $returned_user = $user_model->getByEmail([
      'email' => $email
    ]);

    if($returned_user['erro']){
      $this->jsonResponse($returned_user);
    }
    
    $user_data = $returned_user['data'];
    if(sizeof($user_data)){
      $this->jsonResponse(['erro' => 1, 'msg' => 'Email já utilizado', 'data' => []]);
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $inserted_user = $user_model->insertUser([
      'username' => $username,
      'email'    => $email,
      'password' => $password_hash
    ]);
    
    if($inserted_user['erro']){
      $this->jsonResponse($inserted_user);
    }

    $_SESSION['username'] = $username;
    $_SESSION['id_user']  = $inserted_user['data']['insertedId'];
    $_SESSION['email']    = $email;

    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao cadastrar usuário', 'data' => []]);
  }
}