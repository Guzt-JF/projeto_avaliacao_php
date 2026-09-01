<?php 
// define o namespace como App\Core
namespace App\Core;

// usa a exception para lançar um erro
use Exception;

// Classe de router, feita para cuidar dos redirecionamentos
// basicamente pega a url, e procura pela classe e pelo metodo sendo requisitados
class Router {
  public function __construct() {
    try {
      // Busca a url digitada
      $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      
      // busca o nome do script base
      $scriptName = $_SERVER['SCRIPT_NAME'];
      // com isso consegue o nome da pasta base do projeto
      $basePath = dirname($scriptName);

      // remove o caminho da pasta base da URL, deixando apenas a rota que será processada pelo Router
      if ($basePath !== '/') {
        $request = substr($request, strlen($basePath));
      }

      // Remove as barras do inicio e do fim da rota
      $url = trim($request, '/');

      // divide a url em partes com base no /
      $parts = explode('/', $url);

      // a primeira parte é a controller sendo requisitada
      $controller = isset($parts[0]) && !empty($parts[0]) ? $parts[0] : 'dashboard';
      
      // a segunda parte é o metodo snedo requisitado
      $method = isset($parts[1]) && !empty($parts[1]) ? $parts[1] : 'index';

      // mota a classe com o nome da controller
      $controllerClass = "App\\Controllers\\{$controller}";

      // verifica se a classe existe, caso não lança um erro
      if (!class_exists($controllerClass)) {
        throw new Exception("Controller não encontrado");
      }

      // monta a classe
      $controller = new $controllerClass();

      // verifica se o metodo requisitado existe, caso não lança um erro
      if (!method_exists($controller, $method)) {
        throw new Exception("Método não encontrado");
      }

      // caso exista executa a classe com o metodo escolhido
      $controller->$method();
    } catch (Exception $e) {
      // em caso de erro, armazena o erro em uma variavel, e mostra a tela de erro, com o problema escrito na tela
      $erro = $e->getMessage();
      require_once BASE_PATH . '/Public/404.php';
    }
  }
}