<?php
// define o namespace como App\Core
namespace App\Core;

// Classe de controller, é a base para todas as controllers e basicamente cuida
// da funções fundamentais para uma controller em modelo MVC
class Controller
{
  // Função para buscar e definir a model
  public function model(string $model) {
    // Procura pelo script com base no nome informado
    require BASE_PATH . 'App/Models/' . $model . '.php';

    // Define a classe e retorna
    $classe = 'App\\Models\\' . $model;
    return new $classe();
  }

  // Função para buscar e exibir as views
  public function view(string $view, array $data = []) {
    // divide a view, obtendo a pasta e o arquivo
    $parts = explode('/', $view);

    // Define a pasta 
    $import_folder = $parts[0];
    
    // Define o metodo, caso não encontre nenhum, ele cai no padrão
    $has_method = isset($parts[1]) && !empty($parts[1]) && $parts[1] !== 'index';
    // define o nome do arquivo css padrão da pagina
    $css_file = $has_method ? $parts[1] : 'styles';
    // define o nome do arquivo js padrão da pagina
    $js_file = $has_method ? $parts[1] : 'main';

    // Busca e retorna o arquivo css
    $stylesheet = $this->checkAndReturnStylesheet($import_folder, $css_file);

    // Busca e retorna o arquivo js
    $script = $this->checkAndReturnScript($import_folder, $js_file);

    // Realiza o import da pagina e grava em uma variavel
    ob_start(); 
    require BASE_PATH . 'App/Views/' . $view . '.php';
    $page = ob_get_clean(); 

    // Envia as variaveis coletadas para a view base do projeto
    require BASE_PATH . 'App/Views/base.php';
  }

  // Função para buscar e retornar o stylesheet procurado
  public function checkAndReturnStylesheet(string $import_folder, string $css_file){
    $css_path = BASE_PATH . 'Public/css/' . $import_folder . '/' . $css_file . '.css';

    // Verifica se a rota informada existe, e caso sim ele retorna o link
    if(file_exists($css_path)){
      return '<link rel="stylesheet" href="'.BASE_URL.'/Public/css/'.$import_folder.'/' . $css_file . '.css">';
    }
    return '';
  }

  // Função para buscar e retornar o script procurado
  public function checkAndReturnScript(string $import_folder, string $js_file){
    $js_path = BASE_PATH . 'Public/js/' . $import_folder . '/' . $js_file . '.js';

   // Verifica se a rota informada existe, e caso sim ele retorna o script
    if(file_exists($js_path)){
      return '<script src="'.BASE_URL.'/Public/js/'.$import_folder.'/' . $js_file . '.js"></script>';
    }
    return '';
  }

  // função para verificar se o metodo da requisição é permitido
  public function verifyMethod(string $method){
    // Busca e compara a request da requisição com a ideal, caso não seja a mesma retorna erro
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
      $this->jsonResponse(['erro' => 1, 'msg' => 'Método não permitido', 'data' => []],405);
    }

    // Caso seja retorna true
    return true;
  }

  // Função para retornar resposta com formato JSON
  public function jsonResponse(array $data, $http = 200){
    // define o response code
    http_response_code($http);

    // define os headers para os dados serem interpretados como json
    header('Content-Type: application/json; charset=utf-8');

    // converte o array enviado para texto para poder ser escrito
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

    // interrompe todo o script
    exit;
  }
}