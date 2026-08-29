<?php 

namespace App\Core;

use Exception;

class Router {
  public function __construct() {
    try {
      $request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
      
      $scriptName = $_SERVER['SCRIPT_NAME'];
      $basePath = dirname($scriptName);

      if ($basePath !== '/') {
        $request = substr($request, strlen($basePath));
      }

      $request = trim($request, '/');

      $url = trim(parse_url($request, PHP_URL_PATH), '/');

      $parts = explode('/', $url);
      $controller = isset($parts[0]) && !empty($parts[0]) ? $parts[0] : 'dashboard';
      $method = isset($parts[1]) && !empty($parts[1]) ? $parts[1] : 'index';

      $controllerClass = "App\\Controllers\\{$controller}";

      if (!class_exists($controllerClass)) {
        throw new Exception("Controller não encontrado");
      }

      $controller = new $controllerClass();

      if (!method_exists($controller, $method)) {
        throw new Exception("Método não encontrado");
      }

      $controller->$method();
    } catch (Exception $e) {
      $erro = $e->getMessage();
      require_once __DIR__ . '/../../Public/404.php';
    }
  }
}