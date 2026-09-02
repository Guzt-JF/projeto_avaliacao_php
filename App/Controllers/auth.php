<?php
// define o namespace como App\Controllers
namespace App\Controllers;

// usa o controller como extenção da clasese
use App\Core\Controller;

// Classe para a controller responsavel pelas coisas relacionadas a autenticação
class Auth extends Controller
{
  // Função para abrir a pagina de login
  public function index() {
    $this->view('auth/index');
    // importa o style & script gerais das telas de auth
    echo $this->checkAndReturnStylesheet('auth', 'panel');
    echo $this->checkAndReturnScript('auth', 'function');
  }

  // Função para abrir a pagina de registro
  public function registro() {
    $this->view('auth/registro');
    // importa o style & script gerais das telas de auth
    echo $this->checkAndReturnStylesheet('auth', 'panel');
    echo $this->checkAndReturnScript('auth', 'function');
  }

  // Função para os usuários logarem
  public function sign_in() {
    // Verifica se o metodo da request é POST
    $this->verifyMethod('POST');

    // Importa a model de usuário
    $user_model = $this->model('user');

    // Verifica se a conexão com o banco foi estabelecida, caso não retorna um erro
    if($user_model->conn == null){
      $this->jsonResponse(['erro' => 1, 'msg'=> 'Erro ao Conectar no banco de dados, verifique suas credenciais e o servidor', 'data' => []]);
    }

    // Filtra o campo de email
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // verifica se o email e a senha estão vazios
    if (!$email || !$password) {
      $this->jsonResponse(['erro' => 1, 'msg' => 'Ops, Email ou Senha inválido', 'data' => []]);
    }

    // procura por dados de usuários com este email para encontrar a conta
    $returned_user = $user_model->getByEmail([
      'email' => $email
    ]);

    // em caso de falha retorna uma mensagem de erro pro usuário
    if($returned_user['erro']){
      $this->jsonResponse($returned_user);
    }
    
    // caso contrario armazena os dados do usuário
    $user_data = $returned_user['data'];

    // verifica se o hash da senha está correto com a senha infromada
    if(!sizeof($user_data) || 
      (
        isset($params['pasword'])  &&
        !empty($params['pasword']) &&
        !password_verify($params['pasword'], $user_data['password'])
      )
    ){
      $this->jsonResponse(['erro' => 1, 'msg' => 'Ops, Email ou Senha inválido', 'data' => []]);
    }
    
    // caso contrario armazena os dados do usuário na session
    $_SESSION['username'] = $user_data["name"];
    $_SESSION['id_user']  = $user_data["id_user"];
    $_SESSION['email']    = $user_data["email"];

    // e retona uma mensagem de sucesso pro usuário
    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao logar', 'data' => []]);
  }

  // Função para cadastrar usuários
  public function sign_up() {
    // Verifica se o metodo da request é POST
    $this->verifyMethod('POST');

    // Importa a model de usuário
    $user_model = $this->model('user');

    // Verifica se a conexão com o banco foi estabelecida, caso não retorna um erro
    if($user_model->conn == null){
      $this->jsonResponse(['erro' => 1, 'msg'=> 'Erro ao Conectar no banco de dados, verifique suas credenciais e o servidor', 'data' => []]);
    }

    $username = $_POST['username'];
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    // verifica se o nome de usuário, email ou senha estão vazios
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

    // procura por dados de usuários com este email para garantir que já não foi utilizado
    $returned_user = $user_model->getByEmail([
      'email' => $email
    ]);

    // em caso de falha retorna uma mensagem de erro pro usuário
    if($returned_user['erro']){
      $this->jsonResponse($returned_user);
    }
    
    // caso contrario armazena os dados do usuário para verificar se o email já foi utilizado
    $user_data = $returned_user['data'];
    if(sizeof($user_data)){
      $this->jsonResponse(['erro' => 1, 'msg' => 'Email já utilizado', 'data' => []]);
    }

    // caso contrário gera um hash da senha
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // insere os dados do usuário no banco de dados
    $inserted_user = $user_model->insertUser([
      'username' => $username,
      'email'    => $email,
      'password' => $password_hash
    ]);
    
    // em caso de falha retorna uma mensagem de erro pro usuário
    if($inserted_user['erro']){
      $this->jsonResponse($inserted_user);
    }

    // caso contrario armazena os dados do usuário na session
    $_SESSION['username'] = $username;
    $_SESSION['id_user']  = $inserted_user['data']['insertedId'];
    $_SESSION['email']    = $email;

    // e retona uma mensagem de sucesso pro usuário
    $this->jsonResponse(['erro' => 0, 'msg' => 'Sucesso ao cadastrar usuário', 'data' => []]);
  }
}